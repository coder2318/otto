<script context="module" lang="ts">
    import InputPassword from '@/components/Auth/InputPassword.svelte'
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    import Settings from '@/components/Layouts/Settings.svelte'
    export const layout = [Base, Dashboard, Settings]
</script>

<script lang="ts">
    import { useForm } from '@inertiajs/svelte'
    import { fade } from 'svelte/transition'

    const form = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    })

    function submit() {
        $form.put(window.location.pathname, {
            onSuccess: () => $form.reset(),
        })
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Change Password</title>
</svelte:head>

<main class="card rounded-xl border border-base-300" in:fade>
    <form class="card-body gap-4" on:submit|preventDefault={submit}>
        <span class="card-title text-2xl text-primary lg:text-3xl"
            >Change Password</span
        >
        <div class="form-control">
            <label class="label" for="current_password">
                <span class="label-text">Current Password</span>
            </label>
            <InputPassword
                class="input input-bordered w-full {$form.errors
                    .current_password
                    ? 'input-error'
                    : ''}"
                bind:value={$form.current_password}
                type="password"
                name="current_password"
                placeholder="Enter Current Password"
                required
            />
            {#if $form.errors.current_password}
                <span class="label-text-alt mt-1 text-left text-error">
                    {$form.errors.current_password}
                </span>
            {/if}
        </div>
        <div class="form-control">
            <label class="label" for="password">
                <span class="label-text">New Password</span>
            </label>
            <InputPassword
                class="input input-bordered w-full {$form.errors.password
                    ? 'input-error'
                    : ''}"
                bind:value={$form.password}
                type="password"
                name="password"
                placeholder="Enter New Password"
                required
            />
            {#if $form.errors.password}
                <span class="label-text-alt mt-1 text-left text-error">
                    {$form.errors.password}
                </span>
            {/if}
        </div>
        <div class="form-control">
            <label class="label" for="password_confirmation">
                <span class="label-text">Confirm Password</span>
            </label>
            <InputPassword
                class="input input-bordered w-full {$form.errors
                    .password_confirmation
                    ? 'input-error'
                    : ''}"
                bind:value={$form.password_confirmation}
                type="password"
                name="password_confirmation"
                placeholder="Confirm New Password"
                required
            />
            {#if $form.errors.password_confirmation}
                <span class="label-text-alt mt-1 text-left text-error">
                    {$form.errors.password_confirmation}
                </span>
            {/if}
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
                Change Password
            </button>
        </div>
    </form>
</main>
