<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte';
    import Auth from '@/components/Layouts/Auth.svelte';
    export const layout = [Base, Auth];
</script>

<script lang="ts">
    import { fade } from 'svelte/transition'
    import { useForm, inertia } from '@inertiajs/svelte';
    import Logo from '@/components/SVG/logo.svg.svelte';

    const form = useForm({
        email: '',
    })

    function submit() {
        $form.post('/forgot-password');
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Forgot Password</title>
</svelte:head>

<div class="p-4 flex flex-col w-full" in:fade>
    <a href="/" use:inertia class="max-w-[300px] h-auto">
        <Logo class="w-full" />
    </a>

    <div class="font-serif text-3xl italic text-primary my-8">
        Forgot Your <span class="text-secondary-focus">Password</span>
    </div>

    <form on:submit|preventDefault={submit} class="flex flex-col items-center w-full">
        <div class="form-control w-full">
            <label class="label" for="email">
                <span class="label-text">Email</span>
            </label>
            <input bind:value={$form.email} type="email" name="email" placeholder="Enter Email Address" class="input input-bordered {$form.errors.email ? 'input-error' : ''}" required />
            {#if $form.errors.email}
                <span class="label-text-alt text-error text-left mt-1">{$form.errors.email}</span>
            {/if}
        </div>

        <div class="form-control w-full mt-8">
            <button class="btn btn-primary rounded-full" type="submit">Reset Password</button>
        </div>
    </form>

    <div class="mt-8 lg:mt-16 text-center">
        Back to <a href="/login" use:inertia class="link link-primary">Log in</a>
    </div>
</div>
