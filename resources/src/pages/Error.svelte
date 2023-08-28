<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    export const layout = [Base]
</script>

<script lang="ts">
    export let status: number
    export let error: string
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {error}</title>
</svelte:head>

<main class="antialiased">
    <div
        class="items-top relative flex min-h-screen justify-center sm:items-center sm:pt-0"
    >
        <div class="mx-auto max-w-xl sm:px-6 lg:px-8">
            <div class="flex items-center pt-8 sm:justify-start sm:pt-0">
                <div class="border-r-2 border-base-content px-4 text-lg">
                    {status}
                </div>
                <div class="ml-4 text-lg uppercase tracking-wider">
                    {error}
                </div>
            </div>
        </div>
    </div>
</main>
