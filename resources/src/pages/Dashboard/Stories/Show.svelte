<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { inertia } from '@inertiajs/svelte'
    import smallBanner from '@/assets/img/small-banner.jpg'
    import beginYourBook from '@/assets/img/begin-your-book.jpg'
    import finishYourBook from '@/assets/img/finishyourbook.jpg'
    import beginYourBookIcon from '@/assets/img/begin-your-book-icon.svg'
    import finishYourBookIcon from '@/assets/img/finishyourbookicon.svg'
    import designCoverIllustration from '@/assets/img/design-cover-illustration.svg'
    import coverAddIcon from '@/assets/img/cover-add-icon.svg'
    import QuoteIcon from '@/components/SVG/quote-icon.svg.svelte'
    import smallBannerIllustration from '@/assets/img/profile-illustration.svg'
    import CreateStory from '@/components/Stories/CreateStory.svelte'
    import EditCoverBtn from '@/components/SVG/buttons/edit-cover-btn.svg.svelte'
    export let story: { data: App.Story }
    export let auth: { user: { data: App.User } }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {story.data.title}</title>
</svelte:head>

<section class="smallBanner">
    <div class="otto-container">
        <img class="smallBanner-illustration" src={smallBannerIllustration} alt="Figure" />
        <div class="block">
            <img class="smallBanner-bgi" src={smallBanner} alt="ImageBanner" />
            <h2 class="title font-serif">
                Good morning, {auth?.user?.data?.details?.first_name ?? 'User'}. What are you up to today?
            </h2>
        </div>
    </div>
</section>

<section class="yourNarrative">
    <div class="otto-container">
        <div class="block">
            <div class="wrap">
                <a use:inertia href="/stories/{story.data.id}/chapters" class="nCard beginYourBook">
                    <img class="nCard-bgi" src={beginYourBook} alt="writing room" />

                    <div class="nCard__content">
                        <div class="nCard__content_text">
                            <h3 class="nCard-title">
                                <span>Begin</span> your book
                            </h3>
                            <span class="nCard-subtitle"
                                >Dive deep into the writing room and craft your story with AI’s assistance.</span
                            >
                        </div>
                        <button class="nCard__btn">
                            <div class="nCard__btn_icon">
                                <img src={beginYourBookIcon} alt="Icon" />
                            </div>
                            <p>Start writing</p>
                        </button>
                    </div>
                </a>

                <a use:inertia href="/stories/{story.data.id}/cover" class="nCard finishYourBook">
                    <img class="nCard-bgi" src={finishYourBook} alt="Continue Editing" />

                    <div class="nCard__content">
                        <div class="nCard__content_text">
                            <h3 class="nCard-title">
                                <span>Finish</span> your book
                            </h3>
                            <span class="nCard-subtitle"
                                >Fine-tune your memories with AI’s advanced editing tools.
                            </span>
                        </div>
                        <button class="nCard__btn">
                            <div class="nCard__btn_icon">
                                <img src={finishYourBookIcon} alt="Icon" />
                            </div>
                            <p>Continue Editing</p>
                        </button>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<CreateStory {story} />

<section class="designCover">
    <div class="otto-container">
        <div class="designCover__block">
            <img class="designCover-illustration" src={designCoverIllustration} alt="Illustration" />
            <div class="wrap">
                <div class="block">
                    <div class="cover">
                        {#if !story.data.cover?.url}
                            <a href="/stories/{story.data.id}/cover" use:inertia class="cover-add">
                                <img src={coverAddIcon} alt="plus" />
                            </a>
                        {:else}
                            <img
                                class="aspect-[2/3] h-full object-cover object-right"
                                src={story.data.cover?.url}
                                alt="Cover"
                            />
                        {/if}
                    </div>
                    <div
                        class="block__bottom"
                        class:justify-center={!story.data.cover?.url}
                        class:justify-between={story.data.cover?.url}
                    >
                        <div class="bookProgress">
                            <div class="bookProgress__content">
                                <span class="bookProgress-count text-primary">{story.data.progress ?? 0}%</span>
                                <span class="bookProgress-title text-primary">complete</span>
                            </div>
                            <progress class="progress progress-primary" value={story.data?.progress ?? 0} max="100"
                            ></progress>
                        </div>
                        {#if story.data.cover?.url}
                            <a class="otto-btn-svg" href="/stories/{story.data.id}/cover" use:inertia>
                                <EditCoverBtn />
                            </a>
                        {/if}
                    </div>
                </div>

                <div class="designCover__content">
                    <div class="designCover__content_top">
                        <h3 class="title fz_h3 text-primary">Design your Cover</h3>
                        <div class="quote text-primary">
                            <div class="quote-icon">
                                <QuoteIcon />
                            </div>
                            <blockquote>
                                Never judge someone by the way he looks or a book by the way it's covered; for inside
                                those tattered pages, there's a lot to be discovered.
                            </blockquote>
                            <span class="quote-name font-serif"> ― Steve Cosgroves </span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 text-primary">
                        <div class="card bg-neutral p-6 text-center">
                            <span class="text-6xl">{story.data.words}</span>
                            <span class="text-lg">Words Written</span>
                        </div>
                        <div class="card bg-neutral p-6 text-center">
                            <span class="text-6xl">{story.data.pages}</span>
                            <span class="text-lg">Pages Written</span>
                        </div>
                    </div>
                </div>

                <!--  -->
            </div>
        </div>
    </div>
</section>

<style lang="scss">
    .yourNarrative {
        position: relative;
        overflow: hidden;

        // .block {
        //     background-color: #eae4dc;
        //     border-radius: 24px;
        //     padding: 50px;
        // }

        .wrap {
            display: flex;
            flex-wrap: wrap;
            margin-right: -30px;
        }
    }

    .nCard {
        position: relative;
        border-radius: 24px;
        background-color: #fff;
        flex-basis: calc(100% / 2 - 30px);
        margin-right: 30px;
        overflow: hidden;
        aspect-ratio: 16/10.8;
        min-height: 280px;

        &::after {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            // background-color: rgba(217, 206, 191, 0.4);
            background: linear-gradient(0deg, rgba(0, 0, 0, 0.36) 0%, rgba(0, 0, 0, 0) 100%),
                linear-gradient(180deg, rgba(0, 0, 0, 0.36) 0%, rgba(0, 0, 0, 0) 100%),
                linear-gradient(0deg, rgba(12, 52, 92, 0.1), rgba(12, 52, 92, 0.1));
        }

        &-bgi {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        &__content {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: flex-start;
            position: relative;
            z-index: 3;
            padding: 50px;
            height: 100%;
        }

        &-title {
            font-size: 46px;
            line-height: 1.1;
            color: #fff;
            margin-bottom: 10px;

            span {
                color: #fff;
                transition: 0.3s;
            }
        }

        &-subtitle {
            color: #fff;
            font-size: 18px;
            line-height: 1.33;
            font-weight: 400;
            transition: 0.3s;
        }

        &__btn {
            display: flex;
            align-items: center;
            height: 54px;
            background-color: #d2e5f9;
            transition: 0.3s;
            border-radius: 40px;
            padding: 0 18px 0 9px;

            &_icon {
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: #fff;
                border-radius: 100%;
                margin-right: 12px;
                width: 42px;
                height: 42px;
            }

            p {
                font-size: 18px;
                color: #06192d;
            }
        }

        &:hover {
            .nCard__btn {
                background-color: #ffd885;
            }
            .nCard-subtitle {
                color: #ffd885;
            }
            .nCard-title {
                span {
                    color: #ffd885;
                }
            }
        }

        &.beginYourBook {
            .nCard-title span {
                color: #ffd885;
            }
            .nCard-subtitle {
                color: #ffd885;
            }
            .nCard__btn {
                background-color: #ffd885;
            }
            &:hover {
                .nCard__btn {
                    background-color: #ffbe33;
                }
                .nCard-subtitle {
                    color: #ffbe33;
                }
                .nCard-title span {
                    color: #ffbe33;
                }
            }
        }

        &.finishYourBook {
            .nCard-title span {
                color: #cfe3f3;
            }
            .nCard-subtitle {
                color: #cfe3f3;
            }
            .nCard__btn {
                background-color: #cfe3f3;
            }
            &:hover {
                .nCard__btn {
                    background-color: #8ebff0;
                }
                .nCard-subtitle {
                    color: #8ebff0;
                }
                .nCard-title span {
                    color: #8ebff0;
                }
            }
        }
    }

    .smallBanner {
        width: 100%;
        padding: 20px 0;

        .otto-container {
            position: relative;
        }

        &-illustration {
            position: absolute;
            left: -100px;
            top: -160px;
            width: 380px;
        }

        &-bgi {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .block {
            display: flex;
            align-items: center;
            width: 100%;
            border-radius: 24px;
            background-color: #222;
            padding: 24px 50px;
            position: relative;
            overflow: hidden;

            &::after {
                content: '';
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(to right, rgba(12, 52, 92, 0.7), rgba(226, 218, 207, 0.2));
            }
        }

        .title {
            font-size: 42px;
            line-height: 1.1;
            color: #fff;
            position: relative;
            z-index: 2;
            font-style: italic;
        }
    }

    .designCover {
        padding-bottom: 100px;

        .title {
            margin-bottom: 65px;
        }

        &-illustration {
            position: absolute;
            right: 0;
            top: 0;
        }

        &__block {
            background-color: #eae4dc;
            padding: 50px;
            border-radius: 24px;
            position: relative;
            overflow: hidden;
        }

        .wrap {
            display: flex;
        }

        .block {
            min-width: 422px;
            max-width: 422px;
            padding: 16px 16px 36px;
            background-color: #fff;
            margin-right: 36px;
            border-radius: 24px;

            &__bottom {
                display: flex;
            }
        }

        .cover {
            background-color: rgba(226, 218, 207, 0.2);
            position: relative;
            border-radius: 14px;
            aspect-ratio: 16/14;
            margin-bottom: 24px;
            overflow: hidden;
            display: flex;
            justify-content: center;

            &-add {
                display: flex;
                align-items: center;
                justify-content: center;
                position: absolute;
                width: 84px;
                height: 84px;
                border-radius: 100%;
                background-color: #fff;
                position: absolute;
                left: 50%;
                top: 50%;
                transform: translate(-50%, -50%);
                border: 2px solid #fff;
                transition: 0.3s;

                &:hover {
                    border: 2px solid #0c345c;
                }
            }

            // &-img {
            //     position: absolute;
            //     left: 0;
            //     top: 0;
            //     width: 100%;
            //     height: 100%;
            //     object-fit: cover;
            // }
        }

        .bookProgress {
            display: flex;
            flex-direction: column;
            align-items: center;
            &__content {
                display: flex;
                align-items: flex-end;
                margin-bottom: 12px;
            }
            &-count {
                font-size: 36px;
                line-height: 1;
                margin-right: 5px;
            }
            &-title {
                font-size: 18px;
                line-height: 1;
            }

            .progress {
                width: 220px;
                height: 8px;
                background-color: #e2dacf;
            }
        }

        &__content {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            width: 100%;

            &_top {
                margin-bottom: 30px;
            }
        }

        .quote {
            padding-left: 92px;
            position: relative;
            &-icon {
                position: absolute;
                left: 10px;
                top: -16px;
            }
            &-name {
                font-size: 24px;
                color: inherit;
            }
            blockquote {
                font-size: 18px;
                margin-bottom: 5px;
                max-width: 532px;
                color: inherit;
            }
        }
    }

    @media (max-width: 1199px) {
        .yourNarrative {
            .wrap {
                margin-right: -24px;
            }
        }

        .nCard {
            flex-basis: calc(50% - 24px);
            margin-right: 24px;
            &__content {
                padding: 25px 20px;
            }
            &-title {
                font-size: 32px;
            }
            &-subtitle {
                font-size: 16px;
            }
            &__btn {
                transform: scale(0.9);
            }
        }

        .smallBanner {
            .block {
                padding: 30px 30px;
            }
            .title {
                font-size: 48px;
            }
        }

        .designCover {
            .wrap {
                flex-direction: column;
            }
            &__block {
                padding: 30px;
            }
            .block {
                margin-right: 0;
                margin-bottom: 24px;
            }
        }
    }

    @media (max-width: 991px) {
    }

    @media (max-width: 767px) {
        .smallBanner {
            // .block {
            //     padding: 30px 20px;
            // }
            .title {
                font-size: 32px;
            }
        }

        .nCard {
            flex-basis: calc(100%);
            margin-bottom: 24px;

            &:last-child {
                margin-bottom: 0;
            }

            &-title {
                font-size: 32px;
            }
        }

        .designCover {
            .quote {
                padding-left: 70px;
                &-icon {
                    left: 0;
                }
            }
            &__block {
                padding: 30px 20px;
            }
            .block {
                min-width: auto;
                max-width: none;
                width: 100%;
            }
        }
    }
</style>
