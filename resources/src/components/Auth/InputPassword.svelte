<script lang="ts">
    import Fa from 'svelte-fa'
    import { faEye, faEyeSlash } from '@fortawesome/free-solid-svg-icons'

    export let value: string
    let hidden: boolean = $$restProps.type === 'password'
    let input: HTMLInputElement
    let type = $$restProps.type === 'password' ? 'text' : 'password'

    function toggle() {
        hidden = !hidden
        input.type = hidden ? 'password' : type
    }
</script>

<span class="relative">
    <input bind:value bind:this={input} {...$$restProps} />

    <button
        type="button"
        on:click={toggle}
        class="btn btn-square btn-ghost btn-sm absolute bottom-0 right-2 top-0 m-auto"
        tabindex="-1"
    >
        <Fa icon={hidden ? faEye : faEyeSlash} />
    </button>
</span>
