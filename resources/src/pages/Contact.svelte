<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    export const layout = [Base]
</script>

<script lang="ts">
    import { useForm, page } from '@inertiajs/svelte'
    import Navbar from '@/components/Static/Navbar.svelte'
    import Footer from '@/components/Static/Footer.svelte'
    import PageHeader from '@/components/Static/PageHeader.svelte'
    import Phone from '@/components/SVG/contact-phone.svg.svelte'
    import Mail from '@/components/SVG/contact-mail.svg.svelte'
    import Location from '@/components/SVG/contact-location.svg.svelte'
    import img from '@/assets/img/contact-form-img.jpg'
    import splash from '@/assets/img/splash-contact-form.svg'
    import Honeypot from '@/components/Honeypot.svelte'
    import { addHoneypot } from '@/service/honeypot'

    const form = useForm(
        addHoneypot($page?.props?.honeypot)({
            name: '',
            email: '',
            phone: '',
            message: '',
        })
    )

    function submit() {
        $form.post(window.location.pathname, {
            preserveScroll: true,
            onSuccess: () => {
                $form.reset()
            },
        })
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Contact</title>
</svelte:head>

<Navbar class="bg-primary text-primary-content" />

<PageHeader class="mb-16 mt-36 lg:mb-24 lg:mt-48">
    Get in <i>Touch</i>
</PageHeader>

<section
    class="container mx-auto mb-16 grid grid-cols-1 items-center justify-center gap-4 px-4 md:grid-cols-2"
>
    <div
        class="card bg-contain bg-center bg-no-repeat p-16"
        style="background-image: url({splash})"
    >
        <figure class="rounded-xl">
            <img src={img} alt="" class="h-auto w-full" />
        </figure>
    </div>

    <form class="card w-full" on:submit|preventDefault={submit}>
        <Honeypot honeypot={$page?.props?.honeypot} {form} />
        <div class="card-body gap-4">
            <div class="form-control">
                <input
                    type="text"
                    name="name"
                    placeholder="Full Name"
                    class:input-error={$form.errors.name}
                    class="input input-bordered input-ghost input-lg"
                    bind:value={$form.name}
                />
                {#if $form.errors.name}
                    <span class="label-text-alt mt-1 text-left text-error">
                        {$form.errors.name}
                    </span>
                {/if}
            </div>
            <div class="form-control">
                <input
                    type="email"
                    name="email"
                    placeholder="Email Address"
                    class:input-error={$form.errors.email}
                    class="input input-bordered input-ghost input-lg"
                    bind:value={$form.email}
                />
                {#if $form.errors.email}
                    <span class="label-text-alt mt-1 text-left text-error">
                        {$form.errors.email}
                    </span>
                {/if}
            </div>

            <div class="form-control">
                <input
                    type="tel"
                    name="phone"
                    placeholder="Phone Number"
                    class:input-error={$form.errors.phone}
                    class="input input-bordered input-ghost input-lg"
                    bind:value={$form.phone}
                />
                {#if $form.errors.phone}
                    <span class="label-text-alt mt-1 text-left text-error">
                        {$form.errors.phone}
                    </span>
                {/if}
            </div>

            <div class="form-control">
                <textarea
                    name="message"
                    placeholder="Message"
                    rows="5"
                    class:textarea-error={$form.errors.message}
                    class="textarea textarea-bordered textarea-ghost textarea-lg"
                    bind:value={$form.message}
                />
                {#if $form.errors.message}
                    <span class="label-text-alt mt-1 text-left text-error">
                        {$form.errors.message}
                    </span>
                {/if}
            </div>

            <div class="card-actions">
                <button
                    type="submit"
                    class="btn btn-primary btn-lg rounded-full px-8"
                    disabled={$form.processing}
                >
                    Send Message
                </button>
            </div>
        </div>
    </form>
</section>

<section
    class="container mx-auto mb-16 grid grid-cols-1 items-center justify-center gap-8 px-4 md:grid-cols-3"
>
    <a
        class="card bg-neutral transition-transform hover:scale-105"
        href="tel:+444123456789"
    >
        <figure>
            <Phone />
        </figure>
        <div class="card-body items-center pt-0 text-primary">
            <div class="card-title font-serif font-normal">Call Us Today</div>
            <p>+444 123 456 789</p>
        </div>
    </a>

    <a
        class="card bg-neutral transition-transform hover:scale-105"
        href="mailto:info@ottostory.com"
    >
        <figure>
            <Mail />
        </figure>
        <div class="card-body items-center pt-0 text-primary">
            <div class="card-title font-serif font-normal">Email Us On</div>
            <p>info@ottostory.com</p>
        </div>
    </a>

    <div class="card bg-neutral transition-transform hover:scale-105">
        <figure>
            <Location />
        </figure>
        <div class="card-body items-center pt-0 text-primary">
            <div class="card-title font-serif font-normal">Location</div>
            <p>New York, USA - 100001</p>
        </div>
    </div>
</section>

<Footer />
