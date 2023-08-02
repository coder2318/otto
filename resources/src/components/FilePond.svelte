<script lang="ts">
    import FilePond, { registerPlugin } from 'svelte-filepond'
    import FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation'
    import FilePondPluginImagePreview from 'filepond-plugin-image-preview'

    // Register the plugins
    registerPlugin(
        FilePondPluginImageExifOrientation,
        FilePondPluginImagePreview
    )

    let pond: FilePond
    export let name = 'filepond'
</script>

<FilePond bind:this={pond} {name} {...$$restProps} />
