<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Background from '@/components/Layouts/Focus.svelte'
    export const layout = [Base, Background]
</script>

<script lang="ts">
    import { router, useForm } from '@inertiajs/svelte'
    import { onMount } from 'svelte'
    import { loadStripe, type Stripe, type StripeElements } from '@stripe/stripe-js'
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
    let modal: HTMLDialogElement

    const form = useForm({
        coupon: '',
    })

    onMount(() => {
        loadStripe(import.meta.env.VITE_STRIPE_KEY).then((res) => (stripe = res))
        period = new URLSearchParams(window.location.search).get('period') || 'month'
    })

    function submitCoupon() {
        if (processing) return

        modal.close()

        processing = true

        $form.post('/plans/coupon', {
            only: ['plan'],
            onFinish: () => {
                processing = false
            },
        })
    }

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

    $: [price_id, price] = Object.entries(plan.data.prices).find(([, price]) => price.interval === period)
</script>

<svelte:head>
    <title>
        {import.meta.env.VITE_APP_NAME} - Subscribe to {plan.data.name}
    </title>
</svelte:head>

<div class="z-10 flex flex-1 flex-col items-center justify-center gap-8 py-8" in:fade>
    <h1 class="text-4xl text-primary lg:text-6xl">
        Subscribe to <span class="italic">{plan.data.name}</span>
    </h1>
    <div class="card bg-neutral text-neutral-content md:flex-row">
        <div class="card-body md:w-1/2">
            <div class="flex justify-between">
                <div>
                    <h2 class="card-title text-2xl text-primary">{plan.data.name}</h2>
                    <span class="text-4xl font-bold text-primary"
                        >{usd(price.value, {
                            maximumFractionDigits: 0,
                            currency: price.currency,
                        })}</span
                    >
                    {#if price.interval_count && price.interval}
                        <span class="text-base-content">/{price.interval_count} {price.interval}</span>
                    {/if}
                </div>
                {#if plan.data.discount}
                    <div>
                        <h2 class="card-title text-2xl text-error">- Discount</h2>
                        <span class="text-4xl font-bold text-error">- {plan.data.discount}</span>
                        {#if price.interval_count && price.interval}
                            <span class="text-base-content">/{price.interval_count} {price.interval}</span>
                        {/if}
                    </div>
                {/if}
            </div>
            <p />
            <ul class="list-inside list-disc">
                {#each plan.data.features as feature}
                    <li>{feature}</li>
                {/each}
            </ul>
        </div>
        <div class="divider md:divider-horizontal" />
        <div class="card-body justify-center md:w-1/2">
            {#if stripe}
                <Elements
                    {stripe}
                    clientSecret={intent.client_secret}
                    theme="flat"
                    labels="floating"
                    locale="en"
                    bind:elements
                >
                    <form on:submit|preventDefault={submit} class="flex h-full flex-col items-stretch">
                        <div class="flex-1">
                            <PaymentElement options={{ layout: 'auto' }} />
                        </div>

                        <div class="card-actions mt-4">
                            {#if error}
                                <div class="alert alert-error mb-2">
                                    {error.message}
                                </div>
                            {/if}
                            <div class="grid w-full grid-cols-2 gap-4">
                                <button
                                    class="btn btn-primary"
                                    type="button"
                                    disabled={processing}
                                    on:click|preventDefault={() => modal.showModal()}
                                >
                                    Use Coupon
                                </button>
                                <button type="submit" class="btn btn-secondary" disabled={processing}>
                                    {#if processing}<span class="loading loading-dots loading-md" />{/if} Purchase
                                </button>
                            </div>
                        </div>
                    </form>
                </Elements>
            {:else}
                <span class="loading loading-spinner loading-lg" />
            {/if}
        </div>
    </div>
</div>

<dialog bind:this={modal} class="modal">
    <div class="modal-box">
        <h3 class="text-lg font-normal italic">Use Coupon Code</h3>
        <div class="form-control mt-4">
            <input type="text" class="input input-bordered w-full" bind:value={$form.coupon} />
        </div>
        <div class="modal-action">
            <button type="button" class="btn btn-primary" on:click|preventDefault={submitCoupon}>Confirm</button>
            <form method="dialog">
                <button type="submit" class="btn">Close</button>
            </form>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop"><button /></form>
</dialog>
