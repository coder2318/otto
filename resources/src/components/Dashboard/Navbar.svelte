<script lang="ts">
    import { inertia, page } from '@inertiajs/svelte'
    import Logo from '../SVG/logo.svg.svelte'
    import LogoIcon from '../SVG/logo-icon.svg.svelte'
    import User from '../SVG/user.svg.svelte'
    import Fa from 'svelte-fa'
    import { faSearch, faCog, faBell } from '@fortawesome/free-solid-svg-icons'

    $: user = $page.props.auth.user as App.User | null
</script>

<nav class="navbar sticky top-0 z-20 bg-primary text-primary-content">
    <div class="navbar-start">
        <a
            class="btn btn-ghost text-xl normal-case"
            href="/dashboard"
            use:inertia
        >
            <Logo class="hidden h-full md:block" />
            <LogoIcon class="h-full md:hidden" />
        </a>
    </div>
    <div class="navbar-end flex gap-1 md:gap-2">
        <div class="form-control relative">
            <Fa
                icon={faSearch}
                class="absolute left-4 top-1/2 -translate-y-1/2"
            />
            <input
                type="text"
                placeholder="Search"
                class="search input input-ghost rounded-full border-neutral pl-10"
            />
        </div>
        <button
            class="btn btn-circle btn-ghost border-neutral hover:border-neutral-focus"
        >
            <Fa icon={faBell} class="h-full" />
        </button>
        <a
            class="btn btn-circle btn-ghost border-neutral hover:border-neutral-focus"
            href="/settrings"
            use:inertia
        >
            <Fa icon={faCog} class="h-full" />
        </a>
        {#if user}
            <div class="dropdown-end dropdown leading-none">
                <label
                    tabindex="-1"
                    class="avatar btn btn-circle btn-ghost"
                    for="dropdown"
                >
                    <div class="w-full rounded-full">
                        {#if user.avatar}
                            <img src={user.avatar} alt="avatar" />
                        {:else}
                            <User
                                class="bg-secondary"
                                pathClass="fill-secondary-content"
                            />
                        {/if}
                    </div>
                </label>
                <ul
                    tabindex="-1"
                    id="dropdown"
                    class="menu dropdown-content rounded-box z-[1] mt-3 w-48 border border-base-300 bg-base-100 p-2 text-base-content shadow"
                >
                    <li>
                        <a href="/profile" use:inertia>Profile</a>
                        <a href="/guest-chapters" use:inertia>Guest Chapters</a>
                        <button
                            href="/logout"
                            use:inertia={{ href: '/logout', method: 'POST' }}
                        >
                            Logout
                        </button>
                    </li>
                </ul>
            </div>
        {/if}
    </div>
</nav>
