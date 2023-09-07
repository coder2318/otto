<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    import Settings from '@/components/Layouts/Settings.svelte'
    export const layout = [Base, Dashboard, Settings]
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Change Password</title>
</svelte:head>
