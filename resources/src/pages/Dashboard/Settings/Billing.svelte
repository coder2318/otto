<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    import Settings from '@/components/Layouts/Settings.svelte'
    import { usd } from '@/service/helpers'
    export const layout = [Base, Dashboard, Settings]
</script>

<script lang="ts">
    import { fade } from 'svelte/transition'
    export let current: any, plans: { data: App.Plan[] }, period_end: string

    let plansModal: HTMLDialogElement
    let period = 'month'

    $: currentPlan = plans.data.find(
        (plan) => plan.prices[current.stripe_price]
    )
    $: currentPrice = currentPlan.prices[current.stripe_price]
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Billing Settings</title>
</svelte:head>

<main class="card rounded-xl border border-base-300" in:fade>
    <div class="card-body gap-4">
        <span class="card-title text-2xl text-primary lg:text-3xl">
            Current Subscription Plan
        </span>

        <div class="rounded-lg bg-base-200 p-4">
            <div class="card-title text-xl text-primary">
                {currentPlan.name}
            </div>

            <div class="card-title text-lg text-primary">
                {usd(currentPrice.value, {
                    maximumFractionDigits: 0,
                })} / {currentPrice.interval_count}
                {currentPrice.interval}
            </div>

            <p>{@html currentPlan.description}</p>

            <ul>
                {#each currentPlan.features as feature}
                    <li>{feature}</li>
                {/each}
            </ul>

            <div class="form-control mt-4">
                <button
                    class="btn btn-primary btn-sm"
                    on:click={() => plansModal.show()}
                >
                    Change Plan
                </button>
            </div>
        </div>
    </div>
</main>

<dialog bind:this={plansModal} class="modal">
    <div class="modal-box w-11/12 max-w-5xl">
        <div class="carousel rounded-box w-full flex-1">
            {#each plans.data as plan}
                <div class="carousel-item w-1/3">
                    <div class="card m-5 bg-neutral text-neutral-content">
                        <div class="card-body">
                            <h2 class="card-title text-2xl text-primary">
                                {plan.name}
                            </h2>
                            <div class="flex">
                                {#each Object.entries(plan.prices) as [key, price] (key)}
                                    {#if price.interval === period}
                                        <span>
                                            <span
                                                class="text-4xl font-bold text-primary"
                                                >{usd(price.value, {
                                                    maximumFractionDigits: 0,
                                                    currency: price.currency,
                                                })}</span
                                            >
                                            <span class="text-base-content"
                                                >/{price.interval_count}
                                                {price.interval}</span
                                            >
                                        </span>
                                    {/if}
                                {/each}
                            </div>
                            <p class="prose max-w-none">
                                {@html plan.description}
                            </p>
                            <button
                                class="btn btn-secondary btn-xs w-full rounded-full"
                                disabled={currentPlan.id === plan.id}
                            >
                                {#if currentPlan.id === plan.id}
                                    Current Plan
                                {:else}
                                    Change Plan
                                {/if}
                            </button>
                        </div>
                    </div>
                </div>
            {/each}
        </div>
    </div>
    <div class="modal-backdrop bg-base-content/20">
        <button
            type="button"
            on:click|preventDefault={() => plansModal.close()}
        />
    </div>
</dialog>
