<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Auth from '@/components/Layouts/Auth.svelte'
    export const layout = [Base, Auth]
</script>

<script lang="ts">
    import { fade } from 'svelte/transition'
    import { useForm, inertia } from '@inertiajs/svelte'
    import Logo from '@/components/SVG/logo.svg.svelte'
    import InputPassword from '@/components/Auth/InputPassword.svelte'
    import SocialLogin from '@/components/Auth/SocialLogin.svelte'

    const form = useForm({
        email: '',
        password: '',
        password_confirmation: '',
    })

    function submit() {
        $form.post('/register')
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Sign Up</title>
</svelte:head>

<div class="p-4 flex flex-col w-full" in:fade>
    <a href="/" use:inertia class="max-w-[300px] h-auto">
        <Logo class="w-full" />
    </a>

    <div class="font-serif text-3xl italic text-primary my-8">
        Start Your <span class="text-secondary-focus">Own Story!!!</span>
    </div>

    <form
        on:submit|preventDefault={submit}
        class="flex flex-col items-center w-full"
        autocomplete="on"
    >
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
                <span class="label-text-alt text-error text-left mt-1"
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
                class="w-full input input-bordered {$form.errors.password
                    ? 'input-error'
                    : ''}"
                required
                autocomplete="new-password"
            />
            {#if $form.errors.password}
                <span class="label-text-alt text-error text-left mt-1"
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
                class="w-full input input-bordered {$form.errors
                    .password_confirmation
                    ? 'input-error'
                    : ''}"
                required
                autocomplete="new-password"
            />
            {#if $form.errors.password}
                <span class="label-text-alt text-error text-left mt-1"
                    >{$form.errors.password}</span
                >
            {/if}
        </div>

        <div class="form-control w-full mt-8">
            <button class="btn btn-primary rounded-full" type="submit"
                >Sign Up</button
            >
        </div>

        <div class="mt-4 text-center">
            Already have an account? <a
                href="/login"
                use:inertia
                class="link link-primary">Log In</a
            >
        </div>
    </form>

    <div class="divider text-base-content/30 my-8 lg:my-16">
        or continue with
    </div>

    <SocialLogin />
</div>
