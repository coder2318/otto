<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Focus from '@/components/Layouts/Focus.svelte'
    export const layout = [Base, Focus]
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Start your story</title>
</svelte:head>

<div class="z-10 flex flex-col flex-1 items-center">123</div>
