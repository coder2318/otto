<script lang="ts">
    import { inertia, page } from '@inertiajs/svelte'
    import Logo from '../SVG/logo.svg.svelte'
    import User from '../SVG/user.svg.svelte'
    $: user = $page.props.auth.user as App.User | null

    let className = ''

    export { className as class }
</script>

<nav class="navbar absolute top-0 z-20 transition-all {className}">
    <div class="flex-1">
        <a class="btn btn-ghost text-xl normal-case" href="/" use:inertia>
            <Logo class="h-full" textClass="fill-current" />
        </a>
    </div>
    <div class="flex-none gap-2">
        {#if user}
            <div class="dropdown-end dropdown leading-none">
                <label
                    tabindex="-1"
                    class="avatar btn btn-circle btn-ghost"
                    for="dropdown"
                >
                    <div class="w-full rounded-full border-2 border-secondary">
                        {#if user.avatar}
                            <img src={user.avatar} alt="avatar" />
                        {:else}
                            <User
                                class="bg-transparent"
                                pathClass="fill-secondary"
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
