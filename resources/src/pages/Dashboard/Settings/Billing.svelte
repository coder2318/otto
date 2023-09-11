<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    import Settings from '@/components/Layouts/Settings.svelte'
    export const layout = [Base, Dashboard, Settings]
</script>

<script lang="ts">
    import { router } from '@inertiajs/svelte'
    import { usd } from '@/service/helpers'
    import { faDownload, faX } from '@fortawesome/free-solid-svg-icons'
    import { dayjs } from '@/service/dayjs'
    import Fa from 'svelte-fa'
    import { fade } from 'svelte/transition'
    export let current: any | null,
        plans: { data: App.Plan[] },
        upcoming: any | null,
        invoices: any

    let plansModal: HTMLDialogElement, confirmCancelModal: HTMLDialogElement
    let period = 'month'

    $: currentPlan = plans.data.find(
        (plan) => plan.prices?.[current?.stripe_price ?? -1]
    )
    $: currentPrice = currentPlan?.prices?.[current?.stripe_price ?? -1]

    function cancelSubscription() {
        confirmCancelModal.close()
        router.post(window.location.pathname, null, {
            only: ['current', 'flash'],
        })
    }

    function changeSubscription(plan: App.Plan) {
        plansModal.close()
        const price = Object.entries(plan.prices).find(
            ([, price]) => price.interval === period
        )[0]
        router.put(window.location.pathname, { price })
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Billing Settings</title>
</svelte:head>

<main class="card mb-8 rounded-xl border border-base-300" in:fade>
    <div class="card-body gap-4">
        {#if current}
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

                <button
                    class="btn btn-primary btn-sm mt-4"
                    on:click={() => plansModal.show()}
                >
                    Change Plan
                </button>
            </div>

            <div class="divider" />
        {/if}
        {#if upcoming}
            <span class="card-title text-2xl text-primary lg:text-3xl">
                Payments
            </span>

            <div class="rounded-lg bg-base-200 p-4">
                <div class="text-sm text-base-content/80">
                    Next Payment on {dayjs
                        .unix(upcoming.period_end)
                        .format('LL')}
                </div>
                <div class="mt-4 text-4xl font-bold text-primary">
                    {usd(upcoming.amount_due / 100)}
                </div>
            </div>
            <div class="divider" />
        {/if}
        {#if current?.ends_at}
            <span class="card-title text-2xl text-primary lg:text-3xl">
                Cancel Subscription
            </span>

            <div class="rounded-lg bg-base-200 p-4">
                <p>
                    You may cancel your subscription at any time. Once your
                    subscription has been cancelled, you will have the option to
                    resume the subscription until the end of your current
                    billing cycle.
                </p>
                <button
                    class="btn btn-primary btn-outline btn-sm mt-4"
                    on:click={() => confirmCancelModal.show()}
                >
                    Cancel Subscription
                </button>
            </div>

            <div class="divider" />
        {:else}
            <span class="card-title text-2xl text-primary lg:text-3xl">
                Resume Subscription
            </span>

            <div class="rounded-lg bg-base-200 p-4">
                <p>
                    Having second thoughts about cancelling your subscription?
                    You can instantly reactive your subscription at any time
                    until the end of your current billing cycle
                    <i>({dayjs(current.ends_at).format('ll')})</i>. After your
                    current billing cycle ends, you may choose an entirely new
                    subscription plan.
                </p>
                <button
                    class="btn btn-primary btn-outline btn-sm mt-4"
                    on:click={() => confirmCancelModal.show()}
                >
                    Resume
                </button>
            </div>

            <div class="divider" />
        {/if}

        <span class="card-title text-2xl text-primary lg:text-3xl">
            Receipts
        </span>

        <div class="rounded-lg bg-base-200">
            <div class="overflow-x-auto">
                <table class="table">
                    <tbody>
                        {#each invoices as invoice}
                            <tr class="hover hover:!bg-base-300">
                                <td
                                    >{dayjs
                                        .unix(invoice.created)
                                        .format('ll')}</td
                                >
                                <td>{usd(invoice.total / 100)}</td>
                                <td
                                    ><span class="badge badge-sm"
                                        >{invoice.status}</span
                                    ></td
                                >
                                <a
                                    href="/user/invoice/{invoice.id}"
                                    class="btn btn-square btn-ghost"
                                >
                                    <Fa icon={faDownload} />
                                </a>
                            </tr>
                        {/each}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<dialog bind:this={confirmCancelModal} class="modal">
    <div class="modal-box w-11/12 max-w-5xl">
        <div class="text-lg font-bold">Confirm Billing Action</div>
        <p class="py-4">
            Are you sure you want to {current.ends_at ? 'resume' : 'cancel'} your
            subscription?
        </p>
        <div class="modal-action">
            <button
                type="button"
                class="btn btn-outline btn-sm"
                on:click|preventDefault={() => confirmCancelModal.close()}
            >
                Nevermind
            </button>
            <button
                type="button"
                class="btn btn-primary btn-sm"
                on:click|preventDefault={() => cancelSubscription()}
            >
                Confirm
            </button>
        </div>
    </div>
    <div class="modal-backdrop bg-base-content/20">
        <button
            type="button"
            on:click|preventDefault={() => confirmCancelModal.close()}
        />
    </div>
</dialog>

<dialog bind:this={plansModal} class="modal">
    <div class="modal-box w-11/12 max-w-5xl">
        <div class="modal-action mt-0">
            <label class="label cursor-pointer gap-2">
                <span class="label-text">Monthly</span>
                <input
                    type="checkbox"
                    class="toggle-success-error"
                    on:change={(e) =>
                        (period = e.currentTarget.checked ? 'year' : 'month')}
                />
                <span class="label-text">Yearly</span>
            </label>
            <button
                type="button"
                class="btn btn-square btn-ghost"
                on:click={() => plansModal.close()}><Fa icon={faX} /></button
            >
        </div>
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
                                type="button"
                                disabled={plan.prices?.[
                                    current?.stripe_price ?? -1
                                ]?.interval === period}
                                on:click|preventDefault={() =>
                                    changeSubscription(plan)}
                            >
                                {#if plan.prices?.[current?.stripe_price ?? -1]?.interval === period}
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
