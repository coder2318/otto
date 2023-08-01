<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Background from '@/components/Layouts/Focus.svelte'
    export const layout = [Base, Background]
</script>

<script lang="ts">
    import { router } from '@inertiajs/svelte'
    import { onMount } from 'svelte'
    import {
        loadStripe,
        type Stripe,
        type StripeElements,
    } from '@stripe/stripe-js'
    import { fade } from 'svelte/transition'
    import { usd } from '@/service/helpers'
    import { Elements, PaymentElement } from 'svelte-stripe'

    export let plan: { data: App.Plan }
    export let intent

    let period = 'month'
    let error = null
    let processing = false
    let elements: StripeElements
    let stripe: Stripe | null = null

    onMount(async () => {
        stripe = await loadStripe(import.meta.env.VITE_STRIPE_KEY)
        period =
            new URLSearchParams(window.location.search).get('period') || 'month'
    })

    async function submit() {
        if (processing) return
        processing = true

        const result = await stripe.confirmSetup({
            elements,
            redirect: 'if_required',
        })

        if (result.error) {
            processing = false
            return (error = result.error)
        }

        return router.put(window.location.href, {
            payment_method: result.setupIntent.payment_method,
            price_id,
        })
    }

    $: [price_id, price] = Object.entries(plan.data.prices).find(
        ([, price]) => price.interval === period
    )
</script>

<svelte:head>
    <title>
        {import.meta.env.VITE_APP_NAME} - Subscribe to {plan.data.name}
    </title>
</svelte:head>

<div
    class="z-10 flex flex-col flex-1 items-center justify-center gap-8"
    in:fade
>
    <h1 class="text-2xl md:text-4xl lg:text-6xl text-primary">
        Subscribe to <span class="italic">{plan.data.name}</span>
    </h1>
    <div class="card bg-neutral text-neutral-content md:flex-row">
        <div class="card-body md:w-1/2">
            <h2 class="card-title text-primary text-2xl">{plan.data.name}</h2>
            <p>
                <span class="font-bold text-4xl text-primary"
                    >{usd(price.value, {
                        maximumFractionDigits: 0,
                        currency: price.currency,
                    })}</span
                >
                <span class="text-base-content"
                    >/{price.interval_count} {price.interval}</span
                >
            </p>
            <p class="prose max-w-none">{@html plan.data.description}</p>
        </div>
        <div class="divider md:divider-horizontal" />
        <div class="card-body md:w-1/2 justify-center">
            {#if stripe}
                <Elements
                    {stripe}
                    clientSecret={intent.client_secret}
                    theme="flat"
                    labels="floating"
                    locale="en"
                    bind:elements
                >
                    <form
                        on:submit|preventDefault={submit}
                        class="flex h-full flex-col items-stretch"
                    >
                        <div class="flex-1">
                            <PaymentElement />
                        </div>

                        <div class="card-actions mt-4">
                            {#if error}
                                <div class="alert alert-error mb-2">
                                    {error.message}
                                </div>
                            {/if}
                            <button
                                type="submit"
                                class="btn btn-secondary rounded-full w-full"
                                disabled={processing}
                            >
                                {#if processing}<span
                                        class="loading loading-dots loading-md"
                                    />{/if} Parchase
                            </button>
                        </div>
                    </form>
                </Elements>
            {:else}
                <span class="loading loading-spinner loading-lg" />
            {/if}
        </div>
    </div>
</div>
