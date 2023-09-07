<script lang="ts">
    import Fa from 'svelte-fa'
    import { faEye, faEyeSlash } from '@fortawesome/free-solid-svg-icons'
    import { onMount } from 'svelte'

    export let value: string
    export let type: string

    let hidden: boolean = type === 'password'
    let input: HTMLInputElement
    const switchType = type === 'password' ? 'text' : 'password'

    onMount(() => {
        input.type = type
    })

    function toggle(set = null) {
        hidden = set === null ? !hidden : set
        input.type = hidden ? 'password' : switchType
    }
</script>

<span class="relative">
    <input bind:value bind:this={input} {...$$restProps} />

    <button
        type="button"
        on:click={() => toggle()}
        class="btn btn-square btn-ghost btn-sm absolute bottom-0 right-2 top-0 m-auto"
        tabindex="-1"
    >
        <Fa icon={hidden ? faEye : faEyeSlash} />
    </button>
</span>
