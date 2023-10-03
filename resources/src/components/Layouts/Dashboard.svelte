<script lang="ts">
    import Navbar from '@/components/Dashboard/Navbar.svelte'
</script>

<div class="min-h-screen flex-col bg-base-100">
    <Navbar />
    <slot />
</div>
