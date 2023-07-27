<script lang="ts">
    import Loading from "../Loading.svelte";
    import Toast from "../Toast.svelte";
</script>

<slot />

<Toast />
<Loading />
