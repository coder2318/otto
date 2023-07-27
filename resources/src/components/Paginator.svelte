<script lang="ts">
    import { inertia } from '@inertiajs/svelte';
    export let meta : App.PaginationMeta = null;
    export let buttonClass = "";
    export let activeClass = "btn-primary";
    let className = "";
    export { className as class };

    function linkStyle(link) {
        let styles = [];

        if (!link.url) {
            styles.push('btn-disabled')
        }

        styles.push(meta.current_page.toString() == link.label ? activeClass : buttonClass);

        return styles.join(' ');
    }
</script>

{#if meta}
    <div class="join {className}" {...$$restProps}>
        {#each meta?.links as link}
            <a
                href={link.url ?? '#'}
                class="join-item btn {linkStyle(link)}"
                use:inertia>
                {@html link.label}
            </a>
        {/each}
    </div>
{/if}
