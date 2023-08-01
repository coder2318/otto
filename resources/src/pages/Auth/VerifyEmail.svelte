<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Auth from '@/components/Layouts/Auth.svelte'
    export const layout = [Base, Auth]
</script>

<script lang="ts">
    import { inertia, router } from '@inertiajs/svelte'
    import Logo from '@/components/SVG/logo.svg.svelte'

    function submit() {
        router.post('/email/verification-notification')
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Verify Email Address</title>
</svelte:head>

<div class="w-full p-4 flex flex-col">
    <a href="/" use:inertia class="max-w-[300px] h-auto">
        <Logo class="w-full" />
    </a>

    <div class="font-serif text-3xl italic text-primary my-8">
        Verify Your <span class="text-secondary-focus">Email Address</span>
    </div>

    <form
        on:submit|preventDefault={submit}
        class="flex flex-col items-center w-full"
    >
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
