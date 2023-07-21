<script context="module" lang="ts">
    import Base from '@/components/layouts/Base.svelte';
    import Auth from '@/components/layouts/Auth.svelte';
    export const layout = [Base, Auth];
</script>

<script lang="ts">
    import { useForm, inertia } from '@inertiajs/svelte';
    import Logo from '@/components/svg/logo.svg.svelte';
    import InputPassword from '@/components/InputPassword.svelte';

    const form = useForm({
        password: '',
    })

    function submit() {
        $form.post('/user/confirm-password', { replace: true });
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Confirm Password</title>
</svelte:head>

<div class="w-full p-4 flex flex-col">
    <a href="/" use:inertia class="max-w-[300px] h-auto">
        <Logo class="w-full" />
    </a>

    <div class="font-serif text-3xl italic text-primary my-8">
        Enter Your <span class="text-secondary-focus">Password</span>
    </div>

    <form on:submit|preventDefault={submit} class="flex flex-col items-center w-full">
        <div class="form-control w-full">
            <label class="label" for="password">
                <span class="label-text">Password</span>
            </label>
            <InputPassword bind:value={$form.password} type="password" name="password" placeholder="Password" class="w-full input input-bordered {$form.errors.password ? 'input-error' : ''}" required />
            {#if $form.errors.password}
                <span class="label-text-alt text-error text-left mt-1">{$form.errors.password}</span>
            {/if}
        </div>

        <div class="form-control w-full mt-8">
            <button class="btn btn-primary rounded-full" type="submit">Confirm</button>
        </div>
    </form>
</div>
