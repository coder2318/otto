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
    let isFocused = false

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

        editor.on('focus', () => {
            isFocused = true
        })

        editor.on('blur', () => {
            isFocused = false
        })

        return () => editor?.destroy()
    })

    $: isEditorEmpty = !content.trim()
</script>

<div
    class="prose mx-auto min-h-[450px] max-w-none rounded-3xl border border-[##FFF9ED] bg-[#fff9ed] px-6 pb-6 pt-5 focus:outline-none"
>
    <slot name="top" />
    {#if isEditorEmpty && !isFocused}
        <div class="placeholder" on:click={() => editor.chain().focus('start').run()}>Type Your Story here...</div>
    {/if}
    <div class="w-full" bind:this={element} {...$$restProps} />
</div>

<style>
    .placeholder {
        position: absolute;
        font-family: serif;
        font-size: 28px;
        line-height: 1.3;
        color: #808080;
        font-style: italic;
        cursor: text;
    }
</style>
