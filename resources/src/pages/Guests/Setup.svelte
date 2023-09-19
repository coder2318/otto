<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { page } from '@inertiajs/svelte'

    $: console.log($page?.props)
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Guest Reply</title>
</svelte:head>

User Setup
