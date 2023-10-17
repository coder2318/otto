<script lang="ts">
    import plate1 from '@/assets/img/index-plates-1.png'
    import plate2 from '@/assets/img/index-plates-2.png'
    import plate3 from '@/assets/img/index-plates-3.png'
    import plate4 from '@/assets/img/index-plates-4.png'
    import background from '@/assets/img/index-plates-bg.svg'
    import { faFaceSmile, faGift, faImage, faLock } from '@fortawesome/free-solid-svg-icons'
    import Fa from 'svelte-fa'
    import emotion from '@/assets/img/section-grid-cards/emotion.svg'
    import gift from '@/assets/img/section-grid-cards/gift.svg'
    import photo from '@/assets/img/section-grid-cards/photo.svg'
    import secure from '@/assets/img/section-grid-cards/secure.svg'
</script>

<section class="section-grid-cards flex items-center justify-center pb-40 pt-44">
    <div
        class="container mx-auto grid grid-cols-1 items-stretch justify-center gap-4 bg-contain bg-center bg-no-repeat sm:grid-cols-2 lg:grid-flow-col lg:grid-cols-4 lg:grid-rows-6"
        style="background-image: url({background})"
    >
        <div class="card order-1 sm:order-2 lg:row-span-2 lg:ml-auto lg:w-1/2 lg:pb-[50%]">
            <figure>
                <img class="w-full rounded-2xl" src={plate1} alt="" />
            </figure>
        </div>

        <div class="card order-2 bg-success text-success-content sm:order-1 lg:row-span-4 lg:mt-[100%]">
            <div class="card-body relative gap-6 pb-12 pt-20">
                <div class="iconBlock badge badge-neutral absolute">
                    <img src={secure} alt="iconQuote" />
                </div>
                <h2 class="fz_h3 title font-serif font-normal">100% <i> Secure &</i> <i>Confidential</i></h2>
                <p>
                    Your memories are safe with us, as OttoStory guarantees complete security and confidentiality of
                    your personal narratives and information
                </p>
            </div>
        </div>

        <div class="firstBigImage card order-3 mt-auto lg:row-span-3">
            <figure>
                <img class="w-full rounded-2xl" src={plate2} alt="" />
            </figure>
        </div>

        <div class="yellowCard card order-4 bg-secondary text-secondary-content lg:row-span-3">
            <div class="card-body relative gap-6 pb-12 pt-20">
                <div class="iconBlock badge badge-neutral absolute">
                    <img src={photo} alt="iconPhoto" />
                </div>
                <h2 class="fz_h3 title font-serif font-normal">
                    Photo
                    <i>Inclusion</i>
                </h2>
                <p>Enrich your narrative with personal images, adding a vibrant visual element to your memoir.</p>
            </div>
        </div>
        <div class="card order-5 sm:order-6 lg:row-span-3">
            <figure>
                <img class="w-full rounded-2xl" src={plate3} alt="" />
            </figure>
        </div>
        <div class="blueCard card order-6 bg-primary text-primary-content sm:order-5 lg:row-span-3">
            <div class="card-body relative gap-6 pb-12 pt-20">
                <div class="iconBlock badge badge-neutral absolute">
                    <img src={emotion} alt="iconPhoto" />
                </div>
                <h2 class="fz_h3 title font-serif font-normal">
                    Emotion
                    <i>Recognition</i>
                </h2>
                <p>
                    Engage with thought-provoking prompts crafted by psychologists, while Otto, sensitive to your
                    emotional tone, ensures your storytelling remains authentic and deeply resonant.
                </p>
            </div>
        </div>
        <div class="card order-7 lg:row-span-2 lg:mt-auto lg:w-1/2">
            <figure>
                <img class="w-full rounded-2xl" src={plate4} alt="" />
            </figure>
        </div>
        <div class="card order-8 bg-accent text-accent-content lg:row-span-4 lg:mb-[100%]">
            <div class="card-body relative gap-6 pb-12 pt-20">
                <div class="iconBlock badge badge-neutral absolute">
                    <img src={gift} alt="iconPhoto" />
                </div>
                <h2 class="fz_h3 title font-serif font-normal">Give the Gift of <i>Your Story</i></h2>
                <p>Share the joy of storytelling by gifting an OttoStory experience to your loved ones.</p>
            </div>
        </div>
    </div>
</section>

<style lang="scss">
    .section-grid-cards {
        font-size: 1.25rem;

        .container {
            max-width: calc(100%);
        }

        .title {
            color: #0f2e52;
        }

        .iconBlock {
            position: absolute;
            width: 84px;
            height: 84px;
            border-radius: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            top: 0;
            transform: translateY(-50%);
        }

        .title {
            color: #0f2e52;
        }

        p {
            color: #0f2e52;
        }

        .firstBigImage,
        .yellowCard {
            top: -102px;
        }

        .blueCard {
            color: #fff;
            p {
                color: #f1ede7;
            }
            .title {
                color: #fff;
            }
        }
    }
    @media (max-width: 1023px) {
        .section-grid-cards {
            .yellowCard {
                top: 0;
            }
            .firstBigImage {
                top: 0;
            }
        }
    }
    @media (max-width: 991px) {
        .section-grid-cards {
            padding-top: 120px;
        }
    }
</style>
