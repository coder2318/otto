<script lang="ts" context="module">
    import { registerPlugin } from 'filepond'
    import FilePondPluginFileEncode from 'filepond-plugin-file-encode'
    import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type'
    import FilePondPluginImageCrop from 'filepond-plugin-image-crop'
    import FilePondPluginImageEdit from 'filepond-plugin-image-edit'
    import FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation'
    import FilePondPluginImagePreview from 'filepond-plugin-image-preview'
    import FilePondPluginImageTransform from 'filepond-plugin-image-transform'

    registerPlugin(
        FilePondPluginImageExifOrientation,
        FilePondPluginImagePreview,
        FilePondPluginFileValidateType,
        FilePondPluginFileEncode,
        FilePondPluginImageEdit,
        FilePondPluginImageTransform,
        FilePondPluginImageCrop
    )
</script>

<script lang="ts">
    import { supported, create } from 'filepond'
    import { onMount, afterUpdate, onDestroy } from 'svelte'
    import type { FilePond as FilePondType } from 'filepond'

    let element: HTMLInputElement

    export let instance: FilePondType

    onMount(() => supported() && (instance = create(element, { ...$$props })))

    afterUpdate(() => instance?.setOptions($$props))

    onDestroy(() => instance?.destroy())
</script>

<input type="file" bind:this={element} {...$$restProps} />
