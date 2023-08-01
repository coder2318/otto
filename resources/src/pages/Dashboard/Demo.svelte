<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Focus from '@/components/Layouts/Focus.svelte'
    export const layout = [Base, Focus]
</script>

<script lang="ts">
    import { inertia } from '@inertiajs/svelte'
    import { fade } from 'svelte/transition'
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Start your story</title>
</svelte:head>

<div class="z-10 flex flex-col flex-1 items-center gap-8" in:fade>
    <h1 class="text-4xl md:text-5xl text-primary text-center">
        Let's answer <span class="italic">one of the three questions</span>
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-3 flex-1 gap-4">
        <div class="card bg-neutral shadow">
            <div class="card-body">
                <figure class="rounded-xl">
                    <img src="https://picsum.photos/640/480" alt="Shoes" />
                </figure>
                <p class="text-xl font-serif">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                </p>
                <div class="card-actions">
                    <button
                        class="btn btn-primary btn-lg btn-outline rounded-full"
                    >
                        Record Your Answer
                    </button>
                </div>
            </div>
        </div>
        <div class="card bg-neutral shadow">
            <div class="card-body gap-4">
                <figure class="rounded-xl">
                    <img src="https://picsum.photos/640/480" alt="Shoes" />
                </figure>
                <p class="text-xl font-serif">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                </p>
                <div class="card-actions">
                    <button
                        class="btn btn-primary btn-lg btn-outline rounded-full"
                    >
                        Record Your Answer
                    </button>
                </div>
            </div>
        </div>
        <div class="card bg-neutral shadow">
            <div class="card-body">
                <figure class="rounded-xl">
                    <img src="https://picsum.photos/640/480" alt="Shoes" />
                </figure>
                <p class="text-xl font-serif">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                </p>
                <div class="card-actions">
                    <button
                        class="btn btn-primary btn-lg btn-outline rounded-full"
                    >
                        Record Your Answer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <a class="btn btn-success btn-lg" href="/plans" use:inertia>
        Get premium access
    </a>
</div>
