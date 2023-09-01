<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { useForm, page, router } from '@inertiajs/svelte'
    import Breadcrumbs from '@/components/Stories/Breadcrumbs.svelte'
    import { usd } from '@/service/helpers'
    import { dayjs } from '@/service/dayjs'

    export let price: number | null = null
    export let story: { data: App.Story }
    export let countries = []
    export let states = []

    const form = useForm({
        first_name: '',
        last_name: '',
        phone: '',
        email: '',
        address: '',
        city: '',
        country_code:
            new URLSearchParams(window.location.search).get('country_code') ??
            '',
        state_code: '',
        postal_code: '',
    })

    function submit(event: SubmitEvent) {
        $form.submit(event.submitter.dataset.method, window.location.pathname, {
            only: ['price', 'flash', 'errors'],
        })
    }

    function getStates() {
        if (!$form.country_code) {
            return
        }

        router.get(
            window.location.pathname,
            {
                country_code: $form.country_code,
            },
            {
                only: ['states'],
                onBefore: () => {
                    $form.state_code = ''
                    states = []
                },
            }
        )
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {story.data.title}</title>
</svelte:head>

<form on:submit|preventDefault={submit} class="container mx-auto px-4">
    <Breadcrumbs step={3} {story} />
    <h1 class="my-4 mt-16 text-3xl text-primary">Order Details</h1>

    <main class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
        <div class="card bg-neutral text-neutral-content lg:col-span-2">
            <div class="card-body">
                <h2 class="card-title">Shipping Details</h2>
                <hr />
                <div class="grid grid-cols-1 gap-x-8 sm:grid-cols-2">
                    <div class="form-control">
                        <label class="label" for="first_name">
                            <span class="label-text">First Name</span>
                        </label>
                        <input
                            bind:value={$form.first_name}
                            type="text"
                            name="first_name"
                            id="first_name"
                            placeholder="First Name"
                            class="input input-bordered input-ghost"
                            disabled={!!price}
                        />
                        {#if $form.errors.first_name}
                            <span
                                class="label-text-alt mt-1 text-left text-error"
                            >
                                {$form.errors.first_name}
                            </span>
                        {/if}
                    </div>
                    <div class="form-control">
                        <label class="label" for="last_name">
                            <span class="label-text">Last Name</span>
                        </label>
                        <input
                            bind:value={$form.last_name}
                            type="text"
                            name="last_name"
                            id="last_name"
                            placeholder="Last Name"
                            class="input input-bordered input-ghost"
                            disabled={!!price}
                        />
                        {#if $form.errors.last_name}
                            <span
                                class="label-text-alt mt-1 text-left text-error"
                            >
                                {$form.errors.last_name}
                            </span>
                        {/if}
                    </div>
                    <div class="form-control">
                        <label class="label" for="phone">
                            <span class="label-text">Phone Number</span>
                        </label>
                        <input
                            bind:value={$form.phone}
                            type="tel"
                            name="phone"
                            id="phone"
                            placeholder="Phone Number"
                            class="input input-bordered input-ghost"
                            disabled={!!price}
                        />
                        {#if $form.errors.phone}
                            <span
                                class="label-text-alt mt-1 text-left text-error"
                            >
                                {$form.errors.phone}
                            </span>
                        {/if}
                    </div>
                    <div class="form-control">
                        <label class="label" for="email">
                            <span class="label-text">Email Address</span>
                        </label>
                        <input
                            bind:value={$form.email}
                            type="email"
                            name="email"
                            id="email"
                            placeholder="Email Address"
                            class="input input-bordered input-ghost"
                            disabled={!!price}
                        />
                        {#if $form.errors.email}
                            <span
                                class="label-text-alt mt-1 text-left text-error"
                            >
                                {$form.errors.email}
                            </span>
                        {/if}
                    </div>
                    <div class="form-control sm:col-span-2">
                        <label class="label" for="address">
                            <span class="label-text">Address</span>
                        </label>
                        <input
                            bind:value={$form.address}
                            type="text"
                            name="address"
                            id="address"
                            placeholder="Address"
                            class="input input-bordered input-ghost"
                            disabled={!!price}
                        />
                        {#if $form.errors.address}
                            <span
                                class="label-text-alt mt-1 text-left text-error"
                            >
                                {$form.errors.address}
                            </span>
                        {/if}
                    </div>
                    <div class="form-control">
                        <label class="label" for="country_code">
                            <span class="label-text">Country</span>
                        </label>
                        <select
                            class="select select-bordered select-ghost"
                            bind:value={$form.country_code}
                            on:change={getStates}
                            disabled={!!price}
                        >
                            <option value="" disabled>Country</option>
                            {#each countries as country}
                                <option value={country.code}
                                    >{country.name}</option
                                >
                            {/each}
                        </select>
                        {#if $form.errors.country_code}
                            <span
                                class="label-text-alt mt-1 text-left text-error"
                            >
                                {$form.errors.country_code}
                            </span>
                        {/if}
                    </div>
                    <div class="form-control">
                        <label class="label" for="state_code">
                            <span class="label-text">State</span>
                        </label>
                        <select
                            class="select select-bordered select-ghost"
                            bind:value={$form.state_code}
                            disabled={!!price}
                        >
                            <option value="" disabled>State</option>
                            {#each states as state}
                                <option value={state.code}>{state.name}</option>
                            {/each}
                        </select>
                        {#if $form.errors.state_code}
                            <span
                                class="label-text-alt mt-1 text-left text-error"
                            >
                                {$form.errors.state_code}
                            </span>
                        {/if}
                    </div>
                    <div class="form-control">
                        <label class="label" for="city">
                            <span class="label-text">City</span>
                        </label>
                        <input
                            bind:value={$form.city}
                            type="text"
                            name="city"
                            id="city"
                            placeholder="City"
                            class="input input-bordered input-ghost"
                            disabled={!!price}
                        />
                        {#if $form.errors.city}
                            <span
                                class="label-text-alt mt-1 text-left text-error"
                            >
                                {$form.errors.city}
                            </span>
                        {/if}
                    </div>
                    <div class="form-control">
                        <label class="label" for="postal_code">
                            <span class="label-text">Postal Code</span>
                        </label>
                        <input
                            bind:value={$form.postal_code}
                            type="text"
                            name="postal_code"
                            id="postal_code"
                            placeholder="Postal Code"
                            class="input input-bordered input-ghost"
                            disabled={!!price}
                        />
                        {#if $form.errors.postal_code}
                            <span
                                class="label-text-alt mt-1 text-left text-error"
                            >
                                {$form.errors.postal_code}
                            </span>
                        {/if}
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body justify-around gap-4 p-0">
                <h2 class="card-title">Order Summary</h2>
                <hr />
                <div class="grid grid-cols-4 items-center justify-center gap-2">
                    <div class="col-span-1">
                        <img
                            src={story.data.cover}
                            alt="cover"
                            class="w-full"
                        />
                    </div>
                    <div class="col-span-3 text-primary">
                        <h4>
                            {$page.props.auth.user.details.first_name}
                            {$page.props.auth.user.details.last_name}
                        </h4>
                        <h3 class="card-title">{story.data.title}</h3>
                    </div>
                </div>
                {#if dayjs(story.data.created_at).diff(dayjs(), 'month') <= 6}
                    <div class="alert alert-success gap-0 p-2">
                        <div
                            class="w-full rounded-xl border-2 border-dashed border-neutral p-2 text-center"
                        >
                            Congratulations on completing your story in six
                            months! As a bonus, you will receive
                            <b class="block font-serif italic"
                                >three extra books.</b
                            >
                        </div>
                    </div>
                {/if}
                <table class="table w-full text-lg">
                    <tbody>
                        <tr>
                            <td>Number of Pages</td>
                            <td>{story.data.pages ?? '-'}</td>
                        </tr>
                        <tr>
                            <td>Color Printing</td>
                            <td>Picture book</td>
                        </tr>
                        <tr>
                            <td>Binding Type</td>
                            <td>Hard Cover</td>
                        </tr>
                        {#if price}
                            <tr>
                                <th>Total</th>
                                <th>{usd(price)}</th>
                            </tr>
                        {/if}
                    </tbody>
                </table>
                <div class="card-actions">
                    {#if !price}
                        <button
                            type="submit"
                            class="btn btn-primary w-full rounded-full"
                            disabled={$form.processing}
                            data-method="patch"
                        >
                            {#if $form.processing}
                                <span class="loading loading-spinner" />
                            {/if} Calculate Price
                        </button>
                    {:else}
                        <button
                            type="submit"
                            class="btn btn-secondary w-full rounded-full"
                            disabled={$form.processing}
                            data-method="post"
                        >
                            {#if $form.processing}
                                <span class="loading loading-spinner" />
                            {/if} Order Your Book
                        </button>
                    {/if}
                </div>
            </div>
        </div>
    </main>
</form>

<style lang="scss">
    .table {
        td,
        th {
            @apply p-0 py-1;
        }
    }
</style>
