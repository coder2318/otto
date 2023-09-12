<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    import Settings from '@/components/Layouts/Settings.svelte'
    export const layout = [Base, Dashboard, Settings]
</script>

<script lang="ts">
    import { useForm } from '@inertiajs/svelte'
    import { fade } from 'svelte/transition'

    const form = useForm({
        email: {
            news_and_updates: false,
            tips_and_tutorials: false,
            plans: false,
        },
        push: {
            reminders: false,
        },
    })

    function submit() {}
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Notifications Settings</title>
</svelte:head>

<main class="card rounded-xl border border-base-300" in:fade>
    <form class="card-body gap-4" on:submit|preventDefault={submit}>
        <span class="card-title text-2xl text-primary lg:text-3xl">
            Notifications
        </span>
        <p class="text-base-content/60">
            Select the kinds of notifications you get about our activities and
            recommendations.
        </p>
        <div class="divider" />
        <div class="flex flex-col items-start gap-10 md:flex-row">
            <div class="min-w-[30%] flex-1 shrink-0">
                <span class="card-title text-xl text-primary">
                    Notifications
                </span>
                <p class="text-base-content/60">
                    Get emails to find out what's going on when you're not
                    online, You can turn these off.
                </p>
            </div>
            <div class="w-full">
                <div class="form-control w-full">
                    <label class="label justify-between">
                        <div class="flex flex-col">
                            <span class="label-text font-bold text-primary">
                                News and Updates
                            </span>
                            <span class="label-text-alt">
                                News about product and feature updates.
                            </span>
                        </div>
                        <input
                            type="checkbox"
                            bind:checked={$form.email.news_and_updates}
                            class="toggle toggle-primary"
                        />
                    </label>
                </div>
                <div class="form-control w-full">
                    <label class="label justify-between">
                        <div class="flex flex-col">
                            <span class="label-text font-bold text-primary">
                                Tips and Tutorials
                            </span>
                            <span class="label-text-alt">
                                Tips on getting more out of Otto Story.
                            </span>
                        </div>
                        <input
                            type="checkbox"
                            bind:checked={$form.email.tips_and_tutorials}
                            class="toggle toggle-primary"
                        />
                    </label>
                </div>
                <div class="form-control w-full">
                    <label class="label justify-between">
                        <div class="flex flex-col">
                            <span class="label-text font-bold text-primary">
                                Plans
                            </span>
                            <span class="label-text-alt">
                                Plans and pricing updates.
                            </span>
                        </div>
                        <input
                            type="checkbox"
                            bind:checked={$form.email.plans}
                            class="toggle toggle-primary"
                        />
                    </label>
                </div>
            </div>
        </div>
        <div class="divider" />
        <div class="flex flex-col items-start gap-10 md:flex-row">
            <div class="min-w-[30%] flex-1 shrink-0">
                <span class="card-title text-xl text-primary">
                    Push Notifications
                </span>
                <p class="text-base-content/60">
                    Get notifications to your device when something happens.
                </p>
            </div>
            <div class="w-full">
                <div class="form-control w-full">
                    <label class="label justify-between">
                        <div class="flex flex-col">
                            <span class="label-text font-bold text-primary">
                                Reminders
                            </span>
                            <span class="label-text-alt">
                                These are notification to remind you of
                                autobiography
                            </span>
                        </div>
                        <input
                            type="checkbox"
                            bind:checked={$form.push.reminders}
                            class="toggle toggle-primary"
                        />
                    </label>
                </div>
            </div>
        </div>
        <div class="card-actions justify-end">
            <button
                class="btn btn-primary"
                class:disabled={$form.processing}
                disabled={$form.processing}
                type="submit"
            >
                {#if $form.processing}
                    <span class="loading loading-spinner" />
                {/if}
                Save
            </button>
        </div>
    </form>
</main>
