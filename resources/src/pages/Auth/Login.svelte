<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte';
    import Auth from '@/components/Layouts/Auth.svelte';
    export const layout = [Base, Auth];
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Sign In</title>
</svelte:head>

<script lang="ts">
    import { fade } from 'svelte/transition'
    import { useForm, inertia } from '@inertiajs/svelte';
    import Logo from '@/components/SVG/logo.svg.svelte';
    import InputPassword from '@/components/Auth/InputPassword.svelte';
    import SocialLogin from '@/components/Auth/SocialLogin.svelte';

    const form = useForm({
        email: '',
        password: '',
        remember: false,
    })

    function submit() {
        $form.post('/login');
    }
</script>

<div class="p-4 flex flex-col w-full" in:fade>
    <a href="/" use:inertia class="max-w-[300px] h-auto">
        <Logo class="w-full" />
    </a>

    <div class="font-serif text-3xl italic text-primary my-8">
        Back to Your <span class="text-secondary-focus">Digital Book!!!</span>
    </div>

    <form on:submit|preventDefault={submit} class="flex flex-col items-center w-full">
        <div class="form-control w-full">
            <label class="label" for="email">
                <span class="label-text">Email</span>
            </label>
            <input bind:value={$form.email} type="email" name="email" placeholder="Email" class="input input-bordered {$form.errors.email ? 'input-error' : ''}" required />
            {#if $form.errors.email}
                <span class="label-text-alt text-error text-left mt-1">{$form.errors.email}</span>
            {/if}
        </div>
        <div class="form-control w-full">
            <label class="label" for="password">
                <span class="label-text">Password</span>
            </label>
            <InputPassword bind:value={$form.password} type="password" name="password" placeholder="Password" class="w-full input input-bordered {$form.errors.password ? 'input-error' : ''}" required />
            {#if $form.errors.password}
                <span class="label-text-alt text-error text-left mt-1">{$form.errors.password}</span>
            {/if}
        </div>
        <div class="form-control w-full">
            <div class="flex items-center justify-around">
                <label class="label cursor-pointer justify-start gap-2 px-0 my-2">
                    <input bind:checked={$form.remember} type="checkbox" class="checkbox {$form.errors.remember ? 'checkbox-error' : 'checkbox-primary'}" name="remember" />
                    <span class="label-text">Remember me</span>
                </label>
                <a href="/forgot-password" use:inertia class="link link-primary ml-auto">Forgot password?</a>
            </div>
            {#if $form.errors.remember}
                <span class="label-text-alt text-error text-left mt-1">{$form.errors.remember}</span>
            {/if}
        </div>

        <div class="form-control w-full mt-8">
            <button class="btn btn-primary rounded-full" type="submit">Login</button>
        </div>

        <div class="mt-4 text-center">
            Don't have an account? <a href="/register" use:inertia class="link link-primary">Sign up</a>
        </div>
    </form>

    <div class="divider text-base-content/30 my-8 lg:my-16">or continue with</div>

    <SocialLogin />
</div>
