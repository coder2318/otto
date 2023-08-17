<script lang="ts">
    import type { Writable } from 'svelte/store'
    import type { Honeypot } from '@/service/honeypot'

    export let form: Writable<any>
    export let honeypot: Honeypot | null = null
</script>

{#if honeypot?.enabled}
    <div class="hidden">
        <input
            type="text"
            bind:value={$form[honeypot?.nameFieldName]}
            name="honeypot.nameFieldName"
            id="honeypot.nameFieldName"
        />
        <input type="text" bind:value={$form[honeypot?.validFromFieldName]} />
    </div>
{/if}
