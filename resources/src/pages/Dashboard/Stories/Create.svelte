<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { fade } from 'svelte/transition'
    import { useForm } from '@inertiajs/svelte'
    const form = useForm<Partial<App.Story>>({
        title: '',
        cover: '',
        status: '',
        timeline_id: '',
    })
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Create New Story</title>
</svelte:head>

<main class="container mx-auto flex flex-1 flex-col gap-8 p-4 py-8" in:fade>
    <div class="flex justify-between">
        <h1 class="text-3xl font-bold italic text-primary">Create New Story</h1>
    </div>
</main>
