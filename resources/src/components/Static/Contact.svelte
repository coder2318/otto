<script lang="ts">
    import { useForm, page } from '@inertiajs/svelte'
    import image from '@/assets/img/contact.jpeg'
    import { addHoneypot } from '@/service/honeypot'
    import Honeypot from '../Honeypot.svelte'

    import contactIllustration from '@/assets/img/contact-illustration.svg'
    import contactIllustration2 from '@/assets/img/contact-illustration-2.svg'

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

<section class="contact flex items-center justify-center pb-24 pt-44">
    <div class="container">
        <div class="wrap flex items-stretch justify-center">
            <img class="contact-illustration" src={contactIllustration} alt="Illustration" />
            <div class="contact__image">
                <img src={image} alt="contact" />
            </div>
            <div class="card rounded-xl bg-neutral">
                <form class="form justify-center" on:submit|preventDefault={submit}>
                    <Honeypot honeypot={$page?.props?.honeypot} {form} />
                    <div class="card-header">
                        <img class="contact-illustration-small" src={contactIllustration2} alt="Illustration" />
                        <h2 class="fz_h2 title relative z-10 text-primary">
                            Sign Up for <i>Preorder</i>
                        </h2>
                    </div>
                    <p class="g-text">
                        Be the first to get your hands on our exciting new product! Sign up for our pre-order section
                        and secure your spot to receive exclusive offers and early access.
                    </p>
                    <div class="form-control">
                        <label class="label" for="name">
                            <span class="label-text">Full Name</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            bind:value={$form.name}
                            class="input"
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
                            class="input"
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
                    <div class="card-actions mt-8">
                        <button
                            type="submit"
                            class="btn btn-secondary h-16 rounded-full px-10 text-xl"
                            disabled={$form.processing}>Submit</button
                        >
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<style lang="scss">
    .contact {
        overflow: hidden;
        .wrap {
            position: relative;
        }
        &-illustration {
            position: absolute;
            left: -84px;
            top: -50px;
        }
        .card {
            position: relative;
            overflow: hidden;
            padding: 60px 40px 40px;
            &-header {
                position: relative;
                margin-bottom: 30px;
            }
        }
        &-illustration-small {
            position: absolute;
            left: -15px;
            top: -19px;
        }
        &__image {
            width: 100%;
            min-width: 460px;
            max-width: 540px;
            margin-right: 60px;
            border-radius: 12px;
            height: auto;
            position: relative;
            overflow: hidden;
            min-height: 624px;

            img {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
        }

        .g-text {
            margin-bottom: 32px;
        }

        .form {
            .form-control {
                margin-bottom: 24px;
            }
            label {
                padding: 0;
                margin-bottom: 4px;

                span {
                    font-size: 16px;
                    color: #333333;
                    font-weight: 500;
                }
            }
            input {
                height: 60px;
                background: transparent;
                border: 1px solid #999999;
                color: #999999;
                border-radius: 10px;
                font-size: 16px;

                &:focus {
                    outline: none;
                }
            }
            .btn {
                min-width: 148px;
                line-height: 1;
            }
        }
    }
    @media (max-width: 1280px) {
        .contact {
            &__image {
                margin-right: 30px;
            }
        }
    }
    @media (max-width: 991px) {
        .contact {
            .wrap {
                flex-direction: column;
            }
            &__image {
                margin-right: 0px;
                margin-bottom: 30px;
                max-width: 100%;
                min-width: auto;
                min-height: auto;
                aspect-ratio: 16/20;
            }
            .card {
                padding: 60px 20px 40px;
            }
        }
    }
</style>
