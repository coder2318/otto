<script lang="ts">
    import { inertia, router, page } from '@inertiajs/svelte'
    import Logo from './SVG/logo.svg.svelte'
    import User from './SVG/user.svg.svelte'

    $: user = $page.props.auth.user
</script>

<nav class="navbar sticky top-0 z-20 bg-primary text-primary-content">
    <div class="flex-1">
        <a class="btn btn-ghost text-xl normal-case" href="/" use:inertia>
            <Logo class="h-full" />
        </a>
    </div>
    {#if user}
        <div class="flex-none gap-2">
            <a
                class="btn btn-secondary btn-outline rounded-full"
                href="/stories"
                use:inertia>Start Writing</a
            >
            <div class="dropdown-end dropdown leading-none">
                <label
                    tabindex="-1"
                    class="avatar btn btn-circle btn-ghost"
                    for="dropdown"
                >
                    <div class="h-full rounded-full">
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
                    class="menu dropdown-content rounded-box menu-sm z-[1] mt-3 w-48 border border-base-300 bg-base-200 p-2 text-base-content shadow"
                >
                    <li>
                        <button
                            type="button"
                            on:click|preventDefault={() =>
                                router.post('/logout')}
                        >
                            Logout
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    {:else}
        <a class="btn btn-ghost rounded-full" href="/login" use:inertia
            >Sign In</a
        >
    {/if}
</nav>
