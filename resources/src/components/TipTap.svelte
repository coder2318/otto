<script lang="ts">
    import { Editor } from '@tiptap/core'
    import { onMount } from 'svelte'
    import { strToHtml } from '@/service/helpers'
    import StarterKit from '@tiptap/starter-kit'
    import Focus from '@tiptap/extension-focus'
    import Placeholder from '@tiptap/extension-placeholder'

    let element: HTMLElement

    export let editor: Editor = null
    export let content = ''
    export let autofocus = false

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
            content: strToHtml(content, true),
            onTransaction: () => (editor = editor),
            onUpdate: ({ editor }) => {
                const newText = editor.getText({ blockSeparator: '\n\n' })
                if (newText == content) return
                content = newText
            },
            onCreate: ({ editor }) => {
                if (autofocus) {
                    editor.chain().focus('end').run()
                }
            },
        })

        return () => editor?.destroy()
    })

    function updateContent(content: string) {
        editor?.commands.setContent(strToHtml(content, false), false)
    }

    $: updateContent(content)
</script>

<div class="prose mx-auto w-full max-w-none focus:outline-none">
    <slot name="top" />
    <div class="w-full" bind:this={element} {...$$restProps} />
</div>

<style lang="scss">
    :global(.tiptap p.is-editor-empty:first-child::before) {
        @apply pointer-events-none float-left h-0 text-neutral-content/60;
        content: attr(data-placeholder);
    }
</style>
