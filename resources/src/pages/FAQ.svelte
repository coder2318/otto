<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import titleWithIllustration from '@/assets/img/title-with-illustration.svg'
    export const layout = [Base]
</script>

<script lang="ts">
    import Navbar from '@/components/Static/Navbar.svelte'
    import Footer from '@/components/Static/Footer.svelte'
    import faqArrow from '@/assets/img/faq-arrow.svg'

    const questions = [
        {
            question: 'Lorem Ipsum is simply dummy text of the printing?',
            answer: `It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.`,
        },
        {
            question: 'Lorem Ipsum is simply dummy text of the printing?',
            answer: `It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.`,
        },
        {
            question: 'Lorem Ipsum is simply dummy text of the printing?',
            answer: `It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.`,
        },
        {
            question: 'Lorem Ipsum is simply dummy text of the printing?',
            answer: `It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.`,
        },
        {
            question: 'Lorem Ipsum is simply dummy text of the printing?',
            answer: `It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.`,
        },
    ]
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - FAQ</title>
</svelte:head>

<Navbar class="relative bg-primary text-primary-content" />

<main class="faq">
    <div class="otto-container">
        <div class="titleWithIllustration">
            <div class="wrap">
                <h1 class="fz_h2 title text-primary">
                    Frequently Asked <i>Questions</i>
                </h1>
                <img src={titleWithIllustration} alt="Illustration" />
            </div>
        </div>

        {#each questions as { question, answer }}
            <div class="collapse-arrow group collapse rounded-xl">
                <input type="radio" name="faq-accordion" class="peer" />

                <div class="collapse-title bg-neutral-focus font-serif text-primary peer-checked:bg-neutral">
                    {question}
                    <img class="arrow" src={faqArrow} alt="Illustration" />
                </div>

                <div class="collapse-content bg-neutral-focus peer-checked:bg-neutral">
                    <p>
                        {answer}
                    </p>
                </div>
            </div>
        {/each}
    </div>
</main>

<Footer />

<style lang="scss">
    .faq {
        padding: 124px 0 80px;

        .collapse[open].collapse-arrow > .collapse-title .arrow,
        .collapse-open.collapse-arrow > .collapse-title .arrow,
        .collapse-arrow:focus:not(.collapse-close) > .collapse-title .arrow,
        .collapse-arrow:not(.collapse-close) > input[type='checkbox']:checked ~ .collapse-title .arrow,
        .collapse-arrow:not(.collapse-close) > input[type='radio']:checked ~ .collapse-title .arrow {
            transform: rotate(180deg);
        }

        .collapse-arrow {
            margin-bottom: 24px;

            &:last-child {
                margin-bottom: 0;
            }
        }

        .collapse-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 30px;
            font-size: 36px;
            line-height: 1.1;

            &:after {
                display: none;
            }

            .arrow {
                margin-left: 15px;
            }

            .bg-neutral {
                img {
                    transform: rotate(180deg);
                }
            }
        }

        .collapse-content {
            color: #4d4d4d;
            font-size: 18px;
            padding-left: 30px;
            padding-right: 30px;

            p {
                padding-bottom: 10px;
            }
        }
    }

    @media (max-width: 1280px) {
        .faq {
            .collapse-title {
                font-size: 28px;
                padding: 20px;
            }

            .collapse-content {
                padding-left: 20px;
                padding-right: 20px;
            }
        }
    }

    @media (max-width: 767px) {
        .faq {
            .collapse-title {
                font-size: 24px;
            }
        }
    }
</style>
