<script lang="ts">
    import { inertia, page } from '@inertiajs/svelte'
    import Logo from '../SVG/logo.svg.svelte'
    import LogoIcon from '../SVG/logo-icon.svg.svelte'
    import User from '../SVG/user.svg.svelte'
    import { opened, count } from '../Notifications.svelte'
    import settingsIcon from '@/assets/img/navbar-icons/settings.svg'
    import Search from './Search.svelte'

    $: user = $page.props?.auth?.user?.data as App.User | null
</script>

<nav class="navbar sticky top-0 z-20 bg-primary px-5 py-4 text-primary-content md:px-12">
    <div class="navbar-start">
        <a class="relative block h-14 w-fit" href="/" use:inertia>
            <Logo class="hidden h-full md:block" />
            <LogoIcon class="h-full md:hidden" />
        </a>
    </div>
    <div class="navbar-end flex gap-4">
        <Search />
        <!-- Indicator -->
        {#if user}
            <div class="indicator transition duration-300">
                {#if $count}
                    <span class="badge indicator-item badge-accent right-2 top-2 h-4 w-4 p-0">
                        <span class="absolute inset-0 animate-ping rounded-full bg-accent" />
                        {$count}
                    </span>
                {/if}
                <button
                    type="button"
                    class="btn btn-circle btn-ghost border-neutral hover:border-neutral-focus"
                    on:click|preventDefault={() => ($opened = !$opened)}
                >
                    <svg width="25" height="26" viewBox="0 0 25 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12.0199 4C8.3399 4 5.3599 6.98 5.3599 10.66V12.76C5.3599 13.44 5.0799 14.46 4.7299 15.04L3.4599 17.16C2.6799 18.47 3.2199 19.93 4.6599 20.41C9.4399 22 14.6099 22 19.3899 20.41C20.7399 19.96 21.3199 18.38 20.5899 17.16L19.3199 15.04C18.9699 14.46 18.6899 13.43 18.6899 12.76V10.66C18.6799 7 15.6799 4 12.0199 4Z"
                            stroke="white"
                            stroke-width="1.5"
                            stroke-miterlimit="10"
                            stroke-linecap="round"
                        />
                        <path
                            d="M15.3299 20.8198C15.3299 22.6498 13.8299 24.1498 11.9999 24.1498C11.0899 24.1498 10.2499 23.7698 9.64992 23.1698C9.04992 22.5698 8.66992 21.7298 8.66992 20.8198"
                            stroke="white"
                            stroke-width="1.5"
                            stroke-miterlimit="10"
                        />
                        {#if $count}
                            <circle cx="18" cy="7" r="5" fill="#FFBE33" stroke="#0C345C" stroke-width="4" />
                        {/if}
                    </svg>
                </button>
            </div>

            <a
                class="btn btn-circle btn-ghost border-neutral transition duration-300 hover:scale-105 hover:border-neutral-focus"
                href="/settings"
                use:inertia
            >
                <img src={settingsIcon} alt="Figure" />
            </a>

            <div class="dropdown dropdown-end leading-none">
                <label tabindex="-1" class="avatar btn btn-circle btn-ghost h-16 w-16 bg-neutral" for="dropdown">
                    <div class="w-full rounded-full">
                        {#if user.avatar}
                            <img src={user.avatar} alt="avatar" />
                        {:else}
                            <User class="bg-secondary" pathClass="fill-secondary-content" />
                        {/if}
                    </div>
                </label>
                <ul
                    tabindex="-1"
                    id="dropdown"
                    class="menu dropdown-content rounded-box z-[1] mt-3 w-48 border border-base-300 bg-base-100 p-2 text-base-content shadow"
                >
                    <li>
                        <a href="/u/{user.id}" use:inertia>Profile</a>
                    </li>
                    <li>
                        <a href="/stories" use:inertia> Stories </a>
                    </li>
                    <li>
                        <a href="/guests/chapters" use:inertia> Guest Chapters </a>
                    </li>
                    <li>
                        <!-- <a href="/profile" use:inertia>Profile</a>
                        <a href="/guest-chapters" use:inertia>Guest Chapters</a> -->
                        <button href="/logout" use:inertia={{ href: '/logout', method: 'POST' }}> Logout </button>
                    </li>
                </ul>
            </div>
        {/if}
    </div>
</nav>
