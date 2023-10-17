<script lang="ts">
    import womanIllustration from '@/assets/img/woman-illustration.svg'
    import manIllustration from '@/assets/img/man-illustration.svg'
    import whiteOrangeFigure from '@/assets/img/white-orange-figure.svg'
    import yellowWhiteFigure from '@/assets/img/yellow-white-figure.svg'
</script>

<section class="focusSection flex flex-col">
    <div class="container">
        <div class="blockFocus relative flex flex-1 rounded-3xl bg-base-200">
            <slot />
            <img class="womanIllustration" src={womanIllustration} alt="Illustration" />
            <img class="manIllustration" src={manIllustration} alt="Illustration" />
            <img class="whiteOrangeFigure" src={whiteOrangeFigure} alt="Figure" />
            <img class="yellowWhiteFigure" src={yellowWhiteFigure} alt="Figure" />
        </div>
    </div>
</section>

<style lang="scss">
    .focusSection {
        .container {
            margin: 0 auto;
            width: 100%;
            max-width: 100%;
        }
        .blockFocus {
            min-height: 950px;
            padding: 20px;
            @media (max-width: 1280px) {
                min-height: 800px;
            }
            @media (max-width: 767px) {
                min-height: auto;
                padding: 160px 20px;
            }
        }
        .womanIllustration {
            position: absolute;
            left: 130px;
            top: -110px;
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
            right: 75px;
            bottom: -95px;
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
            bottom: -15px;
            left: -133px;
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
            top: 60px;
            right: 20px;
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
