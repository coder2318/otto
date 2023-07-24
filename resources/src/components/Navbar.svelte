<script lang="ts">
    import { inertia, router, page } from '@inertiajs/svelte';
    import Logo from "./SVG/logo.svg.svelte";
    import User from './SVG/user.svg.svelte';

    $: user = $page.props.auth.user;
</script>

<div class="navbar bg-primary text-primary-content">
    <div class="flex-1">
        <a class="btn btn-ghost normal-case text-xl" href="/" use:inertia>
            <Logo class="h-full" />
        </a>
    </div>
    {#if user}
        <div class="flex-none gap-2">
            <span>{user.email}</span>
            <div class="dropdown dropdown-end">
                <label tabindex="-1" class="btn btn-ghost btn-circle avatar" for="dropdown">
                    <div class="w-10 rounded-full">
                        {#if user.avatar}
                            <img src={user.avatar} alt="avatar"/>
                        {:else}
                            <User class="bg-secondary" pathClass="fill-secondary-content"/>
                        {/if}
                    </div>
                </label>
                <ul tabindex="-1" id="dropdown" class="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content bg-base-200 text-base-content rounded-box w-48 border border-base-300">
                    <li>
                        <button type="button" on:click|preventDefault={() => router.post('/logout')}>
                            Logout
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    {:else}
        <a class="btn btn-ghost rounded-full" href="/login" use:inertia>Login</a>
        <a class="btn btn-secondary btn-outline rounded-full" href="/register" use:inertia>Start Writing</a>
    {/if}
</div>
