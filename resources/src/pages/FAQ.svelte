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
            question: 'What is OttoStory?',
            answer: `OttoStory is a modern AI application crafted to create a personalized, verified narrative of yours or your family's life story.\n\nThrough a set of well-thought-out questions and prompts, it captures your spoken or typed responses and shapes them into a well-structured, articulate story.  OttoStory ensures that the narrative is factual and authentic, backed by your personal review.  `,
        },
        {
            question: 'How do I get started with OttoStory?',
            answer: `Kickstart your OttoStory journey by going to <a href="https://ottostory.com">ottostory.com</a> and going through the registration. You'll then go through a detailed setup process to tailor your preferences and start recording your family's legacy today.`,
        },
        {
            question: 'How does OttoStory craft my autobiography?',
            answer: `OttoStory uses a blend of intuitive questions and cutting-edge machine learning to curate your life's tale. After your review and approval, the content gets compiled into your evolving book. Once done, we send over a deluxe hardcover edition straight to your doorstep. No charge for shipping.`,
        },
        {
            question: 'Can I add multimedia to my autobiography?',
            answer: `Yes, OttoStory allows the inclusion of photographs, adding a layer of richness to your narrative.`,
        },
        {
            question: 'Is my data secure with OttoStory?',
            answer: `Your data security is our priority. OttoStory employs strong encryption methods to keep all your data completely secured.`,
        },
        {
            question: 'How long does it take to complete my autobiography?',
            answer: `Timelines can vary, but on average, users complete their 200-page autobiography within 9 months. The platform has no time constraints, allowing you to work at your own pace.`,
        },
        {
            question: 'Can OttoStory be a gift?',
            answer: `Absolutely! OttoStory makes an exceptional gift for milestone birthdays, anniversaries, or retirements, helping your loved ones document their lifetime of stories in a meaningful way.`,
        },
        {
            question: 'What is the delivery time for the physical book?',
            answer: `Upon finalization of your autobiography, expect the high-quality hardcover version at your doorstep within about 10-14 days.`,
        },
        {
            question: 'How many questions does OttoStory offer?',
            answer: `OttoStory provides a broad range of questions covering various topics such as love, relationships, school, and education. We've collaborated with cognitive behavior therapists, professional authors, and publishers to ensure our questions are well-structured for quality storytelling.\n\nAdditionally, you have the option to create your own questions, allowing you to explore any aspect of your life in your OttoStory.`,
        },
        {
            question: 'Can friends contribute to my OttoStory?',
            answer: `Yes, you can invite friends to formulate and answer questions about you, adding a communal and multifaceted dimension to your autobiography.`,
        },
        {
            question: 'Does OttoStory offer multilingual support?',
            answer: `OttoStory is multilingual, catering to a global audience.`,
        },
        {
            question: `What if I encounter issues while using OttoStory?`,
            answer: `We have a comprehensive customer support infrastructure featuring in-app FAQs, real-time chat, and a 48-hour email response policy.`,
        },
        {
            question: 'Is post-completion editing possible?',
            answer: `Yes, you can edit your OttoStory anytime before sending it off for the printing process.`,
        },
        {
            question: 'What sets OttoStory apart from other autobiography services?',
            answer: `The distinct feature of OttoStory lies in its unique machine learning system, well-considered questions, and reliable support. Our platform, developed in conjunction with experts, ensures a personalized and verified recounting of your life story, setting it apart from other services.`,
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
            <div class="group collapse collapse-arrow rounded-xl">
                <input type="radio" name="faq-accordion" class="peer" />

                <div class="collapse-title bg-neutral-focus font-serif text-primary peer-checked:bg-neutral">
                    {question}
                    <img class="arrow" src={faqArrow} alt="Illustration" />
                </div>

                <div class="collapse-content bg-neutral-focus peer-checked:bg-neutral">
                    <p class="whitespace-pre-line">
                        {@html answer}
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
