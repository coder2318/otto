<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    export const layout = [Base]
</script>

<script lang="ts">
    import { inertia } from '@inertiajs/svelte'
    import Navbar from '@/components/Static/Navbar.svelte'
    import PageHeader from '@/components/Static/PageHeader.svelte'
    import Footer from '@/components/Static/Footer.svelte'
    import img from '@/assets/img/about-img.jpg'
    import splash from '@/assets/img/about-splash.svg'
    import imgTeam1 from '@/assets/img/team-1.jpg'
    import imgTeam2 from '@/assets/img/team-2.jpg'
    import imgTeam3 from '@/assets/img/team-3.jpg'
    import titleWithIllustration from '@/assets/img/title-with-illustration.svg'
    import whiteOrangeFigure from '@/assets/img/white-orange-figure.svg'
    import yellowWhiteFigure from '@/assets/img/yellow-white-figure.svg'
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - About</title>
</svelte:head>

<Navbar class="relative bg-primary text-primary-content" />

<section class="aboutUs">
    <div class="otto-container">
        <div class="titleWithIllustration">
            <div class="wrap">
                <h1 class="fz_h2 title text-primary">
                    About <i>Us</i>
                </h1>
                <img src={titleWithIllustration} alt="Illustration" />
            </div>
        </div>

        <div class="wrap">
            <div class="aboutUs__content">
                <h2 class="fz_h2 title text-primary">
                    You've shown the world chapters of your life, <i>now give them the complete book</i>.
                </h2>
                <div class="aboutUs__desc g-text-wrap">
                    <p>
                        Everyone has a unique story, and at OttoStory, we turn it into an extraordinary autobiography.
                        Meet Otto, your AI biographer, guiding you through a reflective journey of your life's
                        experiences.
                    </p>
                    <p>
                        Say goodbye to professional writers and high costs. Otto helps transform your cherished memories
                        into a captivating memoir, allowing you to share your journey or simply reflect on your life.
                        With OttoStory, your story is immortalized in a personal hardback book.
                    </p>
                    <p>Begin with us today and watch your life's narrative come alive in print. Welcome to OttoStory</p>
                </div>
            </div>

            <div class="aboutUs__block">
                <div class="aboutUs__img">
                    <img src={img} alt={img} class="" />
                </div>
                <img src={splash} alt="Illustration" class="aboutUs-illustration" />
            </div>
        </div>
    </div>
</section>

<section class="titleAndButton bg-base-200">
    <img class="whiteOrangeFigure" src={whiteOrangeFigure} alt="Figure" />
    <img class="yellowWhiteFigure" src={yellowWhiteFigure} alt="Figure" />
    <div class="otto-container">
        <div class="wrap">
            <h2 class="fz_h2 title text-center text-primary">
                Memoirs of a Year : <i>A Beautiful Book Encompassing a Lifetime of Stories</i>
            </h2>
            <a href="/plans" class="otto-btn-primary" use:inertia> Buy Now </a>
        </div>
    </div>
</section>

<section class="ourTeam">
    <div class="otto-container">
        <div class="titleWithIllustration">
            <div class="wrap">
                <h2 class="fz_h2 title text-primary">
                    Our <i>Team</i>
                </h2>
                <img src={titleWithIllustration} alt="Illustration" />
            </div>
        </div>

        <div class="wrap">
            <div class="ourTeamCard">
                <div class="ourTeamCard-img">
                    <img src={imgTeam1} alt="Team member" />
                </div>
                <div class="ourTeamCard__content">
                    <h3 class="ourTeamCard-name">Darlene</h3>
                    <p class="ourTeamCard-position">Founder</p>
                </div>
            </div>
            <div class="ourTeamCard">
                <div class="ourTeamCard-img">
                    <img src={imgTeam2} alt="Team member" />
                </div>
                <div class="ourTeamCard__content">
                    <h3 class="ourTeamCard-name">John Due</h3>
                    <p class="ourTeamCard-position">Customer Care Lead</p>
                </div>
            </div>
            <div class="ourTeamCard">
                <div class="ourTeamCard-img">
                    <img src={imgTeam3} alt="Team member" />
                </div>
                <div class="ourTeamCard__content">
                    <h3 class="ourTeamCard-name">Alejandro</h3>
                    <p class="ourTeamCard-position">Manager</p>
                </div>
            </div>
        </div>
    </div>
</section>

<Footer />

<style lang="scss">
    .cta-bg {
        background-image: url(@/assets/img/about-cta-2.svg), url(@/assets/img/about-cta-1.svg);
        background-repeat: no-repeat, no-repeat;
        background-position:
            top 0 right 0,
            bottom 0 left 0;

        background-size: 9rem, 6rem;
    }

    .titleAndButton {
        padding: 180px 0;
        position: relative;

        .whiteOrangeFigure {
            position: absolute;
            left: -5%;
            bottom: -4%;
            max-width: 230px;
        }
        .yellowWhiteFigure {
            position: absolute;
            top: -5%;
            right: 1%;
            max-width: 230px;
        }
        .wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0 40px;
        }

        .title {
            margin-bottom: 60px;
        }
    }

    .aboutUs {
        padding: 120px 0 160px;
        overflow: hidden;

        .titleWithIllustration {
            margin-bottom: 90px;
        }

        .wrap {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        &__content {
            width: 100%;
            max-width: 624px;
            margin-right: 60px;
            position: relative;
            z-index: 2;
            .title {
                margin-bottom: 3rem;
                display: block;
            }
        }

        &__desc {
            padding-left: 48px;
        }

        &__block {
            position: relative;
            width: 100%;
            max-width: 428px;
        }

        &-illustration {
            position: absolute;
            bottom: -22%;
            left: -10%;
            width: 100%;
            min-width: 144%;
            // max-height: 100%;
            height: auto;
        }

        &__img {
            width: 100%;
            aspect-ratio: 16/18;
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            z-index: 2;

            img {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
        }
    }

    .ourTeam {
        padding: 120px 0 94px;

        .otto-container {
            max-width: 1190px;
        }

        .wrap {
            display: flex;
            flex-wrap: wrap;
            margin-right: -24px;
        }
    }

    .ourTeamCard {
        flex-basis: calc(100% / 3 - 24px);
        margin-right: 24px;
        margin-bottom: 24px;
        background-color: #fff;
        padding: 16px;
        border-radius: 16px;

        &-img {
            position: relative;
            overflow: hidden;
            width: 100%;
            height: auto;
            aspect-ratio: 16/16;
            border-radius: 16px;
            img {
                position: absolute;
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
        }

        &__content {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 16px 16px 8px 16px;
        }

        &-name {
            font-size: 32px;
            line-height: 1.2;
            margin-bottom: 7px;
            color: #0c345c;
        }
        &-position {
            line-height: 1.4;
            font-size: 20px;
            color: #0c345c;
        }
    }

    @media (max-width: 1280px) {
        .titleAndButton {
            .whiteOrangeFigure {
                width: 18%;
                min-width: 120px;
            }
            .yellowWhiteFigure {
                width: 18%;
                min-width: 120px;
            }
        }
    }

    @media (max-width: 991px) {
        .ourTeamCard {
            flex-basis: calc(100% / 2 - 24px);
        }
        .aboutUs {
            .wrap {
                flex-direction: column;
            }

            &__desc {
                padding-left: 0;
            }

            &__content {
                max-width: 100%;
                margin-right: 0;
            }
            &__block {
                margin-top: 110px;
            }
            &-illustration {
                bottom: -7%;
                left: 2%;
                min-width: 114%;
            }
        }
    }

    @media (max-width: 767px) {
        .ourTeamCard {
            flex-basis: calc(100%);
        }
        .titleAndButton {
            .wrap {
                padding: 0;
            }
        }
    }
</style>
