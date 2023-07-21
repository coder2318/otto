<script context="module" lang="ts">
    import Base from '@/components/layouts/Base.svelte';
    import Auth from '@/components/layouts/Auth.svelte';
    export const layout = [Base, Auth];
</script>

<script lang="ts">
    import { fade } from 'svelte/transition'
    import { useForm, inertia } from '@inertiajs/svelte';
    import Logo from '@/components/svg/logo.svg.svelte';
    import InputPassword from '@/components/InputPassword.svelte';

    const form = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    })

    function submit() {
        $form.post('/register');
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

    <form on:submit|preventDefault={submit} class="flex flex-col items-center w-full" autocomplete="on">
        <div class="form-control w-full">
            <label class="label" for="name">
                <span class="label-text">Name</span>
            </label>
            <input bind:value={$form.name} type="text" name="name" placeholder="Name" class="input input-bordered {$form.errors.name ? 'input-error' : ''}" required autocomplete="name" />
            {#if $form.errors.name}
                <span class="label-text-alt text-error text-left mt-1">{$form.errors.name}</span>
            {/if}
        </div>
        <div class="form-control w-full">
            <label class="label" for="email">
                <span class="label-text">Email</span>
            </label>
            <input bind:value={$form.email} type="email" name="email" placeholder="Email" class="input input-bordered {$form.errors.email ? 'input-error' : ''}" required autocomplete="email" />
            {#if $form.errors.email}
                <span class="label-text-alt text-error text-left mt-1">{$form.errors.email}</span>
            {/if}
        </div>
        <div class="form-control w-full">
            <label class="label" for="password">
                <span class="label-text">Password</span>
            </label>
            <InputPassword bind:value={$form.password} type="password" name="password" placeholder="Password" class="w-full input input-bordered {$form.errors.password ? 'input-error' : ''}" required autocomplete="new-password" />
            {#if $form.errors.password}
                <span class="label-text-alt text-error text-left mt-1">{$form.errors.password}</span>
            {/if}
        </div>
        <div class="form-control w-full">
            <label class="label" for="password_confirmation">
                <span class="label-text">Confirm Password</span>
            </label>
            <InputPassword bind:value={$form.password_confirmation} type="password" name="password_confirmation" placeholder="Password Confirmation" class="w-full input input-bordered {$form.errors.password_confirmation ? 'input-error' : ''}" required autocomplete="new-password" />
            {#if $form.errors.password}
                <span class="label-text-alt text-error text-left mt-1">{$form.errors.password}</span>
            {/if}
        </div>

        <div class="form-control w-full mt-8">
            <button class="btn btn-primary rounded-full" type="submit">Sign Up</button>
        </div>

        <div class="mt-4 text-center">
            Already have an account? <a href="/register" use:inertia class="link link-primary">Log In</a>
        </div>
    </form>

    <div class="divider text-base-content/30 my-8 lg:my-16">or continue with</div>

    <div class="flex gap-4 mx-auto">
        <button class="btn rounded-full" type="button">
            <svg class="h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"><path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/><path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/><path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/><path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/></svg>
        </button>
        <button class="btn rounded-full" type="button">
            <svg class="h-full" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 48 48"><path fill="#3F51B5" d="M42,37c0,2.762-2.238,5-5,5H11c-2.761,0-5-2.238-5-5V11c0-2.762,2.239-5,5-5h26c2.762,0,5,2.238,5,5V37z"/><path fill="#FFF" d="M34.368,25H31v13h-5V25h-3v-4h3v-2.41c0.002-3.508,1.459-5.59,5.592-5.59H35v4h-2.287C31.104,17,31,17.6,31,18.723V21h4L34.368,25z"/></svg>
        </button>
    </div>
</div>
