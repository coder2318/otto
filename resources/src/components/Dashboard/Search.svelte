<script lang="ts">
    import { useForm, page, inertia } from '@inertiajs/svelte'
    import Fa from 'svelte-fa'
    import { faClose, faSearch } from '@fortawesome/free-solid-svg-icons'

    let search = $page.props?.search as {
        chapters: { data: App.Chapter[] }
        stories: { data: App.Story[] }
    } | null

    const form = useForm({
        search: '',
    })

    function highlight(text: string, chars: number = null): string {
        const searchWords = $form.search
            .trim()
            .split(/("[^"]*")|\s+/)
            .filter(Boolean)
            .map((word) => word?.replace(/^"(.*)"$/, '$1'))
        const regexArray = searchWords.map((word) => new RegExp(`(?![^<]*>)${word}`, 'gi'))
        regexArray.forEach((regex) => (text = text?.replace(regex, (match) => `<mark>${match}</mark>`)))
        text = text?.replace(/<\/mark>?(\s+)<mark>/g, ' ')

        if (!chars) return text

        const firstMarkIndex = text.indexOf('<mark')
        const lastMarkIndex = text.lastIndexOf('</mark>')

        if (firstMarkIndex !== -1 && lastMarkIndex !== -1) {
            let start = Math.max(firstMarkIndex - chars / 2, 0)
            let end = Math.min(lastMarkIndex + 7 + chars / 2, text.length)
            text = (start === 0 ? '' : '...') + text.substring(start, end) + (end === text.length ? '' : '...')
        } else {
            text = text.substring(0, chars) + '...'
        }
        return text.length > chars ? `${text.substring(0, chars)}...` : text
    }

    function input() {
        if ($form.processing) return

        const current = $form.search

        setTimeout(() => {
            if ($form.search !== current || $form.processing) return

            $form.post('/search', {
                only: ['search'],
                onSuccess: () => {
                    search = $page.props?.search
                },
            })
        }, 300)
    }
</script>

<div class="dropdown form-control relative hidden gap-4 md:block">
    <span class="absolute left-2 top-1/2 -translate-y-1/2">
        {#if $form.isDirty}
            <button
                type="button"
                class="btn btn-circle btn-ghost btn-sm"
                on:click|preventDefault={() => ($form.search = '')}
            >
                <Fa icon={faClose} />
            </button>
        {:else}
            <Fa icon={faSearch} class="ml-2" />
        {/if}
    </span>
    <input
        tabindex="0"
        bind:value={$form.search}
        on:input={input}
        type="text"
        placeholder="Search"
        class="search input input-ghost rounded-full border-neutral pl-10"
    />
    {#if search?.stories?.data?.length || search?.chapters?.data?.length}
        <ul
            class="menu dropdown-content rounded-box mt-2 flex max-h-[400px] min-h-fit w-96 flex-nowrap overflow-scroll bg-base-200 p-0 text-base-content drop-shadow"
        >
            {#if search?.stories?.data?.length}
                <li class="menu-title bg-neutral text-neutral-content first:rounded-t-xl">Stories</li>

                {#each search.stories.data as story}
                    <li>
                        {#if story.title}
                            <a href="/stories/{story.id}" class="block" use:inertia>{@html highlight(story.title)}</a>
                        {/if}
                    </li>
                {/each}
            {/if}

            {#if search?.chapters?.data?.length}
                <li class="menu-title bg-neutral text-neutral-content first:rounded-t-xl">Chapters</li>

                {#each search.chapters.data as chapter}
                    <li>
                        <a href="/chapters/{chapter.id}/write" class="block" use:inertia>
                            {#if chapter.title}
                                <div>{@html highlight(chapter.title)}</div>
                            {/if}
                            {#if chapter.content}
                                <div class="text-base-content/60">{@html highlight(chapter.content, 200)}</div>
                            {/if}
                        </a>
                    </li>
                {/each}
            {/if}
        </ul>
    {/if}
</div>
