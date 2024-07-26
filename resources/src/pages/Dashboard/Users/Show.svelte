<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { page, inertia } from '@inertiajs/svelte'
    import { fade } from 'svelte/transition'
    import bg from '@/assets/img/profile-bg.jpg'
    import User from '@/components/SVG/user.svg.svelte'
    import Instagram from '@/components/SVG/socials/instagram.svg.svelte'
    import Facebook from '@/components/SVG/socials/facebook.svg.svelte'
    import Linkedin from '@/components/SVG/socials/linkedin.svg.svelte'
    import Telegram from '@/components/SVG/socials/telegram.svg.svelte'
    import profileIllustration from '@/assets/img/profile-illustration.svg'

    export let user: { data: App.User }
    export let stories: { data: App.Story[] }

    $: authId = $page.props?.auth?.user?.data?.id

    function icon(code: string) {
        return (
            {
                instagram: Instagram,
                facebook: Facebook,
                telegram: Telegram,
                linkedin: Linkedin,
            }[code] ?? null
        )
    }
</script>

<svelte:head>
    <title
        >{import.meta.env.VITE_APP_NAME} - {user.data.details?.first_name ?? 'User'}
        {user.data.details?.last_name ?? ''}</title
    >
</svelte:head>

<section class="profile" in:fade>
    <div class="otto-container">
        <div class="wrap">
            <img class="profile-illustration" src={profileIllustration} alt="Figure" />
            <div class="profile-block">
                <div class="profile-bgi blockForImage">
                    <img src={bg} alt="background" />
                </div>

                <div class="profileContent">
                    <div class="profileContent_wrap">
                        <div class="profileContent_left">
                            <div class="userAvatar">
                                {#if user.data.avatar}
                                    <img src={user.data.avatar} alt="avatar" />
                                {:else}
                                    <User class="bg-secondary" pathClass="fill-secondary-content" />
                                {/if}
                            </div>
                            {#if user.data.id === authId}
                                <a href="/profile" use:inertia class="otto-btn-outline"> Edit </a>
                            {/if}
                        </div>

                        <div class="profileContent_right">
                            <div class="profileContent_row">
                                <h2 class="userName">
                                    {user.data.details?.first_name ?? 'User'}
                                    {user.data.details?.last_name ?? ''}
                                </h2>
                                <div class="userSocials">
                                    {#each Object.entries(user.data.details?.social ?? {}) as [key, value]}
                                        {#if value}
                                            <a href={value} target="_blank">
                                                <svelte:component this={icon(key)} />
                                            </a>
                                        {/if}
                                    {/each}
                                </div>
                            </div>

                            <a href="mailto:{user.data.email}" class="userEmail">
                                {user.data.email}
                            </a>
                            <div class="userBio">
                                {#if user.data.details?.bio}
                                    <p>{@html user.data.details?.bio}</p>
                                {/if}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{#if stories.data.length > 0}
    <section class="ottoBiography" in:fade>
        <div class="otto-container">
            <h1 class="fz_h2 title text-primary">View <i>OTTO Biography</i></h1>

            <div class="wrap">
                {#each stories.data as story}
                    <div class="book">
                        <div class="book__cover">
                            <img
                                src={story?.cover?.url}
                                class="aspect-[2/3] h-full object-cover object-right"
                                alt="cover"
                            />
                        </div>
                        <div class="book__wrap">
                            <div class="book__content">
                                <h2 class="book-title">{story.title}</h2>
                                <p>
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                    Ipsum has been the industry's standard dummy text ever since the 1500s
                                </p>
                                {#if user.data.id !== authId}
                                    <a href="/books/{story.id}" use:inertia class="otto-btn-primary btn-view-book">
                                        View Book
                                    </a>
                                {/if}
                            </div>
                            <div class="book__buttons">
                                {#if user.data.id === authId}
                                    <a href="/books/{story.id}" use:inertia class="otto-btn-primary btn-view-book">
                                        View Book
                                    </a>
                                    <a href="/stories/{story.id}" class="otto-btn-outline btn-edit-chapter">
                                        Edit Book
                                    </a>
                                {/if}
                            </div>
                        </div>
                    </div>
                {/each}
            </div>
        </div>
    </section>
{/if}

<style lang="scss">
    .profile {
        padding: 60px 0;

        .otto-container {
            max-width: 100%;
            max-width: 1620px;
            padding: 0 50px;
        }

        .wrap {
            width: 100%;
            position: relative;
        }

        &-illustration {
            position: absolute;
            left: -132px;
            bottom: -137px;
        }

        &-block {
            width: 100%;
            background-color: #fff;
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            z-index: 2;
        }

        &-bgi {
            width: 100%;
            aspect-ratio: 160/45;
            border-radius: 0;
        }

        .userAvatar {
            min-width: 170px;
            height: 170px;
            position: relative;
            overflow: hidden;
            background-color: #ece6df;
            border-radius: 100%;
            border: 6px solid #fff;
            margin-bottom: 15px;

            img {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
        }

        .userName {
            font-size: 32px;
            font-weight: 600;
            color: #0c345c;
        }

        .userEmail {
            margin-bottom: 16px;
            display: inline-block;
            font-size: 16px;
            color: #808080;
            line-height: 1.7;

            &:hover {
                text-decoration: underline;
            }
        }

        .userBio {
            font-size: 16px;
            color: #808080;
            line-height: 1.7;
        }

        .userSocials {
            display: flex;
            a {
                display: flex;
                margin-right: 12px;
                transition: 0.3s linear;

                &:hover {
                    transform: scale(1.1);
                }

                &:last-child {
                    margin-right: 0;
                }
            }
        }

        .profileContent {
            padding: 34px 48px 48px;

            &_wrap {
                display: flex;
            }

            &_left {
                display: flex;
                flex-direction: column;
                align-items: center;
                margin-right: 44px;
                margin-top: -84px;
                z-index: 10;
            }

            &_right {
                width: 100%;
            }

            &_row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 4px;
            }
        }

        .otto-btn-outline {
            font-size: 16px;
            height: 48px;
        }
    }

    .ottoBiography {
        position: relative;
        padding-bottom: 100px;
        overflow: hidden;

        .otto-container {
            max-width: 100%;
            max-width: 1620px;
            padding: 0 50px;
        }

        .wrap {
            border-radius: 20px;
            border: 1px solid #c6b69f;
            padding: 24px;
        }

        h1.title {
            margin-bottom: 40px;
        }

        .book {
            margin-bottom: 24px;
            display: flex;
            align-items: center;

            .btn-view-book {
                height: 40px;
                font-size: 14px;
            }

            .btn-edit-chapter {
                height: 40px;
                font-size: 14px;
            }

            &__buttons {
                .btn-view-book {
                    width: 150px;
                    margin-bottom: 16px;
                    padding: 0 15px;
                }
                .btn-edit-chapter {
                    width: 150px;
                    padding: 0 15px;
                }
            }

            &__cover {
                min-width: 120px;
                height: 180px;
                position: relative;
                overflow: hidden;
                background-color: #ece6df;
                border-radius: 10px;
                margin-right: 24px;
            }

            &__wrap {
                display: flex;
                align-items: center;
                justify-content: space-between;
                width: 100%;
            }

            &__content {
                margin-right: 30px;
                p {
                    color: #808080;
                    line-height: 1.7;
                    display: block;
                    margin-bottom: 8px;
                    max-width: 470px;
                }
            }

            &-title {
                font-size: 32px;
                line-height: 1.2;
                color: #0c345c;
                margin-bottom: 8px;
            }

            &:last-child {
                margin-bottom: 0;
            }
        }
    }

    @media (max-width: 991px) {
        .profile {
            .otto-container {
                padding: 0 20px;
            }
        }
        .ottoBiography {
            .otto-container {
                padding: 0 20px;
            }
        }
    }

    @media (max-width: 767px) {
        .profile {
            &-bgi {
                aspect-ratio: 160/60;
            }

            .profileContent {
                padding: 0 20px 20px;

                &_left {
                    flex-direction: row;
                    align-items: flex-end;
                    margin-right: 0;
                    margin-bottom: 30px;
                    margin-top: -54px;

                    .otto-btn-outline {
                        margin-bottom: 1px;
                    }
                }
                &_wrap {
                    flex-direction: column;
                    align-items: flex-start;
                }
                &_row {
                    flex-direction: column;
                    align-items: flex-start;
                }
            }

            .userAvatar {
                margin-bottom: 0;
                margin-right: 15px;
                margin-left: -6px;
                min-width: 150px;
                height: 150px;
            }

            .userSocials {
                order: -1;
                margin-bottom: 10px;
            }
        }
        .ottoBiography {
            .book {
                flex-direction: column;
                align-items: flex-start;

                &__cover {
                    margin-right: 0;
                    margin-bottom: 10px;
                }

                &__buttons {
                    display: flex;
                    flex-direction: row;
                    margin-top: 10px;
                    width: 100%;

                    .btn-view-book {
                        margin-bottom: 0;
                        margin-right: 10px;
                        width: 50%;
                    }
                    .btn-edit-chapter {
                        width: 50%;
                    }
                }

                &__content {
                    margin-right: 0;
                }
                &__wrap {
                    flex-direction: column;
                    align-items: flex-start;
                }
            }
        }
    }
</style>
