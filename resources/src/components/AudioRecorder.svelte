<script lang="ts">
    import { msToTime } from '@/service/helpers'
    import { faMicrophone, faStop, faTrash } from '@fortawesome/free-solid-svg-icons'
    import dayjs from 'dayjs'
    import Fa from 'svelte-fa'
    import { flash } from './Toast.svelte'
    import languages from '@/data/translate_languages.json'

    let dialog: HTMLDialogElement
    let player: HTMLAudioElement
    let mediaRecorder: MediaRecorder
    let interval: number
    let timer: number

    let translate = {
        source: null as string | null,
        target: null as string | null,
    }

    export let min: number = 1 * 60 * 1000
    export let max: number = 5 * 60 * 1000
    export let recordings: Array<{ file: File; translate?: typeof translate }>
    export let maxFiles: number = null

    $: setTranslation(translate)

    function setTranslation(options) {
        recordings?.forEach((record) => (record.translate = options))
        recordings = recordings
    }

    function startRecording() {
        navigator.mediaDevices.getUserMedia({ audio: true, video: false }).then((stream) => {
            const recordedChunks = []
            mediaRecorder = new MediaRecorder(stream, {
                mimeType: 'audio/webm',
            })

            mediaRecorder.addEventListener('dataavailable', function (e) {
                if (e.data.size > 0) recordedChunks.push(e.data)
            })

            mediaRecorder.addEventListener('stop', function () {
                recordings = [
                    ...(recordings ?? []),
                    {
                        translate,
                        file: new File(recordedChunks, `audio_${dayjs().format('YYYY-MM-DD_HH-mm-ss')}.weba`, {
                            type: 'audio/webm',
                        }),
                    },
                ]
            })

            mediaRecorder.start()
            timer = 0
            interval = setInterval(() => {
                if (max - 10 < (timer += 10)) {
                    stopRecording()
                }
            }, 10)
        })
    }

    function stopRecording() {
        if (timer < min) {
            return flash({
                message: `Minimum recording time is ${msToTime(min)}`,
                type: 'alert-warning',
            })
        }
        timer = null
        clearInterval(interval)
        mediaRecorder.stop()
        mediaRecorder = null
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
</script>

<div class="flex flex-col items-center justify-center gap-4">
    <div class="mask mask-circle bg-primary/5 p-4">
        <div class="mask mask-circle bg-primary/10 p-4">
            <div class="mask mask-circle bg-primary/30 p-4">
                <div class="mask mask-circle bg-primary p-4" class:animate-pulse={!!mediaRecorder}>
                    <div class="mask mask-circle bg-neutral">
                        <button
                            type="button"
                            class="btn btn-circle btn-ghost btn-lg text-4xl"
                            disabled={maxFiles !== null && recordings?.length >= maxFiles}
                            on:click|preventDefault={() => (mediaRecorder ? stopRecording() : startRecording())}
                        >
                            <label class="swap-rotate swap">
                                <input type="checkbox" checked={!!mediaRecorder} />
                                <div class="swap-on text-secondary">
                                    <Fa icon={faStop} />
                                </div>
                                <div class="swap-off text-primary">
                                    <Fa icon={faMicrophone} />
                                </div>
                            </label>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {#if !timer}
        <div class="form-control">
            <label class="label flex gap-4">
                <span class="label-text">Source Language:</span>
                <select class="select select-bordered select-ghost" name="language" bind:value={translate.source}>
                    <option value={null}>Recognize</option>
                    {#each languages as language}
                        <option value={language.code}>{language.language}</option>
                    {/each}
                </select>
            </label>
            <label class="label flex gap-4">
                <span class="label-text">Target Language:</span>
                <select class="select select-bordered select-ghost" name="language" bind:value={translate.target}>
                    <option value={null}>Do not translate</option>
                    {#each languages as language}
                        <option value={language.code}>{language.language}</option>
                    {/each}
                </select>
            </label>
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
    {#if timer}
        <div class="flex items-center justify-center gap-2 rounded-full bg-primary/10 p-4 text-primary">
            <span class="w-12"> {msToTime(timer)}</span>
            <progress class="progress progress-primary w-36" value={timer} {max} />
            <span class="w-12"> {msToTime(max)}</span>
        </div>
    {:else if !recordings?.length}
        <div class="flex flex-col items-center justify-center gap-2">
            <h6 class="text-2xl text-primary">Press Start Recording</h6>
            <p>You have {msToTime(max)} minutes to record your answer.</p>
        </div>
    {/if}
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
