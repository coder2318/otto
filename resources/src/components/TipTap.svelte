<script lang="ts">
    import { Editor } from '@tiptap/core'
    import { onMount, afterUpdate, onDestroy } from 'svelte'
    import { strToHtml } from '@/service/helpers'
    import StarterKit from '@tiptap/starter-kit'
    import Focus from '@tiptap/extension-focus'
    import Placeholder from '@tiptap/extension-placeholder'

    let element: HTMLElement
    let wrapper: HTMLElement
    let toolbar: HTMLElement
    let actualParent: HTMLElement = document
    let toolbarHeight = 0

    const NAVBAR_HEIGHT = 99
    const TOOLBAR_PADDING_TOP = 16

    export let editor: Editor = null
    export let content = ''
    export let autofocus = false

    const handleScroll = () => {
        const toolbarTop = wrapper.getBoundingClientRect().top

        const stickyEdge = NAVBAR_HEIGHT + TOOLBAR_PADDING_TOP

        if (toolbarTop <= stickyEdge) {
            toolbar.style.position = 'fixed'
            toolbar.style.top = `${stickyEdge}px`
            toolbar.style.width = `${wrapper.offsetWidth}px`
        } else {
            toolbar.style.position = 'static'
        }
    }

    onMount(() => {
        editor = new Editor({
            editorProps: {
                attributes: {
                    class: 'focus:outline-none',
                },
            },
            element: element,
            extensions: [
                StarterKit,
                Placeholder.configure({
                    placeholder: element.getAttribute('placeholder') ?? 'Write something...',
                }),
                // @ts-ignore
                Focus,
            ],
            content: content,
            onTransaction: () => (editor = editor),
            onUpdate: ({ editor }) => {
                const newText = editor.getHTML()
                if (newText == content) return
                content = newText
            },
            onCreate: ({ editor }) => {
                if (autofocus) {
                    editor.chain().focus('end').run()
                }
            },
        })

        if (actualParent) {
            actualParent.addEventListener('scroll', handleScroll)
        }

        return () => editor?.destroy()
    })

    onDestroy(() => {
        if (actualParent) {
            actualParent.removeEventListener('scroll', handleScroll)
        }
    })
</script>

<div bind:this={wrapper} class="prose mx-auto w-full max-w-none focus:outline-none">
    <slot name="top" />
    {#if editor}
        <div bind:this={toolbar} class="z-10 bg-transparent">
            <button
                on:click={() => editor.chain().focus().toggleBold().run()}
                disabled={!editor.can().chain().focus().toggleBold().run()}
                class={editor.isActive('bold') ? 'is-active' : ''}
            >
                bold
            </button>
            <button
                on:click={() => editor.chain().focus().toggleItalic().run()}
                disabled={!editor.can().chain().focus().toggleItalic().run()}
                class={editor.isActive('italic') ? 'is-active' : ''}
            >
                italic
            </button>
            <button
                on:click={() => editor.chain().focus().toggleHeading({ level: 1 }).run()}
                class={editor.isActive('heading', { level: 1 }) ? 'is-active' : ''}
            >
                h1
            </button>
            <button
                on:click={() => editor.chain().focus().toggleHeading({ level: 2 }).run()}
                class={editor.isActive('heading', { level: 2 }) ? 'is-active' : ''}
            >
                h2
            </button>
            <button
                on:click={() => editor.chain().focus().toggleHeading({ level: 3 }).run()}
                class={editor.isActive('heading', { level: 3 }) ? 'is-active' : ''}
            >
                h3
            </button>
            <button
                on:click={() => editor.chain().focus().toggleHeading({ level: 4 }).run()}
                class={editor.isActive('heading', { level: 4 }) ? 'is-active' : ''}
            >
                h4
            </button>
            <button
                on:click={() => editor.chain().focus().toggleHeading({ level: 5 }).run()}
                class={editor.isActive('heading', { level: 5 }) ? 'is-active' : ''}
            >
                h5
            </button>
            <button
                on:click={() => editor.chain().focus().toggleHeading({ level: 6 }).run()}
                class={editor.isActive('heading', { level: 6 }) ? 'is-active' : ''}
            >
                h6
            </button>
            <button
                on:click={() => editor.chain().focus().toggleBulletList().run()}
                class={editor.isActive('bulletList') ? 'is-active' : ''}
            >
                bullet list
            </button>
            <button
                on:click={() => editor.chain().focus().toggleOrderedList().run()}
                class={editor.isActive('orderedList') ? 'is-active' : ''}
            >
                ordered list
            </button>
        </div>
    {/if}
    <div class="w-full" bind:this={element} {...$$restProps} />
</div>

<style lang="scss">
    :global(.tiptap p.is-editor-empty:first-child::before) {
        @apply pointer-events-none float-left h-0 text-neutral-content/60;
        content: attr(data-placeholder);
    }

    button {
        font-size: inherit;
        font-family: inherit;
        color: #000;
        margin: 0.1rem;
        border: 1px solid black;
        border-radius: 0.3rem;
        padding: 0.1rem 0.4rem;
        background: white;
        accent-color: black;
    }

    button.is-active {
        background-color: black;
        color: white;
    }
</style>
