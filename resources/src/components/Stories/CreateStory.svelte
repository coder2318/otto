<script lang="ts">
    import { inertia } from '@inertiajs/svelte'
    import createOttoStoryIllustration from '@/assets/img/create-otto-story-illustration.svg'
    import createOttos1 from '@/assets/img/create-ottos-1.svg'
    import createOttos2 from '@/assets/img/create-ottos-2.svg'
    import createOttos3 from '@/assets/img/create-ottos-3.svg'
    export let story: { data: App.Story }
</script>

<section class="createStory">
    <div class="otto-container">
        <div class="createStory_wrapper">
            <img class="createStory-illustration" src={createOttoStoryIllustration} alt="Illustartion" />
            <h2 class="fz_h2 title">Create new OttoStory</h2>
            <div class="wrap">
                <div class="createStory__description">
                    <p>
                        Chat with Otto and relive a cherished memory. Turn this treasured moment into a brand new
                        chapter.
                    </p>
                </div>
                <div class="createStory__blocks">
                    <a
                        class="createStory__block record"
                        href="/stories/{story.data.id}/chapters/create?redirect=record"
                        use:inertia
                    >
                        <img src={createOttos1} alt="icon" />
                        <span class="createStory__block-title">Record your Story</span>
                    </a>

                    <a
                        class="createStory__block upload"
                        href="/stories/{story.data.id}/chapters/create?redirect=write"
                        use:inertia
                    >
                        <img src={createOttos2} alt="icon" />
                        <span class="createStory__block-title">Type your Story</span>
                    </a>
                    <a
                        class="createStory__block type"
                        href="/stories/{story.data.id}/chapters/create?redirect=upload"
                        use:inertia
                    >
                        <img src={createOttos3} alt="icon" />
                        <span class="createStory__block-title">Upload File</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style lang="scss">
    .createStory {
        padding: 30px 0;

        &-illustration {
            position: absolute;
            left: 0;
            top: 0;
        }

        .title {
            font-size: 46px;
            margin-bottom: 24px;
        }

        &_wrapper {
            background-color: #ffbe33;
            padding: 32px 50px;
            border-radius: 24px;
            position: relative;
            overflow: hidden;
        }

        .wrap {
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        &__blocks {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            margin-right: -30px;
            max-width: 692px;
        }

        &__block {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-basis: calc(100% / 3 - 30px);
            margin-right: 30px;
            height: 118px;
            background-color: #fff;
            border: 3px solid transparent;
            transition: 0.3s linear;
            border-radius: 30px;
            cursor: pointer;
            padding: 5px;

            &:hover {
                border: 3px solid #ffd886;
            }

            &-title {
                font-size: 20px;
                line-height: 1.1;
                text-align: center;
                color: #06192d;
                font-weight: 400;
                margin-top: 12px;
            }
        }

        &__description {
            width: 100%;
            max-width: 378px;
            margin-right: 36px;

            p {
                font-size: 18px;
                line-height: 1.44;
                color: #06192d;
            }
        }
    }

    @media (max-width: 1199px) {
        .createStory {
            &_wrapper {
                padding: 30px;
            }
            .title {
                margin-bottom: 16px;
            }
            &__description {
                max-width: 100%;
                margin-bottom: 36px;
            }
            .wrap {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    }

    @media (max-width: 767px) {
        .createStory {
            .title {
                font-size: 36px;
            }
            &_wrapper {
                padding: 30px 20px;
            }

            &__blocks {
                width: auto;
            }

            &__block {
                flex-basis: calc(100%);
                margin-bottom: 12px;

                &:last-child {
                    margin-bottom: 0;
                }
            }
        }
    }
</style>
