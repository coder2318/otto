<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { fade } from 'svelte/transition'
    import { useForm } from '@inertiajs/svelte'
    import Breadcrumbs from '@/components/Demo/Breadcrumbs.svelte'
    import Logo from '@/components/SVG/logo-o.svg.svelte'
    import AudioRecorder from '@/components/AudioRecorder.svelte'
    import { start, done } from '@/components/Loading.svelte'
    import { onMount } from 'svelte'
    import { autosize } from '@/service/svelte'

    export let chapter: { data: App.Chapter }

    let carousel: HTMLDivElement

    const form = useForm({
        title: chapter.data.title,
        attachments: null,
        status: chapter.data.status,
    })

    function submit(event: SubmitEvent) {
        $form
            .transform((data) => ({
                _method: 'PUT',
                ...data,
                redirect: 'dashboard.demo.write',
                status: event.submitter.dataset?.status ?? data.status,
            }))
            .post(`/demo`, {
                forceFormData: true,
                onStart: start,
                onFinish: done,
            })
    }

    onMount(() => {
        let timer = 0
        const interval = setInterval(() => {
            timer += 500

            if (timer > 10000) {
                timer = 0
                carousel.scrollLeft =
                    carousel.scrollLeft >= carousel.scrollWidth - carousel.offsetWidth
                        ? 0
                        : carousel.scrollLeft + carousel.offsetWidth
            }
        }, 500)

        return () => clearInterval(interval)
    })
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {chapter.data.title}</title>
</svelte:head>

<Breadcrumbs step={2} />

<form on:submit|preventDefault={submit} in:fade>
    <main class="container card m-4 mx-auto rounded-xl bg-base-200 px-4">
        <div class="card-body gap-4 p-4 md:p-8">
            <textarea
                class="textarea resize-none card-title textarea-ghost font-serif text-2xl font-normal italic text-primary md:text-3xl lg:text-4xl"
                bind:value={$form.title}
                use:autosize={{ offset: 2 }}
                rows="1"
            />

            <div class:lg:grid-cols-2={chapter.data?.question?.sub_questions?.length} class="grid grid-cols-1 gap-8">
                {#if chapter.data?.question?.sub_questions?.length}
                    <div class="card min-h-[200px] rounded-xl bg-neutral-focus text-neutral-content">
                        <div class="card-body">
                            <div class="card-title gap-2">
                                <Logo class="h-12" />
                                <span>Start with a Memory:</span>
                            </div>
                            <div class="carousel h-full overflow-hidden" bind:this={carousel}>
                                {#each chapter.data?.question?.sub_questions as question}
                                    <p
                                        class="carousel-item flex h-full w-full flex-wrap content-center justify-center text-center text-xl"
                                    >
                                        {question}
                                    </p>
                                {/each}
                            </div>
                        </div>
                    </div>
                {/if}

                <div class="card rounded-xl bg-neutral">
                    <div class="card-body gap-4">
                        <AudioRecorder min={1} maxFiles={1} bind:recordings={$form.attachments} />
                    </div>
                </div>
            </div>
        </div>
    </main>

    {#if $form.isDirty}
        <section class="container mx-auto mb-8 flex justify-end">
            <button class="btn btn-primary btn-outline rounded-full" data-status="draft" type="submit">
                Transcribe
            </button>
        </section>
    {/if}
</form>
