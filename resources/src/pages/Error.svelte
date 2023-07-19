<script lang="ts">
    export let status: number;
    export let error: string;
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {error}</title>
</svelte:head>

<main class="antialiased">
    <div class="relative flex items-top justify-center min-h-screen sm:items-center sm:pt-0">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center pt-8 sm:justify-start sm:pt-0">
                <div class="px-4 text-lg border-r-2 border-base-content">
                    {status}
                </div>
                <div class="ml-4 text-lg uppercase tracking-wider">
                    {error}
                </div>
            </div>
        </div>
    </div>
</main>
