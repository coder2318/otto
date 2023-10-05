<script lang="ts">
    import plate1 from '@/assets/img/index-plates-1.png'
    import plate2 from '@/assets/img/index-plates-2.png'
    import plate3 from '@/assets/img/index-plates-3.png'
    import plate4 from '@/assets/img/index-plates-4.png'
    import background from '@/assets/img/index-plates-bg.svg'
    import { faFaceSmile, faGift, faImage, faLock } from '@fortawesome/free-solid-svg-icons'
    import Fa from 'svelte-fa'
</script>

<section class="flex items-center justify-center px-4 py-16">
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
            <div class="card-body relative gap-6 pt-12">
                <div class="badge badge-neutral absolute -top-8 h-16 w-16 text-3xl text-success">
                    <Fa icon={faLock} class="brightness-75" />
                </div>
                <h2 class="card-title font-serif text-2xl font-normal italic xl:text-3xl">
                    100% Secure & Confidential
                </h2>
                <p>
                    Your memories are safe with us, as OttoStory guarantees complete security and confidentiality of
                    your personal narratives and information
                </p>
            </div>
        </div>
        <div class="card order-3 mt-auto lg:row-span-3">
            <figure>
                <img class="w-full rounded-2xl" src={plate2} alt="" />
            </figure>
        </div>
        <div class="card order-4 bg-secondary text-secondary-content lg:row-span-3">
            <div class="card-body relative gap-6 pt-12">
                <div class="badge badge-neutral absolute -top-8 h-16 w-16 text-3xl text-secondary">
                    <Fa icon={faImage} class="brightness-75" />
                </div>
                <h2 class="card-title font-serif text-2xl font-normal italic xl:text-4xl">Photo Inclusion</h2>
                <p>Enrich your narrative with personal images, adding a vibrant visual element to your memoir.</p>
            </div>
        </div>
        <div class="card order-5 sm:order-6 lg:row-span-3">
            <figure>
                <img class="w-full rounded-2xl" src={plate3} alt="" />
            </figure>
        </div>
        <div class="card order-6 bg-primary text-primary-content sm:order-5 lg:row-span-3">
            <div class="card-body relative gap-6 pt-12">
                <div class="badge badge-neutral absolute -top-8 h-16 w-16 text-3xl text-primary/75">
                    <Fa icon={faFaceSmile} />
                </div>
                <h2 class="card-title font-serif text-2xl font-normal italic xl:text-4xl">Emotion Recognition</h2>
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
            <div class="card-body relative gap-6 pt-12">
                <div class="badge badge-neutral absolute -top-8 h-16 w-16 text-3xl text-accent">
                    <Fa icon={faGift} class="brightness-75" />
                </div>
                <h2 class="card-title font-serif text-2xl font-normal italic xl:text-4xl">
                    Give the Gift of Your Story
                </h2>
                <p>Share the joy of storytelling by gifting an OttoStory experience to your loved ones.</p>
            </div>
        </div>
    </div>
</section>
