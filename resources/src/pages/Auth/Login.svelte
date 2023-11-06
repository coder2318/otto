<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Auth from '@/components/Layouts/Auth.svelte'
    export const layout = [Base, Auth]
</script>

<script lang="ts">
    import { fade } from 'svelte/transition'
    import { useForm, inertia, page } from '@inertiajs/svelte'
    import Logo from '@/components/SVG/logo.svg.svelte'
    import InputPassword from '@/components/Auth/InputPassword.svelte'
    import SocialLogin from '@/components/Auth/SocialLogin.svelte'
    import { addHoneypot } from '@/service/honeypot'
    import Honeypot from '@/components/Honeypot.svelte'

    const form = useForm(
        addHoneypot($page?.props?.honeypot)({
            email: '',
            password: '',
            remember: false,
        })
    )

    function submit() {
        $form.post('/login')
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Sign In</title>
</svelte:head>

<div class="flex w-full flex-col p-4" in:fade>
    <a href="/" use:inertia class="h-auto max-w-[300px]">
        <Logo class="w-full" />
    </a>

    <div class="my-8 font-serif text-3xl italic text-primary">Start Your Story Now!</div>

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
                placeholder="Email"
                class="input input-bordered {$form.errors.email ? 'input-error' : ''}"
                required
            />
            {#if $form.errors.email}
                <span class="label-text-alt mt-1 text-left text-error">{$form.errors.email}</span>
            {/if}
        </div>
        <div class="form-control w-full">
            <label class="label" for="password">
                <span class="label-text">Password</span>
            </label>
            <InputPassword
                bind:value={$form.password}
                type="password"
                name="password"
                placeholder="Password"
                class="input input-bordered w-full {$form.errors.password ? 'input-error' : ''}"
                required
            />
            {#if $form.errors.password}
                <span class="label-text-alt mt-1 text-left text-error">{$form.errors.password}</span>
            {/if}
        </div>
        <div class="form-control w-full">
            <div class="flex items-center justify-around">
                <label class="label my-2 cursor-pointer justify-start gap-2 px-0">
                    <input
                        bind:checked={$form.remember}
                        type="checkbox"
                        class="checkbox {$form.errors.remember ? 'checkbox-error' : 'checkbox-primary'}"
                        name="remember"
                    />
                    <span class="label-text">Remember me</span>
                </label>
                <a href="/forgot-password" use:inertia class="link-primary link ml-auto">Forgot password?</a>
            </div>
            {#if $form.errors.remember}
                <span class="label-text-alt mt-1 text-left text-error">{$form.errors.remember}</span>
            {/if}
        </div>

        <div class="form-control mt-8 w-full">
            <button class="btn btn-primary rounded-full" type="submit">
                {#if $form.processing}
                    <span class="loading loading-spinner" />
                {/if}
                Login
            </button>
        </div>

        <div class="mt-4 text-center">
            Don't have an account? <a href="/register" use:inertia class="link-primary link">Sign up</a>
        </div>
    </form>

    <div class="divider my-8 text-base-content/30 lg:my-16">or continue with</div>

    <SocialLogin />
</div>
