<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    import Paginator from '@/components/Paginator.svelte'
    import guestIllustration from '@/assets/img/guest-illustration.svg'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { inertia, page, router } from '@inertiajs/svelte'
    import { fade } from 'svelte/transition'

    $: type = new URLSearchParams($page.url.split('?')?.[1]).get('type') ?? 'sent'
    $: user = $page.props?.auth?.user
    $: guest = $page.props?.auth?.guest

    export let chapters: {
        data: App.Chapter[]
        links: App.PaginationLinks
        meta: App.PaginationMeta
    }

    const types = {
        sent: 'Sent Requests',
        received: 'Received Requests',
    }

    function resend(chapter: App.Chapter) {
        router.post(`/guests/chapters/${chapter.id}/resend`)
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Guest Chapters</title>
</svelte:head>

<main class="">
    <section class="guestChapters">
        <div class="otto-container">
            <div class="guestTop">
                <div class="guestTop__wrap">
                    <img class="guestChapters-illustration" src={guestIllustration} alt="Illustration" />
                    <h1 class="fz_h2 text-primary">
                        Your <i>Guest Chapters</i>
                    </h1>
                </div>

                {#if user && guest}
                    <div class="tabs">
                        {#each Object.entries(types) as [key, value]}
                            <a class="tab tab-bordered" class:tab-active={type === key} href="?type={key}" use:inertia>
                                {value}
                            </a>
                        {/each}
                    </div>
                {/if}
            </div>

            <div class="guestChapters__table">
                <table class="table w-full rounded-lg bg-neutral text-neutral-content">
                    <thead>
                        <th>{type === 'sent' || !type ? 'To' : 'From'}</th>
                        <th>Email</th>
                        <th>Question</th>
                        <th>Status</th>
                        <th />
                    </thead>
                    <tbody>
                        {#each chapters.data as chapter (chapter.id + type)}
                            <tr in:fade class="hover">
                                <td
                                    >{type === 'sent'
                                        ? chapter.guest?.name
                                        : (chapter.user?.details.first_name ?? 'User') + ' ' + (chapter.user?.details.last_name ?? '')}</td
                                >
                                <td>{type === 'sent' ? chapter.guest?.email : chapter.user?.email}</td>
                                <td style="min-width: 300px;">{chapter.title}</td>
                                <td>{chapter.status}</td>
                                <td style="white-space: nowrap; display: flex;">
                                    {#if type === 'sent'}
                                        <a
                                            href="/guests/chapters/{chapter.id}"
                                            use:inertia
                                            class="otto-btn-secondary otto-btn"
                                        >
                                            Review
                                        </a>
                                        <button on:click={() => resend(chapter)} class="otto-btn-primary otto-btn"
                                            >Resend</button
                                        >
                                    {/if}
                                    {#if type === 'received'}
                                        <a
                                            href="/guests/chapters/{chapter.id}/edit"
                                            use:inertia
                                            class="otto-btn-primary otto-btn"
                                        >
                                            Edit
                                        </a>
                                    {/if}
                                </td>
                            </tr>
                        {/each}
                    </tbody>
                </table>
            </div>
            <Paginator class="flex-wrap items-center justify-center gap-y-2" meta={chapters.meta} />
        </div>
    </section>
</main>

<style lang="scss">
    .guestChapters {
        position: relative;
        padding-top: 80px;
        padding-bottom: 100px;
        overflow: hidden;

        &-illustration {
            position: absolute;
            left: -111px;
            top: -140px;
        }

        .guestTop {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            padding-bottom: 40px;

            @media (max-width: 991px) {
                flex-direction: column;
                align-items: flex-start;
            }

            &__wrap {
                position: relative;

                @media (max-width: 991px) {
                    margin-bottom: 20px;
                }
            }
        }

        &__table {
            position: relative;
            width: 100%;
            overflow: auto;

            .otto-btn {
                font-size: 16px;
                height: 40px;
                padding: 0 16px;
                margin-right: 10px;
            }
        }

        table {
            background-color: transparent;

            thead {
                margin-bottom: 24px;
                border-bottom-color: rgb(242, 238, 233);
                border-bottom-width: 24px;
                th {
                    font-size: 16px;
                    font-weight: 500;
                    color: #1a1a1a;
                }
            }

            tbody {
                tr {
                    border-bottom-width: 16px;
                    border-bottom-color: rgba(242, 238, 233);
                    background-color: #fff;
                    border-radius: 8px;
                    overflow: hidden;

                    td {
                        font-size: 16px;
                        color: #1a1a1a;
                    }
                }
            }
        }

        .tabs {
            .tab {
                font-size: 20px;
                height: 56px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #808080;
                padding: 0 24px;

                @media (max-width: 991px) {
                    padding: 0 12px;
                    font-size: 18px;
                    height: 48px;
                }
            }
            .tab-active {
                color: #0c345c;
            }
        }

        :global(.otto-pagination) {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }
    }
</style>
