<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Background from '@/components/Layouts/Focus.svelte'
    export const layout = [Base, Background]
</script>

<script lang="ts">
    import { inertia } from '@inertiajs/svelte'
    import { fade } from 'svelte/transition'
    import stripe from '@/assets/img/stripe.svg'
    import { usd } from '@/service/helpers'
    import { start, done } from '@/components/Loading.svelte'
    export let plans: { data: App.Plan[] }

    const options = {
        onStart: start,
        onFinish: done,
        hideProgress: true,
    }

    let checked = false
    $: period = checked ? 'unlimited' : 'month'
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Subscription Plans</title>
</svelte:head>

<div class="z-10 flex flex-1 flex-col items-center gap-8 pt-16 lg:p-16" in:fade>
    <h1 class="text-center text-3xl text-primary md:text-4xl lg:text-6xl">
        Choose a <span class="italic">Otto Story Plan</span>
    </h1>
    <img src={stripe} alt="stripe" class="w-32 md:w-64 lg:w-96" />
    <div class="rounded-box max-w-full flex-1 flex-col lg:carousel lg:flex-row">
        {#each plans.data as plan}
            <div class="carousel-item lg:w-1/3">
                <div class="card m-5 w-full bg-neutral text-neutral-content">
                    <div class="card-body">
                        <h2 class="card-title text-2xl text-primary">
                            {plan.name}
                        </h2>
                        {#each Object.entries(plan.prices) as [key, price] (key)}
                            {#if price.interval === period}
                                <span>
                                    <span class="text-4xl font-bold text-primary"
                                        >{usd(price.value, {
                                            maximumFractionDigits: 0,
                                            currency: price.currency,
                                        })}</span
                                    >
                                    {#if price.interval_count && price.interval}
                                        <span class="text-base-content"
                                            >/{price.interval_count}
                                            {price.interval}</span
                                        >
                                    {/if}
                                </span>
                            {/if}
                        {/each}
                        <ul class="flex-1 list-disc">
                            {#each plan.features as feature}
                                <li>{feature}</li>
                            {/each}
                        </ul>
                        <a
                            href="/plans/{plan.slug}?period={period}"
                            use:inertia={options}
                            class="btn btn-secondary w-full rounded-full">Choose Plan</a
                        >
                    </div>
                </div>
            </div>
        {/each}
    </div>
    <label class="label cursor-pointer gap-2">
        <span class="label-text">Monthly</span>
        <input type="checkbox" class="toggle-success-error" bind:checked />
        <span class="label-text">One Time Payment</span>
    </label>
</div>
