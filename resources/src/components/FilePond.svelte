<script lang="ts">
    import FilePond, { registerPlugin } from 'svelte-filepond'
    import FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation'
    import FilePondPluginImagePreview from 'filepond-plugin-image-preview'
    import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type'
    import FilePondPluginFileEncode from 'filepond-plugin-file-encode'

    // Register the plugins
    registerPlugin(
        FilePondPluginImageExifOrientation,
        FilePondPluginImagePreview,
        FilePondPluginFileValidateType,
        FilePondPluginFileEncode
    )

    export let pond: FilePond
    export let name = 'filepond'
</script>

<FilePond bind:this={pond} {name} {...$$restProps} />
