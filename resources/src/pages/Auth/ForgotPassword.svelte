<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Auth from '@/components/Layouts/Auth.svelte'
    export const layout = [Base, Auth]
</script>

<script lang="ts">
    import { fade } from 'svelte/transition'
    import { useForm, inertia, page } from '@inertiajs/svelte'
    import Logo from '@/components/SVG/logo.svg.svelte'
    import { addHoneypot } from '@/service/honeypot'
    import Honeypot from '@/components/Honeypot.svelte'

    const form = useForm(
        addHoneypot($page?.props?.honeypot)({
            email: '',
        })
    )

    function submit() {
        $form.post('/forgot-password')
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Forgot Password</title>
</svelte:head>

<div class="flex w-full flex-col p-4" in:fade>
    <a href="/" use:inertia class="h-auto max-w-[300px]">
        <Logo class="w-full" />
    </a>

    <div class="my-8 font-serif text-3xl italic text-primary">
        Forgot Your <span class="text-secondary-focus">Password</span>
    </div>

    <form on:submit|preventDefault={submit} class="flex w-full flex-col items-center">
        <Honeypot honeypot={$page?.props?.honeypot} {form} />
        <div class="form-control w-full">
            <label class="label" for="email">
                <span class="label-text">Email</span>
            </label>
            <input
                bind:value={$form.email}
                type="email"
                name="email"
                placeholder="Enter Email Address"
                class="input input-bordered {$form.errors.email ? 'input-error' : ''}"
                required
            />
            {#if $form.errors.email}
                <span class="label-text-alt mt-1 text-left text-error">{$form.errors.email}</span>
            {/if}
        </div>

        <div class="form-control mt-8 w-full">
            <button class="btn btn-primary rounded-full" type="submit">
                {#if $form.processing}
                    <span class="loading loading-spinner" />
                {/if}
                Reset Password
            </button>
        </div>
    </form>

    <div class="mt-8 text-center lg:mt-16">
        Back to <a href="/login" use:inertia class="link-primary link">Log in</a>
    </div>
</div>
