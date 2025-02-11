<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { fade } from 'svelte/transition'
    import { page } from '@inertiajs/svelte'
    import Breadcrumbs from '@/components/Demo/Breadcrumbs.svelte'
    import Stamp from '@/components/SVG/stamp.svg.svelte'
    import { dayjs } from '@/service/dayjs'

    export let chapter: { data: App.Chapter }

    let pages = Math.ceil((chapter.data.content?.split(' ').filter((v) => v).length ?? 0) / 500)
    let days = dayjs(chapter.data.updated_at).diff(dayjs(chapter.data.created_at), 'days') + 1
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
        <Stamp class="col-span-3 mx-auto h-64 w-64 md:col-span-1" />
        <div class="col-span-3 flex flex-col items-center justify-center md:col-span-2">
            <div class="card bg-neutral text-neutral-content shadow-xl">
                <div class="card-body md:flex-row">
                    <span class="text-xl"
                        >Completed <span class="px-1 text-4xl text-primary">{pages}</span> page(s)</span
                    >
                    <div class="divider md:divider-horizontal" />
                    <span class="text-xl"
                        >Completed in <span class="px-1 text-4xl text-primary">{days}</span> day(s)</span
                    >
                </div>
            </div>
            {#if $page.props?.auth?.user?.data}
                <div class="card">
                    <div class="card-body items-center">
                        <h2 class="card-title text-4xl font-normal italic">
                            {$page.props?.auth?.user?.data.details.first_name ?? 'User'}
                            {$page.props?.auth?.user?.data.details.last_name ?? ''}
                        </h2>
                        <p>
                            has just finished his autobiography chapter on: {chapter.data.title}
                        </p>
                    </div>
                </div>
            {/if}
            <a href="/plans" class="btn btn-success btn-lg rounded-full font-serif font-normal italic"
                >Get Premium Access</a
            >
        </div>
    </div>
</section>

<section class="container card mx-auto bg-neutral text-neutral-content">
    <div class="card-body items-center justify-center">
        <iframe
            title={chapter.data.title}
            src="/pdf/web/viewer.html?file=/demo/book"
            width="100%"
            class="min-h-[500px] flex-1 bg-base-300"
        />
    </div>
</section>
