<script lang="ts">
    import womanIllustration from '@/assets/img/woman-illustration.png'
    import manIllustration from '@/assets/img/man-illustration.png'
    import whiteOrangeFigure from '@/assets/img/white-orange-figure.svg'
    import yellowWhiteFigure from '@/assets/img/yellow-white-figure.svg'
</script>

<section class="regSection flex flex-col">
    <div class="otto-container flex flex-1 flex-col">
        <div class="relative flex flex-1 rounded-3xl bg-base-200 p-4 pt-16 md:pt-8">
            <slot />
            <img class="womanIllustration" src={womanIllustration} alt="Illustration" />
            <img class="manIllustration" src={manIllustration} alt="Illustration" />
            <img class="whiteOrangeFigure" src={whiteOrangeFigure} alt="Figure" />
            <img class="yellowWhiteFigure" src={yellowWhiteFigure} alt="Figure" />
        </div>
    </div>
</section>

<style lang="scss">
    .regSection {
        padding: 70px 50px;
        min-height: 100vh;

        @media (max-width: 1280px) {
            padding: 40px 20px;
        }

        .otto-container {
            margin: 0 auto;
            width: 100%;
            max-width: 100%;
            height: inherit;
            padding: 0;
        }
        .womanIllustration {
            position: absolute;
            left: -30px;
            top: -52px;
            width: 175px;
            @media (max-width: 1280px) {
                left: 50px;
                top: -80px;
                max-width: 200px;
            }
            @media (max-width: 767px) {
                left: 20px;
                top: -50px;
                max-width: 100px;
            }
        }
        .manIllustration {
            position: absolute;
            right: -21px;
            bottom: -37px;
            @media (max-width: 1280px) {
                right: 45px;
                bottom: -45px;
                max-width: 200px;
            }
            @media (max-width: 767px) {
                right: 20px;
                bottom: -25px;
                max-width: 100px;
            }
        }
        .whiteOrangeFigure {
            position: absolute;
            bottom: 4px;
            left: 0px;
            width: 175px;
            @media (max-width: 1280px) {
                left: -83px;
                bottom: -15px;
                max-width: 240px;
            }
            @media (max-width: 767px) {
                left: -46px;
                max-width: 120px;
            }
        }
        .yellowWhiteFigure {
            position: absolute;
            top: 20px;
            right: 10px;
            width: 240px;
            @media (max-width: 1280px) {
                top: 30px;
                right: 10px;
                max-width: 210px;
            }
            @media (max-width: 767px) {
                top: 10px;
                right: 5px;
                max-width: 110px;
            }
        }
    }
</style>
