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
    import { addHoneypot, type Honeypot } from '@/service/honeypot'

    $: honeypot = $page?.props?.honeypot as Honeypot

    const form = useForm(
        addHoneypot(honeypot)({
            email: '',
            password: '',
            password_confirmation: '',
        })
    )

    function submit() {
        $form.post('/register')
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Sign Up</title>
</svelte:head>

<div class="flex w-full flex-col p-4" in:fade>
    <a href="/" use:inertia class="h-auto max-w-[300px]">
        <Logo class="w-full" />
    </a>

    <div class="my-8 font-serif text-3xl italic text-primary">
        Start Your <span class="text-secondary-focus">Own Story!!!</span>
    </div>

    <form
        on:submit|preventDefault={submit}
        class="flex w-full flex-col items-center"
        autocomplete="on"
    >
        {#if honeypot.enabled}
            <div class="hidden">
                <input
                    type="text"
                    bind:value={$form[honeypot.nameFieldName]}
                    name="honeypot.nameFieldName"
                    id="honeypot.nameFieldName"
                />
                <input
                    type="text"
                    bind:value={$form[honeypot.validFromFieldName]}
                />
            </div>
        {/if}
        <div class="form-control w-full">
            <label class="label" for="email">
                <span class="label-text">Email</span>
            </label>
            <input
                bind:value={$form.email}
                type="email"
                name="email"
                placeholder="Email"
                class="input input-bordered {$form.errors.email
                    ? 'input-error'
                    : ''}"
                required
                autocomplete="email"
            />
            {#if $form.errors.email}
                <span class="label-text-alt mt-1 text-left text-error"
                    >{$form.errors.email}</span
                >
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
                class="input input-bordered w-full {$form.errors.password
                    ? 'input-error'
                    : ''}"
                required
                autocomplete="new-password"
            />
            {#if $form.errors.password}
                <span class="label-text-alt mt-1 text-left text-error"
                    >{$form.errors.password}</span
                >
            {/if}
        </div>
        <div class="form-control w-full">
            <label class="label" for="password_confirmation">
                <span class="label-text">Confirm Password</span>
            </label>
            <InputPassword
                bind:value={$form.password_confirmation}
                type="password"
                name="password_confirmation"
                placeholder="Password Confirmation"
                class="input input-bordered w-full {$form.errors
                    .password_confirmation
                    ? 'input-error'
                    : ''}"
                required
                autocomplete="new-password"
            />
            {#if $form.errors.password}
                <span class="label-text-alt mt-1 text-left text-error"
                    >{$form.errors.password}</span
                >
            {/if}
        </div>

        <div class="form-control mt-8 w-full">
            <button class="btn btn-primary rounded-full" type="submit"
                >Sign Up</button
            >
        </div>

        <div class="mt-4 text-center">
            Already have an account? <a
                href="/login"
                use:inertia
                class="link-primary link">Log In</a
            >
        </div>
    </form>

    <div class="divider my-8 text-base-content/30 lg:my-16">
        or continue with
    </div>

    <SocialLogin />
</div>
