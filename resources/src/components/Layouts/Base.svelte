<script lang="ts">
    import { page } from '@inertiajs/svelte'
    import Loading from '../Loading.svelte'
    import Notifications from '../Notifications.svelte'
    import Toast from '../Toast.svelte'
</script>

<svelte:head>
    <meta name="csrf-token" content={$page.props.csrf_token} />"
</svelte:head>

<Notifications>
    <slot />
</Notifications>

<Toast />
<Loading />
