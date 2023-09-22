<script lang="ts" context="module">
    import { onMount } from 'svelte'
    import { writable } from 'svelte/store'

    export const opened = writable(false)

    export const count = writable(0)
</script>

<script lang="ts">
    import { fly } from 'svelte/transition'
    import { page, inertia, router } from '@inertiajs/svelte'
    import { echo } from '@/service/echo'
    import { dayjs } from '@/service/dayjs'
    import sound from '@/assets/sounds/notification.mp3'
    import Fa from 'svelte-fa'
    import { faClose } from '@fortawesome/free-solid-svg-icons'

    const notifications = writable([])

    notifications.subscribe((value) => ($count = value.length))

    onMount(() => {
        const user = $page?.props?.auth?.user?.data

        $notifications = [...(user?.unreadNotifications ?? [])]

        echo.private(`App.Models.User.${user?.id}`).notification(
            (notification) => {
                $notifications = [notification, ...$notifications]
                const audio = new Audio(sound)
                audio.volume = 0.1
                audio.play()
            }
        )
    })

    function markAsRead(notification = null, close = true) {
        $opened = !close

        router.post(`/notifications/read/${notification?.id ?? ''}`, null, {
            onSuccess: () => {
                if (notification) {
                    return ($notifications = $notifications.filter(
                        (n) => n.id !== notification.id
                    ))
                }
                $notifications = []
            },
        })
    }
</script>

<slot />

<div class="drawer drawer-end z-20">
    <input
        id="notifications-drawer"
        bind:checked={$opened}
        type="checkbox"
        class="drawer-toggle"
    />
    <div class="drawer-side">
        <label for="notifications-drawer" class="drawer-overlay" />
        <ul
            class="flex min-h-full w-96 flex-col items-stretch gap-4 bg-base-200 p-4 text-base-content"
        >
            {#each $notifications as notification (notification.id)}
                <li out:fly={{ x: '100%' }}>
                    <div class="card bg-neutral text-neutral-content">
                        <div class="card-body pt-2">
                            <div class="card-actions -mr-6 justify-end">
                                <button
                                    class="btn btn-circle btn-ghost btn-sm"
                                    type="button"
                                    on:click|preventDefault={() =>
                                        markAsRead(notification, false)}
                                >
                                    <Fa icon={faClose} />
                                </button>
                            </div>
                            <a
                                class="card-title text-base"
                                href={notification.data.url}
                                use:inertia={{
                                    onSuccess: () => markAsRead(notification),
                                }}
                            >
                                {notification.data.title}
                            </a>
                            {#if notification.data.message}
                                <p>{notification.data.message}</p>
                            {/if}
                            <div class="card-actions">
                                <span
                                    >{dayjs(
                                        notification.created_at
                                    ).fromNow()}</span
                                >
                            </div>
                        </div>
                    </div>
                </li>
            {:else}
                <li>
                    <div class="card">
                        <div class="card-body items-center">
                            <p class="text-base-content/60">
                                You have no notifications
                            </p>
                        </div>
                    </div>
                </li>
            {/each}
            <li class="flex flex-1 items-end">
                <button
                    class="btn btn-primary w-full"
                    on:click|preventDefault={() => markAsRead()}
                >
                    Clear
                </button>
            </li>
        </ul>
    </div>
</div>
