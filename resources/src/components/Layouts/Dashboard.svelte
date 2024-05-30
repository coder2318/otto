<script lang="ts">
    import Navbar from '@/components/Dashboard/Navbar.svelte'
</script>

<div id="dashboardContainer" class="flex min-h-screen flex-col bg-base-100">
    <Navbar />
    <slot />
</div>
