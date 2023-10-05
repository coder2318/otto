<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Auth from '@/components/Layouts/Auth.svelte'
    export const layout = [Base, Auth]
</script>

<script lang="ts">
    import { useForm, inertia, page } from '@inertiajs/svelte'
    import Logo from '@/components/SVG/logo.svg.svelte'
    import InputPassword from '@/components/Auth/InputPassword.svelte'
    import { addHoneypot } from '@/service/honeypot'
    import Honeypot from '@/components/Honeypot.svelte'

    const url = new URL(document.location.toString())

    const form = useForm(
        addHoneypot($page?.props?.honeypot)({
            password: '',
            password_confirmation: '',
            email: url.searchParams.get('email'),
            token: url.pathname.split(/[/]/).pop(),
        })
    )

    function submit() {
        $form.post('/reset-password')
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Reset Password</title>
</svelte:head>

<div class="flex w-full flex-col p-4">
    <a href="/" use:inertia class="h-auto max-w-[300px]">
        <Logo class="w-full" />
    </a>

    <div class="my-8 font-serif text-3xl italic text-primary">
        Reset <span class="text-secondary-focus">Password</span>
    </div>

    <form on:submit|preventDefault={submit} class="flex w-full flex-col items-center">
        <Honeypot honeypot={$page?.props?.honeypot} {form} />
        <div class="form-control w-full">
            <label class="label" for="password">
                <span class="label-text">New Password</span>
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
            <label class="label" for="password_confirmation">
                <span class="label-text">Password Confirmation</span>
            </label>
            <InputPassword
                bind:value={$form.password_confirmation}
                type="password"
                name="password_confirmation"
                placeholder="Password Confirmation"
                class="input input-bordered w-full {$form.errors.password ? 'input-error' : ''}"
                required
            />
            {#if $form.errors.password}
                <span class="label-text-alt mt-1 text-left text-error">{$form.errors.password_confirmation}</span>
            {/if}
        </div>

        <div class="form-control mt-8 w-full">
            <button class="btn btn-primary rounded-full" type="submit">Save Password</button>
        </div>
    </form>
</div>
