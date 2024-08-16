<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { inertia, router } from '@inertiajs/svelte'
    import Breadcrumbs from '@/components/Stories/Breadcrumbs.svelte'
    import { fade, blur } from 'svelte/transition'
    import Paginator from '@/components/Paginator.svelte'
    import BookCoverBuilder from '@/components/Stories/BookCoverBuilder.svelte'
    import { faTrash } from '@fortawesome/free-solid-svg-icons'
    import Fa from 'svelte-fa'
    import { BOOK_COVER_EXCLUDED_FIELDS } from '@/app.constants'
    import { pickBy } from 'lodash'

    export let story: { data: App.Story }
    export let covers: {
        data: App.BookCoverTemplate[]
        links: App.PaginationLinks
        meta: App.PaginationMeta
    }
    export let userCovers: {
        data: App.UserCoverTemplate[]
        links: App.PaginationLinks
        meta: App.PaginationMeta
    }
    export let coverFonts: any

    const coverMeta = story.data?.cover?.meta ?? {}

    const textFields = covers.data.reduce(
        (acc, cover) => [
            ...acc,
            ...(cover.fields || []).filter((field) => field.type === 'text').map((field) => field.key),
        ],
        []
    )

    const shared = pickBy(
        coverMeta,
        (value, key) => textFields.includes(key) && !BOOK_COVER_EXCLUDED_FIELDS.includes(key)
    )

    const deleteUserCover = (coverId: number) => {
        router.delete(`/stories/${story.data.id}/cover/${coverId}`, {
            redirect: 'dashboard.stories.covers',
            preserveScroll: true,
            onSuccess(response) {
                console.info('response', response)
            },
            onError(error) {
                console.error('Error', error)
            },
        })
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {story.data.title}</title>
</svelte:head>

<Breadcrumbs step={1} {story} />

<section class="container mx-auto" in:fade>
    <h1 class="card-title mb-4">Default covers</h1>
    <div class="card m-4 mx-auto grid grid-cols-1 gap-8 rounded-xl px-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
        {#each covers.data as cover (cover.id)}
            <a
                class="card card-bordered border-neutral bg-neutral transition-transform hover:scale-105"
                href="/stories/{story.data.id}/cover/default/{cover.id}"
                use:inertia
                out:blur={{ duration: 250 }}
                in:blur={{ delay: 250, duration: 250 }}
            >
                <figure class="p-2">
                    <BookCoverBuilder {coverFonts} preview={true} template={cover} pages={200} shared={shared ?? {}} />
                </figure>
                <div class="card-body items-center px-1 py-2">
                    <h5 class="card-title">{cover.name}</h5>
                </div>
            </a>
        {/each}
    </div>
</section>
<Paginator class="mx-auto py-4" meta={covers.meta} />

{#if userCovers.data?.length}
    <section class="container mx-auto" in:fade>
        <h1 class="card-title mb-4">User covers</h1>
        <div
            class="card m-4 mx-auto grid grid-cols-1 gap-8 rounded-xl px-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5"
        >
            {#each userCovers.data as cover (cover.id)}
                <div
                    class="card card-bordered border-neutral bg-neutral transition-transform hover:scale-105"
                    out:blur={{ duration: 250 }}
                    in:blur={{ delay: 250, duration: 250 }}
                >
                    <div class="absolute right-4 top-4">
                        <button
                            class="btn-trash btn btn-circle btn-error btn-outline btn-sm"
                            on:click|preventDefault={() => {
                                deleteUserCover(cover.id)
                            }}
                        >
                            <Fa icon={faTrash} />
                        </button>
                    </div>
                    <a href="/stories/{story.data.id}/cover/user/{cover.id}" use:inertia>
                        <figure class="p-2">
                            <BookCoverBuilder
                                {coverFonts}
                                preview={true}
                                template={cover.template}
                                pages={200}
                                shared={cover.parameters ?? {}}
                            />
                        </figure>
                    </a>
                </div>
            {/each}
        </div>
    </section>
    <Paginator class="mx-auto py-4" meta={userCovers.meta} />
{/if}

<style lang="scss">
    .card {
        position: relative;
    }
</style>
