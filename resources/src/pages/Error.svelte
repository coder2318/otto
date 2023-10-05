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
    <div class="flex h-screen w-full items-center justify-center bg-base-100 px-16 md:px-0">
        <div class="card min-w-[400px] bg-neutral text-neutral-content shadow-2xl">
            <div class="card-body items-center">
                <p class="font-sans text-6xl font-bold tracking-wider text-primary md:text-7xl lg:text-9xl">
                    {status}
                </p>
                <p class="font-serif text-2xl italic text-primary md:text-3xl lg:text-5xl">
                    {error}
                </p>
            </div>
        </div>
    </div>
</main>
