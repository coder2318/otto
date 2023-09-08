<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Focus from '@/components/Layouts/Focus.svelte'
    export const layout = [Base, Focus]
</script>

<script lang="ts">
    import { useForm, page, inertia } from '@inertiajs/svelte'
    import { addHoneypot } from '@/service/honeypot'
    import Honeypot from '@/components/Honeypot.svelte'

    const form = useForm(
        addHoneypot($page?.props?.honeypot)({
            name: '',
            email: '',
        })
    )

    function submit() {
        $form.post('/preorder', { preserveScroll: true })
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Access not allowed</title>
</svelte:head>

<div class="z-10 flex flex-1 flex-col items-center">
    <div class="flex flex-1 flex-col items-center justify-center gap-8">
        <h1
            class="whitespace-pre-wrap text-center text-2xl font-bold text-primary md:px-20"
        >
            The page you are looking for is available only to a limited number
            of testers. Sign up for the pre-order to request the access!
        </h1>
        <form
            on:submit|preventDefault={submit}
            class="md:join max-md:flex max-md:flex-col max-md:gap-2"
        >
            <Honeypot honeypot={$page?.props?.honeypot} {form} />
            <span class="form-control">
                <input
                    type="text"
                    id="name"
                    name="name"
                    bind:value={$form.name}
                    class="input join-item input-bordered input-ghost"
                    class:input-error={$form.errors.name}
                    placeholder="Enter Full Name"
                />
                {#if $form.errors.name}
                    <span class="label-text-alt mt-1 text-left text-error">
                        {$form.errors.name}
                    </span>
                {/if}
            </span>
            <span class="form-control">
                <input
                    type="email"
                    id="email"
                    name="email"
                    bind:value={$form.email}
                    class="input join-item input-bordered input-ghost"
                    class:input-error={$form.errors.email}
                    placeholder="Enter Email Address"
                    required
                />
                {#if $form.errors.email}
                    <span class="label-text-alt mt-1 text-left text-error">
                        {$form.errors.email}
                    </span>
                {/if}
            </span>
            <button
                type="submit"
                class="btn btn-secondary join-item rounded-full"
                disabled={$form.processing}>Submit</button
            >
        </form>

        <a
            href="/"
            use:inertia
            class="btn btn-primary btn-outline rounded-full lg:btn-lg"
            >Back to Home</a
        >
    </div>
</div>
