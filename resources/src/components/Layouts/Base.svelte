<script lang="ts">
    import Loading from '../Loading.svelte'
    import Notifications from '../Notifications.svelte'
    import Toast from '../Toast.svelte'
</script>

<Notifications>
    <slot />
</Notifications>

<Toast />
<Loading />
