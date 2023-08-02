<script lang="ts">
    import Navbar from '@/components/Dashboard/Navbar.svelte'
</script>

<div class="flex min-h-screen flex-col bg-base-200">
    <Navbar />
    <slot />
</div>
