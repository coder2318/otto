<script lang="ts">
    import Navbar from '@/components/Dashboard/Navbar.svelte';
</script>

<div class="min-h-screen flex flex-col">
    <Navbar />
    <slot />
</div>
