<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    import splash4 from '@/assets/img/splash-4.svg'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import qs from 'qs'
    import { useForm, page, router } from '@inertiajs/svelte'
    import Breadcrumbs from '@/components/Stories/Breadcrumbs.svelte'
    import { usd } from '@/service/helpers'
    import { dayjs } from '@/service/dayjs'

    export let price: number | null = null
    export let story: { data: App.Story }
    export let countries = []
    export let states = []
    export let payment = null

    let query = qs.parse($page.url.split('?').slice(1).join('?'))

    const form = useForm({
        first_name: '',
        last_name: '',
        phone: '',
        email: '',
        address: '',
        city: '',
        country_code: query?.country_code ?? '',
        state_code: '',
        postal_code: '',
        shipping_method: 'EXPRESS',
        quantity: 1,
    })

    function onInput() {
        if ($form.processing) {
            return
        }

        const valid =
            !!$form.first_name &&
            !!$form.last_name &&
            !!$form.phone &&
            !!$form.email &&
            !!$form.address &&
            !!$form.city &&
            !!$form.country_code &&
            !!$form.state_code &&
            !!$form.postal_code &&
            !!$form.shipping_method &&
            !!$form.quantity

        if (valid) {
            $form.patch(window.location.pathname, {
                preserveScroll: true,
                preserveState: true,
                only: ['price', 'flash', 'errors'],
            })
        }
    }

    const shipping_methods = [
        {
            id: 'MAIL',
            name: 'Mail',
        },
        {
            id: 'PRIORITY_MAIL',
            name: 'Priority Mail',
        },
        {
            id: 'GROUND_HD',
            name: 'Ground (HD)',
        },
        {
            id: 'GROUND_BUS',
            name: 'Ground (Business)',
        },
        {
            id: 'GROUND',
            name: 'Ground',
        },
        {
            id: 'EXPEDITED',
            name: 'Expedited',
        },
        {
            id: 'EXPRESS',
            name: 'Express',
        },
    ]

    function submit(event: SubmitEvent) {
        $form.submit(event.submitter.dataset.method, window.location.pathname, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
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
                preserveState: true,
                preserveScroll: true,
                replace: true,
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

<form on:submit|preventDefault={submit}>
    <Breadcrumbs step={3} {story} />

    <section class="orderDetails">
        <div class="otto-container">
            <div class="wrapper">
                <img src={splash4} class="orderDetails-illustration" alt="" />
                <h1 class="fz_h2 title text-primary">
                    Order <i>Details </i>
                </h1>
            </div>

            <div class="wrap">
                <div class="shippingDetails">
                    <span class="orderDetails-title">Shipping Details</span>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
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
                                disabled={$form.processing}
                                on:change={onInput}
                            />
                            {#if $form.errors.first_name}
                                <span class="label-text-alt mt-1 text-left text-error">
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
                                disabled={$form.processing}
                                on:change={onInput}
                            />
                            {#if $form.errors.last_name}
                                <span class="label-text-alt mt-1 text-left text-error">
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
                                disabled={$form.processing}
                                on:change={onInput}
                            />
                            {#if $form.errors.phone}
                                <span class="label-text-alt mt-1 text-left text-error">
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
                                disabled={$form.processing}
                                on:change={onInput}
                            />
                            {#if $form.errors.email}
                                <span class="label-text-alt mt-1 text-left text-error">
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
                                disabled={$form.processing}
                                on:change={onInput}
                            />
                            {#if $form.errors.address}
                                <span class="label-text-alt mt-1 text-left text-error">
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
                                disabled={$form.processing}
                                on:change={onInput}
                            >
                                <option value="" disabled>Country</option>
                                {#each countries as country}
                                    <option value={country.code}>{country.name}</option>
                                {/each}
                            </select>
                            {#if $form.errors.country_code}
                                <span class="label-text-alt mt-1 text-left text-error">
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
                                disabled={$form.processing}
                                on:change={onInput}
                            >
                                <option value="" disabled>State</option>
                                {#each states as state}
                                    <option value={state.code}>{state.name}</option>
                                {/each}
                            </select>
                            {#if $form.errors.state_code}
                                <span class="label-text-alt mt-1 text-left text-error">
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
                                disabled={$form.processing}
                                on:change={onInput}
                            />
                            {#if $form.errors.city}
                                <span class="label-text-alt mt-1 text-left text-error">
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
                                disabled={$form.processing}
                                on:change={onInput}
                            />
                            {#if $form.errors.postal_code}
                                <span class="label-text-alt mt-1 text-left text-error">
                                    {$form.errors.postal_code}
                                </span>
                            {/if}
                        </div>
                        <div class="form-control">
                            <label class="label" for="shipping_method">
                                <span class="label-text">Shipping Method</span>
                            </label>
                            <select
                                class="select select-bordered select-ghost"
                                bind:value={$form.shipping_method}
                                disabled={$form.processing}
                                on:change={onInput}
                            >
                                {#each shipping_methods as method}
                                    <option value={method.id}>{method.name}</option>
                                {/each}
                            </select>
                            {#if $form.errors.shipping_method}
                                <span class="label-text-alt mt-1 text-left text-error">
                                    {$form.errors.shipping_method}
                                </span>
                            {/if}
                        </div>
                        <div class="form-control">
                            <label class="label" for="quantity">
                                <span class="label-text">Quantity</span>
                            </label>
                            <input
                                bind:value={$form.quantity}
                                type="number"
                                name="quantity"
                                id="quantity"
                                placeholder="Quantity"
                                class="input input-bordered input-ghost"
                                disabled={$form.processing}
                                on:change={onInput}
                                min="1"
                            />
                            {#if $form.errors.quantity}
                                <span class="label-text-alt mt-1 text-left text-error">
                                    {$form.errors.quantity}
                                </span>
                            {/if}
                        </div>
                    </div>
                </div>

                <div class="orderSummery">
                    <span class="orderDetails-title">Order Summary</span>

                    <div class="bookInfo grid grid-cols-4 items-center justify-center gap-2">
                        <div class="col-span-1">
                            <img
                                src={story.data.cover.url}
                                alt="cover"
                                class="aspect-[2/3] w-full object-cover object-right"
                            />
                        </div>
                        <div class="col-span-3 text-primary">
                            <h4 class="bookInfo-author font-serif">
                                {$page.props?.auth?.user?.data.details.first_name}
                                {$page.props?.auth?.user?.data.details.last_name}
                            </h4>
                            <h3 class="bookInfo-name font-serif">{story.data.title}</h3>
                        </div>
                    </div>

                    {#if dayjs(story.data.created_at).diff(dayjs(), 'month') <= 6}
                        <div class="grrenBlock">
                            <div>
                                <p>
                                    Congratulations on completing your story in six months! As a bonus, you will receive
                                    <br />
                                    <i class="font-serif">three extra books</i>
                                </p>
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
                            <tr>
                                <td>Payment Method</td>
                                <td>
                                    {#if payment}
                                        **** **** **** {payment?.card?.last4}
                                    {:else}
                                        -
                                    {/if}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="card-actions">
                        <button
                            type="submit"
                            class="btn btn-primary w-full rounded-full"
                            class:btn-outline={$form.processing || !price}
                            disabled={$form.processing}
                            data-method={price ? 'post' : 'patch'}
                        >
                            {#if $form.processing}
                                <span class="loading loading-spinner" />
                            {/if}
                            {#if price}
                                Order Your Book <b>{usd(price)}</b>
                            {:else}
                                Calculate Price
                            {/if}
                        </button>
                    </div>
                </div>
            </div>
            <!--  -->
        </div>
    </section>
</form>

<style lang="scss">
    .table {
        td {
            @apply p-0 py-1;
        }
    }

    .orderDetails {
        padding-bottom: 100px;
        .wrapper {
            position: relative;
            margin-bottom: 62px;

            .title {
                z-index: 2;
                position: relative;
            }
        }
        &-illustration {
            position: absolute;
            width: auto;
            height: 123px;
            left: -83px;
            top: -23px;
        }

        .wrap {
            display: flex;
        }

        &-title {
            font-size: 28px;
            color: #333333;
            padding-bottom: 16px;
            margin-bottom: 16px;
            border-bottom: 1px solid #d9cebf;
            display: block;
        }

        .shippingDetails {
            background-color: #fff;
            border-radius: 24px;
            overflow: hidden;
            padding: 70px;
            width: 66%;
            max-width: 800px;
            margin-right: 56px;

            .form-control {
                select {
                    height: 60px;
                    font-size: 16px;
                    color: #999999;
                    line-height: 1;
                    // background: #fff;
                    &::placeholder {
                        color: #999999;
                    }

                    &:focus {
                        outline: none;
                        background: #fff;
                    }
                }
                input {
                    height: 60px;
                    font-size: 16px;
                    color: #999999;
                    background: #fff;
                    line-height: 1;
                    &::placeholder {
                        color: #999999;
                    }

                    &:focus {
                        outline: none;
                        background: #fff;
                    }
                }
            }
        }
        .orderSummery {
            width: 34%;

            .orderDetails-title {
                margin-bottom: 10px;
                padding-bottom: 10px;
            }

            .bookInfo {
                margin-bottom: 10px;

                &-author {
                    font-size: 20px;
                    line-height: 34px;
                }
                &-name {
                    font-size: 30px;
                    line-height: 34px;
                }
            }

            .grrenBlock {
                background-color: #a9d392;
                border-radius: 10px;
                padding: 10px;
                margin-bottom: 15px;

                div {
                    border: 1px dashed #fff;
                    border-radius: 10px;
                    padding: 10px 15px;
                }

                p {
                    font-size: 18px;
                    line-height: 26px;
                    text-align: center;
                    color: #0c345c;

                    i {
                        font-size: 24px;
                        font-weight: 60;
                    }
                }
            }

            .table {
                margin-bottom: 30px;
                tr {
                    border-bottom: 1px solid rgba(0, 0, 0, 0.15);
                    td {
                        padding: 8px 0;

                        &:nth-child(1) {
                            color: #8b8b8b;
                        }
                        &:nth-child(2) {
                            text-align: right;
                            color: #0c345c;
                        }
                    }
                }
            }
        }
    }

    @media (max-width: 1199px) {
        .orderDetails {
            .shippingDetails {
                padding: 40px;
                margin-right: 20px;
            }
        }
    }
    @media (max-width: 991px) {
        .orderDetails {
            .wrap {
                flex-direction: column;
            }
            .shippingDetails {
                width: 100%;
                max-width: 100%;
                margin-right: 0;
                margin-bottom: 30px;
                padding: 20px;
            }
            .orderSummery {
                width: 100%;
            }
        }
    }
</style>
