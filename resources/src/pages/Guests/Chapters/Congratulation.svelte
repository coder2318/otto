<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { fade } from 'svelte/transition'
    import Breadcrumbs from '@/components/Chapters/Breadcrumbs.svelte'
    import FingerUp from '@/components/SVG/finger-up.svg.svelte'

    export let chapter: { data: App.Chapter }

    let words = Math.round((chapter.data.content?.split(' ').filter((v) => v).length ?? 0) / 500 + 1)
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {chapter.data.title}</title>
</svelte:head>

<Breadcrumbs step={4} />

<section
    class="container card m-4 mx-auto rounded-xl bg-gradient-to-r from-primary to-primary/70 px-4 text-primary-content"
    in:fade
>
    <div class="card-body grid grid-cols-3 items-center justify-center gap-8">
        <FingerUp class="col-span-3 mx-auto h-64 w-64 md:col-span-1" />
        <div class="col-span-3 flex flex-col items-center justify-center md:col-span-2">
            <div class="card w-full bg-neutral text-neutral-content shadow-xl">
                <div class="card-body justify-center md:flex-row">
                    <span class="text-xl font-normal"
                        >You have added <span class="px-1 text-4xl text-primary">{words}</span> words to this chapter</span
                    >
                </div>
            </div>
            <div class="card">
                <div class="items-center">
                    <h2 class="card-title py-4 text-6xl font-normal italic text-white">You are just one step away</h2>
                    <p class="text-center text-2xl font-normal tracking-tighter text-white">from adding a chapter.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container m-4 mx-auto flex justify-center">
    <a href="/guests/chapters" class="btn btn-secondary rounded-full"> Go back to Guest Chapters </a>
</section>
