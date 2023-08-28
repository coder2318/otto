<script lang="ts">
    import FilePond, { registerPlugin } from 'svelte-filepond'
    import FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation'
    import FilePondPluginImagePreview from 'filepond-plugin-image-preview'
    import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type'
    import FilePondPluginFileEncode from 'filepond-plugin-file-encode'
    import FilePondPluginImageEdit from 'filepond-plugin-image-edit'
    import FilePondPluginImageTransform from 'filepond-plugin-image-transform'
    import FilePondPluginImageCrop from 'filepond-plugin-image-crop'
    import { onMount } from 'svelte'

    // Register the plugins
    registerPlugin(
        FilePondPluginImageExifOrientation,
        FilePondPluginImagePreview,
        FilePondPluginFileValidateType,
        FilePondPluginFileEncode,
        FilePondPluginImageEdit,
        FilePondPluginImageTransform,
        FilePondPluginImageCrop
    )

    export let pond: FilePond = null
    export let name = 'filepond'
</script>

<FilePond bind:this={pond} {name} {...$$restProps} />
