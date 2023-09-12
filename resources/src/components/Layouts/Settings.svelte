<script lang="ts" context="module">
    import { writable } from 'svelte/store'

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

<section
    class="container mx-auto my-8 flex items-center gap-2 px-4 text-primary"
>
    <button
        class="btn btn-circle btn-ghost z-10 text-3xl lg:hidden"
        on:click={() => ($opened = true)}
    >
        <Fa icon={faBars} />
    </button>
    <h1 class="z-10 text-4xl md:text-5xl lg:text-6xl">Settings</h1>
</section>

<section class="container drawer mx-auto px-4 lg:drawer-open lg:gap-8">
    <input
        id="settings-drawer"
        type="checkbox"
        class="drawer-toggle"
        bind:checked={$opened}
    />
    <div class="drawer-content">
        <slot />
    </div>
    <div class="drawer-side max-lg:z-30 lg:h-auto">
        <label for="settings-drawer" class="drawer-overlay"></label>
        <ul
            class="menu menu-lg min-h-full w-80 gap-4 text-base-content max-lg:bg-base-200 lg:p-0"
        >
            {#each links as { href, label }}
                <li class="rounded-lg lg:bg-neutral lg:text-neutral-content">
                    <a
                        {href}
                        class:active={$page.url === href}
                        use:inertia
                        on:click={() => ($opened = false)}
                    >
                        {label}
                    </a>
                </li>
            {/each}
        </ul>
    </div>
</section>

<style lang="scss">
    .menu > li > .active {
        @apply lg:bg-base-300 lg:text-base-content;
    }
</style>
