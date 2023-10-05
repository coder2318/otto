<script lang="ts">
    import { fade } from 'svelte/transition'
    import { createEventDispatcher } from 'svelte'
    import { imask } from '@imask/svelte'
    const dispatch = createEventDispatcher()
    export const question = null
    export let form
</script>

<form
    class="flex h-full w-full flex-1 flex-col items-center justify-center"
    in:fade
    on:submit|preventDefault={() => dispatch('next')}
>
    <div class="items-left flex flex-1 flex-col justify-center">
        <div class="form-control">
            <label class="label flex-col items-start whitespace-nowrap font-serif md:flex-row md:gap-4">
                <span class="label-text text-2xl md:text-5xl lg:text-6xl"
                    >{$form.first_name ? 'First Name:' : 'What is your'}</span
                >
                <input
                    type="text"
                    bind:value={$form.first_name}
                    placeholder="First Name"
                    class="input w-full text-3xl italic md:input-ghost lg:input-lg md:px-0 md:text-5xl lg:text-6xl"
                    name="first_name"
                    id="first_name"
                    required
                />
            </label>
        </div>
        <div class="form-control">
            <label class="label flex-col items-start whitespace-nowrap font-serif md:flex-row md:gap-4">
                <span class="label-text text-2xl md:text-5xl lg:text-6xl"
                    >{$form.last_name ? 'Last Name:' : 'What is your'}</span
                >
                <input
                    type="text"
                    bind:value={$form.last_name}
                    placeholder="Last Name"
                    class="input w-full text-3xl italic md:input-ghost lg:input-lg md:px-0 md:text-5xl lg:text-6xl"
                    name="last_name"
                    id="last_name"
                    required
                />
            </label>
        </div>
        <div class="form-control">
            <label class="label flex-col items-start whitespace-nowrap font-serif md:flex-row md:gap-4">
                <span class="label-text text-2xl md:text-5xl lg:text-6xl">What is your birthday?</span>
                <input
                    type="text"
                    use:imask={{ mask: '00/00/0000' }}
                    pattern="\d&lcub;1,2&rcub;/\d&lcub;1,2&rcub;/\d&lcub;4&rcub;"
                    bind:value={$form.birth_date}
                    placeholder="DD/MM/YYYY"
                    class="input w-full text-3xl italic md:input-ghost lg:input-lg md:px-0 md:text-5xl lg:text-6xl"
                    name="birth_date"
                    id="birth_date"
                    required
                />
            </label>
        </div>
    </div>
    <div class="mb-4 flex items-center justify-center gap-4">
        <button type="submit" class="btn btn-primary w-40">Continue</button>
    </div>
</form>
