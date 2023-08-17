<script lang="ts">
    import { useForm, page } from '@inertiajs/svelte'
    import image from '@/assets/img/contact.jpeg'
    import { addHoneypot, type Honeypot } from '@/service/honeypot'

    $: honeypot = $page?.props?.honeypot as Honeypot

    const form = useForm(
        addHoneypot(honeypot)({
            name: '',
            email: '',
        })
    )

    function submit() {
        $form.post('/preorder', { preserveScroll: true })
    }
</script>

<section class="flex items-center justify-center py-16">
    <div
        class="container grid items-stretch justify-center gap-20 p-4 md:grid-cols-2"
    >
        <figure class="rounded-xl">
            <img
                src={image}
                alt="contact"
                class=" h-full w-full rounded-xl object-cover"
            />
        </figure>
        <div class="card rounded-xl bg-neutral">
            <form
                class="card-body justify-center gap-4"
                on:submit|preventDefault={submit}
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
                <h1 class="text-4xl text-primary">
                    Sign Up for <i>Preorder</i>
                </h1>
                <div>
                    Be the first to get your hands on our exciting new product!
                    Sign up for our pre-order section and secure your spot to
                    receive exclusive offers and early access.
                </div>
                <div class="form-control">
                    <label class="label" for="name">
                        <span class="label-text">Full Name</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        bind:value={$form.name}
                        class="input input-bordered input-ghost"
                        class:input-error={$form.errors.name}
                        placeholder="Enter Full Name"
                    />
                    {#if $form.errors.name}
                        <span class="label-text-alt mt-1 text-left text-error">
                            {$form.errors.name}
                        </span>
                    {/if}
                </div>
                <div class="form-control">
                    <label class="label" for="email">
                        <span class="label-text">Email Address*</span>
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        bind:value={$form.email}
                        class="input input-bordered input-ghost"
                        class:input-error={$form.errors.email}
                        placeholder="Enter Email Address"
                        required
                    />
                    {#if $form.errors.email}
                        <span class="label-text-alt mt-1 text-left text-error">
                            {$form.errors.email}
                        </span>
                    {/if}
                </div>
                <div class="card-actions justify-end">
                    <button
                        type="submit"
                        class="btn btn-secondary btn-lg rounded-full"
                        disabled={$form.processing}>Submit</button
                    >
                </div>
            </form>
        </div>
    </div>
</section>
