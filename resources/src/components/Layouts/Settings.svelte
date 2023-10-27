<script lang="ts" context="module">
    import { writable } from 'svelte/store'
    import settingsIllustration from '@/assets/img/settings-illustration.svg'
    export const opened = writable(false)
</script>

<script lang="ts">
    import { inertia, page } from '@inertiajs/svelte'
    import Fa from 'svelte-fa'
    import { faBars } from '@fortawesome/free-solid-svg-icons'

    const links = [
        { href: '/settings/notifications', label: 'Notifications' },
        { href: '/settings/password', label: 'Change Password' },
        { href: '/settings/billing', label: 'Billing' },
    ]
</script>

<section class="settings__top">
    <div class="otto-container">
        <div class="wrap">
            <img class="settings__top-illustration" src={settingsIllustration} alt="Illustration" />
            <!-- <button class="btn btn-circle btn-ghost z-10 text-3xl lg:hidden" on:click={() => ($opened = true)}>
                <Fa icon={faBars} />
            </button> -->
            <h1 class="fz_h2 z-10">Settings</h1>
        </div>
    </div>
</section>

<section class="settings">
    <div class="otto-container">
        <div class="wrap drawer-open">
            <input id="settings-drawer" type="checkbox" class="drawer-toggle" bind:checked={$opened} />
            <div class="drawer-side">
                <label for="settings-drawer" class="drawer-overlay"></label>
                <ul class="menu menu-lg">
                    {#each links as { href, label }}
                        <li class="rounded-lg lg:bg-neutral lg:text-neutral-content">
                            <a {href} class:active={$page.url === href} use:inertia on:click={() => ($opened = false)}>
                                {label}
                            </a>
                        </li>
                    {/each}
                </ul>
            </div>
            <div class="drawer-content">
                <slot />
            </div>
        </div>
    </div>
</section>

<style lang="scss">
    .menu > li > .active {
        @apply lg:bg-base-300 lg:text-base-content;
    }

    .settings__top {
        padding: 80px 0 70px;

        .wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        button,
        h1 {
            position: relative;
            z-index: 2;
            color: #0c345c;
        }

        &-illustration {
            position: absolute;
            z-index: 1;
            top: -132px;
            left: -90px;
        }
    }

    .settings {
        padding-bottom: 100px;
        position: relative;
        z-index: 3;

        .wrap {
            display: flex;
        }

        .drawer-side {
            margin-right: 48px;
            width: 100%;
            max-width: 276px;

            .menu {
                padding: 0;
                li {
                    margin-bottom: 16px;
                    border-radius: 10px;

                    a {
                        font-size: 18px;
                        color: #000;

                        &.active {
                            background: #e3dbcf;
                        }
                    }
                }
            }
        }
        .drawer-content {
            width: 100%;
        }
    }
</style>
