<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Auth from '@/components/Layouts/Auth.svelte'
    export const layout = [Base, Auth]
</script>

<script lang="ts">
    import { inertia, useForm, page } from '@inertiajs/svelte'
    import Logo from '@/components/SVG/logo.svg.svelte'
    import { addHoneypot } from '@/service/honeypot'
    import Honeypot from '@/components/Honeypot.svelte'

    const form = useForm(addHoneypot($page?.props?.honeypot)())

    function submit() {
        $form.post('/email/verification-notification')
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Verify Email Address</title>
</svelte:head>

<div class="flex w-full flex-col p-4">
    <a href="/" use:inertia class="h-auto max-w-[300px]">
        <Logo class="w-full" />
    </a>

    <div class="my-8 font-serif text-3xl italic text-primary">
        Verify Your <span class="text-secondary-focus">Email Address</span>
    </div>

    <form
        on:submit|preventDefault={submit}
        class="flex w-full flex-col items-center"
    >
        <Honeypot honeypot={$page?.props?.honeypot} {form} />
        <div class="mb-8">
            Thanks for signing up! Before getting started, could you verify your
            email address by clicking on the link we just emailed to you? If you
            didn't receive the email, we will gladly send you another.
        </div>

        <div class="form-control w-full">
            <button class="btn btn-primary rounded-full" type="submit"
                >Resend Verification Email</button
            >
        </div>
    </form>
</div>
