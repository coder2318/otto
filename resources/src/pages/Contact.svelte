<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    export const layout = [Base]
</script>

<script lang="ts">
    import { useForm, page } from '@inertiajs/svelte'
    import Navbar from '@/components/Static/Navbar.svelte'
    import Footer from '@/components/Static/Footer.svelte'
    import Phone from '@/components/SVG/contact-phone.svg.svelte'
    import Mail from '@/components/SVG/contact-mail.svg.svelte'
    import Location from '@/components/SVG/contact-location.svg.svelte'
    import img from '@/assets/img/contact-form-img.jpg'
    import splash from '@/assets/img/splash-contact-form.svg'
    import Honeypot from '@/components/Honeypot.svelte'
    import { addHoneypot } from '@/service/honeypot'
    import titleWithIllustration from '@/assets/img/title-with-illustration.svg'
    import { imask } from '@imask/svelte'

    const options = {
        mask: '+{1}(000)000-000',
        lazy: false,
    }

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

<Navbar class="relative bg-primary text-primary-content" />

<section class="getTouch">
    <div class="otto-container">
        <div class="titleWithIllustration">
            <div class="wrap">
                <h1 class="fz_h2 title text-primary">
                    Get in <i>Touch</i>
                </h1>
                <img src={titleWithIllustration} alt="Illustration" />
            </div>
        </div>

        <div class="wrap">
            <div class="imgBlockWithIllustration">
                <div class="blockForImage">
                    <img src={img} alt="" class="h-auto w-full" />
                </div>
                <img class="illustration" src={splash} alt="Illustration" />
            </div>

            <form class="getTouch-form" on:submit|preventDefault={submit}>
                <Honeypot honeypot={$page?.props?.honeypot} {form} />

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
                        use:imask={options}
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

                <button type="submit" class="btn btn-primary btn-lg rounded-full" disabled={$form.processing}>
                    Send Message
                </button>
            </form>
        </div>
    </div>
</section>

<section class="sContacts">
    <div class="otto-container">
        <div class="wrap">
            <a class="sContacts__card bg-neutral" href="tel:+444123456789">
                <div class="sContacts__card_icon">
                    <Phone />
                </div>

                <div class="sContacts__card_content">
                    <span class="font-serif">Call Us Today</span>
                    <p>+444 123 456 789</p>
                </div>
            </a>

            <a class="sContacts__card bg-neutral" href="mailto:info@ottostory.com">
                <div class="sContacts__card_icon">
                    <Mail />
                </div>
                <div class="sContacts__card_content">
                    <span class="font-serif">Email Us On</span>
                    <p>info@ottostory.com</p>
                </div>
            </a>

            <div class="sContacts__card bg-neutral">
                <div class="sContacts__card_icon">
                    <Location />
                </div>
                <div class="sContacts__card_content">
                    <span>Location</span>
                    <p>New York, USA - 100001</p>
                </div>
            </div>
        </div>
    </div>
</section>

<Footer />

<style lang="scss">
    .getTouch {
        padding: 120px 0 120px;

        .otto-container {
            max-width: 1200px;
        }

        .titleWithIllustration {
            margin-bottom: 100px;
        }

        .wrap {
            display: flex;
            align-items: center;
        }

        .imgBlockWithIllustration {
            margin-right: 100px;
            .illustration {
                position: absolute;
                min-width: 132%;
                top: -16%;
                left: -18%;
            }
        }

        .blockForImage {
            width: 100%;
            min-width: 474px;
            max-width: 474px;
            min-height: 565px;
        }

        &-form {
            width: 100%;
            max-width: 516px;
            position: relative;
            z-index: 10;

            .form-control {
                margin-bottom: 24px;
                position: relative;
            }

            .text-error {
                bottom: -18px;
                left: 10px;
                position: absolute;
                font-size: 12px;
            }

            input {
                font-size: 16px;
                height: 60px;
                &:focus,
                &:active {
                    outline: none;
                }
            }

            textarea {
                &:focus,
                &:active {
                    outline: none;
                }
            }

            .btn {
                display: flex;
                min-height: auto;
                height: 48px;
                padding: 0 32px;
                font-size: 16px;
                border: none;
                margin-top: 36px;
            }
        }
    }

    .sContacts {
        position: relative;
        padding-bottom: 56px;

        .otto-container {
            max-width: 1200px;
        }

        .wrap {
            display: flex;
            flex-wrap: wrap;
            margin-right: -24px;
        }

        &__card {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex-basis: calc(100% / 3 - 24px);
            border-radius: 16px;
            margin-right: 24px;
            margin-bottom: 24px;
            transition: 0.3s;
            padding: 0 40px 40px;

            &_content {
                display: flex;
                flex-direction: column;
                text-align: center;

                span {
                    font-size: 24px;
                    color: #0c345c;
                    line-height: 1.6;
                    display: block;
                    margin-bottom: 3px;
                }

                p {
                    font-size: 18px;
                    line-height: 1.6;
                    color: #0c345c;
                    font-weight: 500;
                }
            }

            &_icon {
                width: 216px;
                height: 216px;
                margin-bottom: 10px;
            }

            &:hover {
                transform: scale(1.05);
            }
        }
    }

    @media (max-width: 991px) {
        .getTouch {
            .wrap {
                flex-direction: column;
            }
            .imgBlockWithIllustration {
                margin-right: 0;
                margin-bottom: 80px;

                .illustration {
                    min-width: 123%;
                    top: -6%;
                    left: -18%;
                }
            }
        }

        .sContacts {
            &__card {
                flex-basis: calc(100% / 2 - 24px);
            }
        }
    }

    @media (max-width: 767px) {
        .getTouch {
            .imgBlockWithIllustration {
                width: 100%;
            }
            .blockForImage {
                min-width: auto;
                width: 100%;
                min-height: auto;
                aspect-ratio: 16/20;
            }
        }

        .sContacts {
            &__card {
                flex-basis: calc(100%);
            }
        }
    }
</style>
