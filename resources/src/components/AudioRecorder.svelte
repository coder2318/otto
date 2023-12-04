<script lang="ts">
    import { fade } from 'svelte/transition'
    import { msToTime } from '@/service/helpers'
    import { faMicrophone, faPause, faStop, faTrash } from '@fortawesome/free-solid-svg-icons'
    import dayjs from 'dayjs'
    import Fa from 'svelte-fa'
    import { flash } from './Toast.svelte'
    import languages from '@/data/translate_languages.json'
    import microphone from '@/assets/img/microphone.svg'
    import AudioBubble from './AudioBubble.svelte'
    import { onDestroy } from 'svelte'

    let dialog: HTMLDialogElement
    let dialogStop: HTMLDialogElement
    let player: HTMLAudioElement
    let mediaRecorder: MediaRecorder
    let interval: number
    let timer: number
    let paused: boolean = false
    let heights = []

    let translate = {
        source: null as string | null,
        target: null as string | null,
    }

    export let min: number | null = null
    export let max: number | null = null
    export let recordings: Array<{ file: File; translate?: typeof translate }>
    export let maxFiles: number = null
    export let transcribe

    $: setTranslation(translate)

    function setTranslation(options) {
        recordings?.forEach((record) => (record.translate = options))
        recordings = recordings
    }

    function createBubbles(stream: MediaStream, bubbles: number = 3) {
        const audioContext = new AudioContext()
        const source = audioContext.createMediaStreamSource(stream)
        const analyser = audioContext.createAnalyser()
        source.connect(analyser)

        analyser.fftSize = 32
        const dataArray = new Uint8Array(bubbles)

        function draw() {
            requestAnimationFrame(draw)

            if (paused) {
                return
            }

            analyser.getByteFrequencyData(dataArray)

            heights.length = 0

            for (let i = 0; i < bubbles; i++) {
                heights.push(dataArray[i])
            }
        }

        draw()
    }

    function startRecording() {
        if (!stopRecording()) {
            return
        }

        navigator.mediaDevices.getUserMedia({ audio: true, video: false }).then((stream) => {
            const recordedChunks = []
            mediaRecorder = new MediaRecorder(stream)
            let format = null

            mediaRecorder.addEventListener('dataavailable', function (e) {
                if (!format) format = e.data.type
                if (e.data.size > 0) recordedChunks.push(e.data)
            })

            mediaRecorder.addEventListener('stop', function () {
                const getFormat = () =>
                    ({
                        'audio/webm': 'weba',
                        'audio/ogg': 'ogg',
                        'audio/wav': 'wav',
                    })[format] ?? format

                recordings = [
                    ...(recordings ?? []),
                    {
                        translate,
                        file: new File(
                            recordedChunks,
                            `audio_${dayjs().format('YYYY-MM-DD_HH-mm-ss')}.${getFormat()}`,
                            {
                                type: format,
                            }
                        ),
                    },
                ]

                stream.getTracks().forEach((track) => track.stop())
                transcribe(event)
            })

            createBubbles(stream)

            mediaRecorder.start()
            timer = 0
            interval = setInterval(() => {
                if (max && max < timer) {
                    return stopRecording()
                }
                timer += 100
            }, 100)
        })
    }

    function stopRecording() {
        if (timer && timer < min) {
            flash({
                message: `Minimum recording time is ${msToTime(min)}`,
                type: 'alert-warning',
            })

            return false
        }
        timer = null
        clearInterval(interval)
        mediaRecorder?.stop()
        mediaRecorder = null

        return true
    }

    function confirmStopRecording() {
        timer = null
        clearInterval(interval)
        mediaRecorder?.stop()
        mediaRecorder = null
        dialogStop.close()
    }

    function pauseRecording() {
        if (!paused) {
            mediaRecorder.pause()
            clearInterval(interval)
        } else {
            mediaRecorder.resume()
            interval = setInterval(() => {
                if (max && max < timer) {
                    return stopRecording()
                }
                timer += 100
            }, 100)
        }

        paused = !paused
    }

    let deleting: number = null

    function deleteRecording(index: number) {
        deleting = index
        dialog.showModal()
    }

    function confirmDelete() {
        recordings.splice(deleting, 1)
        recordings = recordings.length ? recordings : null
        dialog.close()
    }

    onDestroy(() => {
        clearInterval(interval)
        mediaRecorder?.stop()
        mediaRecorder = null
    })
</script>

<div class="flex flex-col items-center justify-center">
    <span class="recordAudio-title font-serif">
        {#if !timer}
            <p>Press to Start Recording</p>
        {:else}
            <p>Recording in progress</p>
        {/if}
    </span>

    {#if !mediaRecorder}
        <button
            type="button"
            class="startRecordBtn my-8"
            disabled={maxFiles !== null && recordings?.length >= maxFiles}
            on:click|preventDefault={() => (mediaRecorder ? stopRecording() : startRecording())}
            in:fade
        >
            <img src={microphone} alt="Microphone" />
        </button>
    {:else}
        <AudioBubble {heights} />
    {/if}

    {#if !timer}
        <div class="form-control">
            <label class="label flex">
                <span class="label-text">Source Language:</span>
                <select
                    class="selectLanguage select select-bordered select-ghost"
                    name="language"
                    bind:value={translate.source}
                >
                    <option value={null}>Recognize</option>
                    {#each languages as language}
                        <option value={language.code}>{language.language}</option>
                    {/each}
                </select>
            </label>
        </div>
    {:else}
        <div class="palyerControls">
            <div class="palyerTime">
                <span class="palyerTime-variable">{msToTime(timer)}</span>
                <span class="palyerTime-static">/ {msToTime(max)}</span>
            </div>
            <div class="palyerControls__buttons">
                <button
                    class="btn btn-circle btn-neutral border border-neutral-content/50"
                    type="button"
                    on:click|preventDefault={() => dialogStop.showModal()}
                >
                    <Fa icon={faStop} />
                </button>
                <button
                    class="btn btn-circle btn-neutral border border-neutral-content/50"
                    type="button"
                    on:click|preventDefault={pauseRecording}
                >
                    <Fa icon={paused ? faMicrophone : faPause} />
                </button>
            </div>
        </div>
    {/if}

    {#each recordings ?? [] as recording, i (recording.file.name)}
        <div class="group relative flex items-center justify-center gap-2 transition-all">
            <audio bind:this={player} src={URL.createObjectURL(recording.file)} controls />
            <button
                type="button"
                class="btn btn-circle btn-error btn-outline btn-sm text-error"
                on:click={() => deleteRecording(i)}
            >
                <Fa icon={faTrash} />
            </button>
        </div>
    {/each}
    {#if max && !recordings?.length && !timer}
        <div class="flex flex-col items-center justify-center gap-2">
            <h6 class="text-2xl text-primary">Press Start Recording</h6>
            <p>You have {msToTime(max)} minutes to record your answer.</p>
        </div>
    {/if}
    <div class="recordAudio__tip">
        {#if timer > min}
            <p class="tip-first">OttoStory AI Transcription Unlocked!</p>
        {:else}
            <p class="tip-second">Record at least {msToTime(min)} to unlock Transcribing with OttoStory AI</p>
        {/if}
        {#if timer}
            <div class="recordAudio-progress" style="width: {(timer / 1000) * 1.66}%"></div>
        {/if}
    </div>
</div>

<dialog bind:this={dialog} class="modal">
    <form method="dialog" class="modal-box">
        <h3 class="text-lg font-bold">Are you sure you want to delete this recording?</h3>
        <div class="modal-action">
            <button class="btn btn-error btn-sm" on:click|preventDefault={confirmDelete}>Delete</button>
            <button class="btn btn-sm" on:click={() => dialog.close()}>Close</button>
        </div>
    </form>
</dialog>

<dialog bind:this={dialogStop} class="modal">
    <form method="dialog" class="modal-box">
        <h3 class="text-lg font-bold">Are you sure you want to stop this recording?</h3>
        <div class="modal-action">
            <button class="btn btn-error btn-sm" data-status="draft" on:click={confirmStopRecording}>Stop</button>
            <button class="btn btn-sm" on:click={() => dialogStop.close()}>Close</button>
        </div>
    </form>
</dialog>

<style lang="scss">
    .startRecordBtn {
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        background-color: #ffbe33;
        width: 120px;
        height: 120px;
        border-radius: 100%;
        transition: 0.3s;
        cursor: pointer;

        &:hover {
            transform: scale(1.1);
        }
    }

    .recordAudio {
        &-title {
            font-size: 42px;
            color: #06192d;
            line-height: 1.1;
            text-align: center;
            display: block;

            @media (max-width: 767px) {
                font-size: 32px;
            }

            p {
                color: inherit;
                display: block;
            }
        }

        &__tip {
            display: flex;
            flex-direction: column;
            align-items: center;
            background: rgba(255, 216, 133, 0.6);
            width: 100%;
            padding: 12px 10px;
            border-radius: 12px;
            margin-top: 20px;

            p {
                font-size: 24px;
                color: #0c345c;
                text-align: center;
                font-weight: 500;
                line-height: 1.1;

                @media (max-width: 767px) {
                    font-size: 18px;
                }
            }
        }

        &-progress {
            height: 10px;
            width: auto;
            max-width: 100%;
            background-color: #0c345c;
            border-radius: 40px;
            margin-top: 10px;
            transition: width 0s ease;
        }
    }

    .label-text {
        font-size: 18px;
        margin-right: 20px;
        color: #1a1a1a;
    }

    .selectLanguage {
        max-width: 150px;
        min-height: auto;
        height: 42px;
        border-radius: 40px;
        border: 1px solid #1d80e2;
        font-size: 18px;
        color: #1a1a1a;
        font-weight: 700;
        padding-left: 16px;
        text-overflow: ellipsis;
        white-space: nowrap;

        &:focus {
            box-shadow: none;
            outline: none;
            background-color: #fff;
        }
    }

    .palyerControls {
        display: flex;

        .palyerTime {
            display: flex;
            align-items: center;
            margin-right: 40px;

            &-variable {
                margin-right: 6px;
                font-size: 24px;
                color: #1a1a1a;
            }

            &-static {
                font-size: 24px;
                color: #bfbfbf;
            }
        }

        &__buttons {
            display: flex;
            align-items: center;

            button {
                margin: 0 5px;
                transition: 0.3s;

                &:hover {
                    transform: scale(1.1);
                }
            }
        }
    }
</style>
