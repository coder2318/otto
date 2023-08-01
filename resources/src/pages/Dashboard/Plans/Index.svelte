<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte';
    import Background from '@/components/Layouts/Focus.svelte';
    export const layout = [Base, Background];
</script>

<script lang="ts">
    import { inertia } from '@inertiajs/svelte';
    import { fade } from 'svelte/transition';
    import stripe from '@/assets/img/stripe.svg';
    import { usd } from '@/service/helpers';
    import { start, done } from '@/components/Loading.svelte';
    export let plans : Array<App.Plan> = [];

    const options = {
        onStart: start,
        onFinish: done,
        hideProgress: true,
    }

    let checked = false;
    $: period = checked ? 'year' : 'month';
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Subscription Plans</title>
</svelte:head>

<div class="flex flex-col h-full w-full items-center justify-center gap-10 py-16 z-[1]" in:fade>
    <h1 class="text-6xl text-primary">Chose a <span class="italic">Otto Story Plan</span></h1>
    <img src={stripe} alt="stripe" class="w-[200px]" />
    <div class="carousel rounded-box max-w-full flex-1">
        {#each plans as plan}
        <div class="carousel-item w-1/3">
            <div class="card bg-neutral text-neutral-content m-5">
                <div class="card-body">
                    <h2 class="card-title text-primary text-2xl">{plan.name}</h2>
                    <div class="flex">
                        {#each Object.entries(plan.prices) as [,price]}
                            {#if price.interval === period}
                                <span>
                                    <span class="font-bold text-4xl text-primary">{usd(price.value, {maximumFractionDigits: 0, currency: price.currency})}</span>
                                    <span class="text-base-content">/{price.interval_count} {price.interval}</span>
                                </span>
                            {/if}
                        {/each}
                    </div>
                    <p class="prose max-w-none">{@html plan.description}</p>
                    <a href="/plans/{plan.slug}?period={period}" use:inertia={options} class="btn btn-secondary rounded-full w-full">Chose Plan</a>
                </div>
            </div>
        </div>
        {/each}
    </div>
    <label class="cursor-pointer label gap-2">
        <span class="label-text">Monthly</span>
        <input type="checkbox" class="toggle-custom" bind:checked/>
        <span class="label-text">Yearly</span>
    </label>
</div>

<style lang="scss">
    .toggle-custom {
        @apply toggle border-success bg-success toggle-error;
    }
</style>
