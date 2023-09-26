<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    import Paginator from '@/components/Paginator.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { inertia, page, router } from '@inertiajs/svelte'
    import { fade } from 'svelte/transition'

    $: type =
        new URLSearchParams($page.url.split('?')?.[1]).get('type') ?? 'sent'
    $: user = $page.props?.auth?.user
    $: guest = $page.props?.auth?.guest

    export let chapters: {
        data: App.Chapter[]
        links: App.PaginationLinks
        meta: App.PaginationMeta
    }

    const types = {
        sent: 'Sent Requests',
        received: 'Received Requests',
    }

    function resend(chapter: App.Chapter) {
        router.post(`/guests/chapters/${chapter.id}/resend`)
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Guest Chapters</title>
</svelte:head>

<main
    class="container mx-auto flex min-h-[calc(100vh-67px)] flex-col gap-16 p-4 py-8"
>
    <div class="flex flex-col justify-between gap-y-4 md:flex-row">
        <h1 class="text-4xl text-primary">
            Your <i>Guest Chapters</i>
        </h1>

        {#if user && guest}
            <div class="tabs">
                {#each Object.entries(types) as [key, value]}
                    <a
                        class="tab tab-bordered"
                        class:tab-active={type === key}
                        href="?type={key}"
                        use:inertia
                    >
                        {value}
                    </a>
                {/each}
            </div>
        {/if}
    </div>

    <section class="flex flex-1 flex-col gap-4 overflow-x-auto">
        <table class="table w-full rounded-lg bg-neutral text-neutral-content">
            <thead>
                <th>{type === 'sent' || !type ? 'To' : 'From'}</th>
                <th>Email</th>
                <th>Question</th>
                <th>Status</th>
                <th />
            </thead>
            <tbody>
                {#each chapters.data as chapter (chapter.id + type)}
                    <tr in:fade class="hover whitespace-nowrap">
                        <td
                            >{type === 'sent'
                                ? chapter.guest?.name
                                : chapter.user?.details.first_name +
                                  ' ' +
                                  chapter.user?.details.last_name}</td
                        >
                        <td
                            >{type === 'sent'
                                ? chapter.guest?.email
                                : chapter.user?.email}</td
                        >
                        <td>{chapter.title}</td>
                        <td>{chapter.status}</td>
                        <td>
                            {#if type === 'sent'}
                                <a
                                    href="/guests/chapters/{chapter.id}"
                                    use:inertia
                                    class="btn btn-secondary rounded-full"
                                >
                                    Review
                                </a>
                                <button
                                    on:click={() => resend(chapter)}
                                    class="btn btn-primary rounded-full"
                                    >Resend</button
                                >
                            {/if}
                            {#if type === 'received'}
                                <a
                                    href="/guests/chapters/{chapter.id}/edit"
                                    use:inertia
                                    class="btn btn-accent rounded-full"
                                >
                                    Edit
                                </a>
                            {/if}
                        </td>
                    </tr>
                {/each}
            </tbody>
        </table>
    </section>

    <Paginator
        class="flex-wrap items-center justify-center gap-y-2"
        meta={chapters.meta}
    />
</main>
