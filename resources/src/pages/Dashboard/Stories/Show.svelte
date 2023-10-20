<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { inertia } from '@inertiajs/svelte'
    import writing from '@/assets/img/dashboard-writing.jpg'
    import editing from '@/assets/img/dashboard-editing.jpg'
    import titleWithIllustration from '@/assets/img/green-illustration.svg'
    export let story: { data: App.Story }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {story.data.title}</title>
</svelte:head>

<main class="yourNarrative">
    <div class="otto-container">
        <div class="titleWithIllustration">
            <div class="wrap">
                <h1 class="fz_h2 title text-primary">
                    Crafting Your Narrative: <i>{story.data.title}</i>
                </h1>
                <img src={titleWithIllustration} alt="Illustration" />
            </div>
        </div>

        <div class="wrap">
            <a use:inertia href="/stories/{story.data.id}/chapters" class="nCard">
                <div class="nCard__img blockForImage">
                    <img src={writing} alt="writing room" />
                </div>
                <div class="nCard__content">
                    <h2 class="nCard-title text-primary">
                        Writing <i>Room</i>
                    </h2>
                </div>
            </a>
            <a use:inertia href="/stories/{story.data.id}/edit" class="nCard">
                <div class="nCard__img blockForImage">
                    <img src={editing} alt="writing room" />
                </div>
                <div class="nCard__content">
                    <h2 class="nCard-title text-primary">
                        Editing <i>Room</i>
                    </h2>
                </div>
            </a>
        </div>
    </div>
</main>

<style lang="scss">
    .yourNarrative {
        position: relative;
        padding: 80px 0 100px;
        overflow: hidden;

        .titleWithIllustration {
            .wrap {
                margin-right: 0;
            }
            img {
                left: -41px;
                top: -19px;
                height: 143%;
                max-height: 95px;
            }
        }

        .otto-container {
            max-width: 1090px;
        }

        .wrap {
            display: flex;
            flex-wrap: wrap;
            margin-right: -40px;
        }
    }

    .nCard {
        position: relative;
        border-radius: 24px;
        padding: 16px;
        background-color: #fff;
        flex-basis: calc(100% / 2 - 40px);
        margin-right: 40px;

        &-title {
            font-size: 44px;
            text-align: center;
        }

        &__img {
            border-radius: 16px;
            width: 100%;
            aspect-ratio: 16/12;

            img {
                transition: 0.5s;
            }

            &:hover {
                img {
                    transform: scale(1.1) rotate(-3deg);
                }
            }
        }

        &__content {
            padding-top: 16px;
        }
    }

    @media (max-width: 991px) {
        .nCard {
            flex-basis: calc(100%);
            margin-bottom: 30px;

            &-title {
                font-size: 32px;
            }
        }
    }
</style>
