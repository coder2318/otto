<script lang="ts">
    import { Editor } from '@tiptap/core'
    import StarterKit from '@tiptap/starter-kit'
    import Focus from '@tiptap/extension-focus'
    import { onMount } from 'svelte'
    import { strToHtml } from '@/service/helpers'

    let element: HTMLElement
    export let editor: Editor = null
    export let content = ''
    export let autofocus = false

    $: {
        if (editor?.getText({ blockSeparator: '\n\n' }) !== content) {
            editor?.commands.setContent(strToHtml(content), false)
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
            extensions: [StarterKit, Focus],
            content: strToHtml(content),
            onTransaction: () => (editor = editor),
            onUpdate: ({ editor }) => (content = editor.getText({ blockSeparator: '\n\n' })),
            onCreate: ({ editor }) => {
                if (autofocus) {
                    editor.chain().focus('end').run()
                }
            },
        })

        return () => editor?.destroy()
    })
</script>

<div class="prose mx-auto my-4 max-w-none focus:outline-none">
    <slot name="top" />
    <div class="w-full" bind:this={element} {...$$restProps} />
</div>
