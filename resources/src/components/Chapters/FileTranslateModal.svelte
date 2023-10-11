<script lang="ts">
    import { useForm } from '@inertiajs/svelte'
    import languages from '@/data/translate_languages.json'
    import type { FilePondFile } from 'filepond'

    export let modal: HTMLDialogElement
    export let file: FilePondFile
    export function onChange() {}

    const form = useForm({
        source: '',
        target: '',
    })

    function resetFile(file: FilePondFile) {
        $form.defaults({
            source: file?.getMetadata('source') ?? null,
            target: file?.getMetadata('target') ?? null,
        })

        $form.reset()
    }

    function save() {
        file.setMetadata('source', $form.source)
        file.setMetadata('target', $form.target)

        modal.close()
    }

    $: resetFile(file)
</script>

<dialog bind:this={modal} class="modal">
    <div class="modal-box">
        <div class="text-lg font-bold">
            File translation <span class="badge badge-lg">{file?.filename}</span>
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Source language</span>
                <select name="source" class="select select-bordered" bind:value={$form.source}>
                    <option value={null}>Default</option>
                    {#each languages as language}
                        <option value={language.code}>{language.language}</option>
                    {/each}
                </select>
            </label>
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Target language</span>
                <select name="target" class="select select-bordered" bind:value={$form.target}>
                    <option value={null}>Default</option>
                    {#each languages as language}
                        <option value={language.code}>{language.language}</option>
                    {/each}
                </select>
            </label>
        </div>
        {#if $form.isDirty}
            <div class="modal-action">
                <button class="btn btn-primary" on:click|preventDefault={save}> Save </button>
            </div>
        {/if}
    </div>
    <form method="dialog" class="modal-backdrop">
        <button />
    </form>
</dialog>
