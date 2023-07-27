<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte';
    import Dashboard from '@/components/Layouts/Dashboard.svelte';
    export const layout = [Base, Dashboard];
</script>

<script lang="ts">

</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - My Stories</title>
</svelte:head>
