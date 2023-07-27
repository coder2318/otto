<script lang="ts">
    import { inertia } from '@inertiajs/svelte';
    export let meta = null;
    export let buttonClass = "";
    export let activeClass = "btn-primary";
    let className = "";
    export { className as class };
</script>

{#if meta}
    <div class="join {className}" {...$$restProps}>
        {#each meta?.links as link}
            <a
                href={link.url}
                class="join-item btn {link.active || !link.url ? 'btn-disabled' : ''} {meta.current_page == link.label ? activeClass : buttonClass}"
                use:inertia>
                {@html link.label}
            </a>
        {/each}
    </div>
{/if}
