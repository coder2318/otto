<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { fade } from 'svelte/transition'
    import { inertia, useForm } from '@inertiajs/svelte'
    import Fa from 'svelte-fa'
    import { faTrash } from '@fortawesome/free-solid-svg-icons'
    import { start, done } from '@/components/Loading.svelte'
    import { bytes } from '@/service/helpers'
    import { dayjs } from '@/service/dayjs'
    import createOttos1 from '@/assets/img/create-ottos-1.svg'
    import createOttos2 from '@/assets/img/create-ottos-2.svg'
    import createOttos3 from '@/assets/img/create-ottos-3.svg'
    import ChapterNameBanner from '@/components/Chapters/ChapterNameBanner.svelte'
    import ChapterTipBanner from '@/components/Chapters/ChapterTipBanner.svelte'
    import goBackLinkIcon from '@/assets/img/go-back-link-icon.svg'
    import microphoneBtn from '@/assets/img/microphone-btn.svg'
    import TranscribeBtn from '@/components/SVG/buttons/transcribe-btn.svg.svelte'

    export let chapter: { data: App.Chapter }

    const form = useForm({
        attachments: [],
    })

    function submit() {
        $form.post(`/chapters/${chapter.data.id}/files`, {
            onStart: start,
            onFinish: done,
            hideProgress: true,
        })
    }

    let dialog: HTMLDialogElement
    let deleting: number = null

    function deleteRecording(index: number) {
        deleting = index
        dialog.showModal()
    }

    function confirmDelete() {
        $form.delete(`/chapters/${chapter.data.id}/files/${deleting}`)
        dialog.close()
    }

    function select(id: number, checked: boolean) {
        checked ? $form.attachments.push(id) : $form.attachments.splice($form.attachments.indexOf(id), 1)
        $form.attachment = $form.attachments
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {chapter.data.title}</title>
</svelte:head>

<ChapterNameBanner title={chapter.data.title} />
<ChapterTipBanner
    title="OttoStory Tool Tips:"
    tip="This is your memory box – where all your previous recordings and responses are stored.  You can click on a version or recording to transcribe and edit."
/>

<form on:submit|preventDefault={submit} in:fade>
    <main class="attachments">
        <div class="otto-container">
            <div class="block">
                <div class="block__top">
                    <h3 class="fz_h3 title">Select Recordings</h3>
                    {#if $form.attachments.length}
                        <button type="submit" class="otto-btn-svg">
                            <TranscribeBtn />
                        </button>
                    {/if}
                </div>
                <div class="attachments__records">
                    {#each chapter.data?.attachments as recording}
                        <div class="card bg-neutral">
                            <div class="card__col">
                                <div class="form-control">
                                    <label class="label cursor-pointer">
                                        <input
                                            type="checkbox"
                                            class="checkbox-secondary checkbox"
                                            class:checkbox-success={recording.transcribed}
                                            on:change={(e) => select(recording.id, e.currentTarget.checked)}
                                        />
                                    </label>
                                </div>
                                <div class="col-span-3 flex flex-1 flex-col lg:col-span-1">
                                    <span class="name">{recording.name}</span>
                                    <span class="record-info flex gap-2 text-xs opacity-50">
                                        <span>Size: {bytes(recording.size)}</span>
                                        <span>Transcribed: {recording.transcribed ? 'Yes' : 'No'}</span>
                                        <span>Uploaded: {dayjs(recording.created_at).format('YYYY-MM-DD HH:mm')}</span>
                                    </span>
                                </div>
                            </div>
                            <div class="card__col">
                                <div class="col-span-4 mx-auto">
                                    {#if recording.is_media}
                                        <audio src={recording.url} controls />
                                    {:else}
                                        <a
                                            href={recording.url}
                                            target="_blank"
                                            class="btn btn-primary btn-outline btn-xs rounded-full">Preview</a
                                        >
                                    {/if}
                                </div>
                                <button
                                    type="button"
                                    class="btn btn-error btn-sm"
                                    on:click|preventDefault={() => deleteRecording(recording.id)}
                                >
                                    <Fa icon={faTrash} />
                                </button>
                            </div>
                        </div>
                    {:else}
                        <div class="createStory__blocks">
                            <a
                                class="createStory__block record bg-base-100"
                                href="/chapters/{chapter.data.id}/record"
                                use:inertia
                            >
                                <img src={createOttos1} alt="icon" />
                                <span class="createStory__block-title">Record your Story</span>
                            </a>

                            <a class="createStory__block upload" href="/chapters/{chapter.data.id}/write" use:inertia>
                                <img src={createOttos2} alt="icon" />
                                <span class="createStory__block-title">Type your Story</span>
                            </a>
                            <a class="createStory__block type" href="/chapters/{chapter.data.id}/upload" use:inertia>
                                <img src={createOttos3} alt="icon" />
                                <span class="createStory__block-title">Upload File</span>
                            </a>
                        </div>
                    {/each}
                    {#if chapter.data?.attachments}
                        <a href="/chapters/{chapter.data.id}/edit" class="addNewRecord" use:inertia>
                            <span>Add New Recording</span>
                            <img src={microphoneBtn} alt="microphone" />
                        </a>
                    {/if}
                </div>
            </div>
            <a href="/chapters/{chapter.data.id}/edit" class="goBackLink" use:inertia>
                <img src={goBackLinkIcon} alt="Record" />
                <span>Record more</span>
            </a>
        </div>
    </main>
</form>

<dialog bind:this={dialog} class="modal">
    <form method="dialog" class="modal-box">
        <h3 class="text-lg font-bold">Are you sure you want to delete this recording?</h3>
        <div class="modal-action">
            <button class="btn btn-error btn-sm" on:click|preventDefault={confirmDelete}>Delete</button>
            <button class="btn btn-sm" on:click={() => dialog.close()}>Close</button>
        </div>
    </form>
</dialog>

<style lang="scss">
    .attachments {
        position: relative;
        padding-bottom: 100px;

        .block {
            position: relative;
            background: #fff;
            padding: 32px 48px 48px 48px;
            border-radius: 24px;
            margin-bottom: 20px;

            @media (max-width: 767px) {
                padding: 16px;
            }

            &__top {
                margin-bottom: 32px;
                display: flex;
                align-items: center;
                justify-content: space-between;

                .title {
                    color: #06192d;
                }
            }
        }

        .card {
            border: 1px solid #1d80e2;
            margin-bottom: 16px;
            border-radius: 28px;
            padding: 10px 20px;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;

            @media (max-width: 991px) {
                flex-direction: column;
                align-items: flex-start;
            }

            &__col {
                display: flex;
                align-items: center;

                &:nth-child(1) {
                    padding-right: 15px;

                    @media (max-width: 991px) {
                        margin-bottom: 10px;
                        align-items: flex-start;
                    }
                }
                &:nth-child(2) {
                    @media (max-width: 767px) {
                        flex-direction: column;
                        align-items: flex-start;
                    }
                }
            }

            .checkbox {
                border: 2px solid #0c345c;
                width: 24px;
                height: 24px;
                margin-right: 16px;
                transition: 0s !important;

                &:checked {
                    background-image: linear-gradient(-45deg, transparent 65%, #0c345c 65.99%),
                        linear-gradient(45deg, transparent 75%, #0c345c 75.99%),
                        linear-gradient(-45deg, #0c345c 40%, transparent 40.99%),
                        linear-gradient(45deg, #0c345c 30%, #ffffff 30.99%, #ffffff 40%, transparent 40.99%),
                        linear-gradient(-45deg, #ffffff 50%, #0c345c 50.99%);
                }
            }

            .btn-error {
                margin-left: 10px;
                @media (max-width: 767px) {
                    margin-top: 1px;
                }
            }

            .name {
                font-size: 20px;
                color: #0c345c;
                line-height: 1.3;
                margin: 0;
            }

            .record-info {
                line-height: 1.3;
                @media (max-width: 767px) {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 0;
                }
                span {
                    color: #1a1a1a;
                    opacity: 0.5;
                    font-size: 16px;
                    @media (max-width: 767px) {
                        margin: 0;
                        padding: 0;
                    }
                }
            }
        }

        .addNewRecord {
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px dashed #bfbfbf;
            border-radius: 28px;
            height: 76px;
            cursor: pointer;
            transition: 0.3s;

            &:hover {
                background-color: rgba(191, 191, 191, 0.2);
            }

            span {
                color: #0c345c;
                font-size: 24px;
                margin-right: 16px;
            }
        }
    }

    .createStory__blocks {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        width: 100%;
        max-width: 720px;
        margin: 0 auto;
    }
    .createStory__block {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex-basis: calc(100% / 3 - 30px);
        margin-right: 30px;
        height: 118px;
        background-color: #fff;
        border: 3px solid transparent;
        transition: 0.3s linear;
        border-radius: 30px;
        cursor: pointer;
        padding: 5px;

        &:hover {
            border: 3px solid #ffd886;
        }

        &-title {
            font-size: 20px;
            line-height: 1.1;
            text-align: center;
            color: #06192d;
            font-weight: 400;
            margin-top: 12px;
        }

        @media (max-width: 767px) {
            flex-basis: calc(100%);
            margin-bottom: 16px;
        }
    }
</style>
