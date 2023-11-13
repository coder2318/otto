<script lang="ts">
    import bannerIllustration from '@/assets/img/profile-illustration.svg'
    import recordBannerIllustration from '@/assets/img/create-otto-story-illustration.svg'
    export let title
</script>

<section class="chapterNameBanner">
    <div class="otto-container">
        <div class="wrapper">
            <img class="chapterNameBanner-figure" src={bannerIllustration} alt="Figure" />
            <div class="block">
                <img class="chapterNameBanner-illustration" src={recordBannerIllustration} alt="Illustartion" />
                <div class="wrap">
                    <span class="font-serif">My OttoStory</span>
                    <h2 class="fz_h3 title">
                        {title}
                    </h2>
                </div>
            </div>
        </div>
    </div>
</section>

<style lang="scss">
    .chapterNameBanner {
        margin: 20px 0;

        .title {
            color: #0c345c;
            line-height: 1;
        }
        .wrapper {
            position: relative;
        }
        .wrap {
            display: flex;
            flex-direction: column;
            align-items: flex-start;

            @media (max-width: 991px) {
                flex-direction: column;
            }
        }
        .block {
            background: #ffbe33;
            border-radius: 24px;
            overflow: hidden;
            padding: 35px 50px;
            position: relative;

            @media (max-width: 767px) {
                padding: 20px;
            }
        }

        &-figure {
            position: absolute;
            width: 400px;
            height: auto;
            top: -120px;
            left: -100px;
        }

        &-illustration {
            position: absolute;
            left: 0;
            top: 0;
        }
        span {
            font-size: 32px;
            color: #0c345c;
            position: relative;
            z-index: 2;
            margin-right: 24px;
            white-space: nowrap;
            // padding-top: 2px;
            @media (max-width: 991px) {
                font-size: 24px;
                padding-top: 0px;
            }
        }
    }
</style>
