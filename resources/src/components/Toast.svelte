<script lang="ts">
    import { onMount } from 'svelte';
	import { fly } from "svelte/transition"
    import { page } from '@inertiajs/svelte';
    import Fa from 'svelte-fa';
    import { faWarning, faInfoCircle, faCheckCircle, faCircleXmark, faClose } from '@fortawesome/free-solid-svg-icons';

    type Notification = {
        message: string;
        type: 'alert-success' | 'alert-error' | 'alert-info' | 'alert-warning';
        timestamp: Date;
    }

    function icon(type: Notification['type']) {
        return {
            'alert-success': faCheckCircle,
            'alert-error': faCircleXmark,
            'alert-info': faInfoCircle,
            'alert-warning': faWarning
        }[type] ?? faCircleXmark;
    }

    function status(status: string) {
        return {
            'verification-link-sent': 'A new verification link has been sent to the email address you provided during registration.',
        }[status] ?? status;
    }

    let messages = [];

    onMount(() => {
        let interval = setInterval(() => {
            messages = messages.filter(message => {
                return (new Date().getTime() - message.timestamp.getTime()) < 10000
            })
        }, 1000)
        return () => clearInterval(interval);
    });

    page.subscribe(page => {
        if (page.props?.flash?.status) {
            messages.push({
                message: status(page.props.flash.status),
                type: 'alert-info',
                timestamp: new Date(),
            })
        }
        if (page.props?.flash?.error) {
            messages.push({
                message: status(page.props.flash.error),
                type: 'alert-error',
                timestamp: new Date(),
            })
        }
        if (page.props?.flash?.success) {
            messages.push({
                message: status(page.props.flash.success),
                type: 'alert-success',
                timestamp: new Date(),
            })
        }
        messages = messages;
    });
</script>

<div class="toast toast-top toast-end">
    {#each messages as message, i}
        <div class="alert {message.type}" out:fly={{x: "100%"}}>
            <Fa icon={icon(message.type)} />
            <span>{message.message}</span>
            <div>
                <button class="btn btn-circle btn-xs btn-ghost" on:click={() => messages = messages.slice(0, i)}>
                    <Fa icon={faClose} />
                </button>
            </div>
        </div>
    {/each}
</div>
