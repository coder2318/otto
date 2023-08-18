<script lang="ts">
    import { Editor } from '@tiptap/core'
    import StarterKit from '@tiptap/starter-kit'
    import { onMount } from 'svelte'

    let element: HTMLElement
    export let editor: Editor = null
    export let content = ''

    onMount(() => {
        editor = new Editor({
            editorProps: {
                attributes: {
                    class: 'focus:outline-none',
                },
            },
            element: element,
            extensions: [StarterKit],
            content: content || '',
            onTransaction: () => (editor = editor),
            onUpdate: ({ editor }) => (content = editor.getHTML()),
        })

        return () => editor?.destroy()
    })
</script>

<div class="prose mx-auto my-4 max-w-none focus:outline-none">
    <slot name="top" />
    <div class="w-full" bind:this={element} {...$$restProps} />
</div>
