<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte';
    import Dashboard from '@/components/Layouts/Dashboard.svelte';
    export const layout = [Base, Dashboard];
</script>

<script lang="ts">
    export let story: { data: App.Story };
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {story.data.title}</title>
</svelte:head>

edit
