<script lang="ts">
    import Navbar from '@/components/Dashboard/Navbar.svelte';
</script>

<div class="min-h-screen flex flex-col bg-base-200">
    <Navbar />
    <slot />
</div>
