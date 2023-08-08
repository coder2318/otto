<script lang="ts" context="module">
    import { writable } from 'svelte/store'
    export type Toast = {
        message: string
        type: 'alert-success' | 'alert-error' | 'alert-info' | 'alert-warning'
        timestamp?: Date
        autohide?: boolean
    }

    const messages = writable([] as Toast[])

    export function flash(notification: Toast) {
        if (!notification.timestamp) {
            notification.timestamp = new Date()
        }
        if (typeof notification.autohide === 'undefined') {
            notification.autohide = true
        }
        messages.update((messages) => {
            messages.push(notification)
            return messages
        })
    }
</script>

<script lang="ts">
    import { onMount } from 'svelte'
    import { fly } from 'svelte/transition'
    import { page } from '@inertiajs/svelte'
    import Fa from 'svelte-fa'
    import {
        faWarning,
        faInfoCircle,
        faCheckCircle,
        faCircleXmark,
        faClose,
    } from '@fortawesome/free-solid-svg-icons'

    function icon(type: Toast['type']) {
        return (
            {
                'alert-success': faCheckCircle,
                'alert-error': faCircleXmark,
                'alert-info': faInfoCircle,
                'alert-warning': faWarning,
            }[type] ?? faCircleXmark
        )
    }

    function status(status: string) {
        return (
            {
                'verification-link-sent':
                    'A new verification link has been sent to the email address you provided during registration.',
            }[status] ?? status
        )
    }

    onMount(() => {
        let interval = setInterval(() => {
            $messages = $messages.filter((message) => {
                return (
                    !message.autohide ||
                    new Date().getTime() - message.timestamp.getTime() < 5000
                )
            })
        }, 1000)
        return () => clearInterval(interval)
    })

    page.subscribe((page) => {
        const message =
            page.props?.flash?.status ??
            page.props?.flash?.error ??
            page.props?.flash?.message
        const type =
            (page.props?.flash?.status && 'alert-info') ||
            (page.props?.flash?.error && 'alert-warning') ||
            (page.props?.flash?.message && 'alert-success')

        if (!message || !type) {
            return
        }

        $messages.push({
            message: status(message),
            type: type,
            timestamp: new Date(),
            autohide: true,
        })
    })
</script>

<div class="toast toast-end toast-bottom">
    {#each $messages as message, i (i)}
        <div class="alert grid-flow-col {message.type}" out:fly={{ x: '100%' }}>
            <Fa icon={icon(message.type)} />
            <span>{message.message}</span>
            <div>
                <button
                    class="btn btn-circle btn-ghost btn-xs"
                    on:click={() => ($messages = $messages.slice(0, i))}
                >
                    <Fa icon={faClose} />
                </button>
            </div>
        </div>
    {/each}
</div>
