<script lang="ts">
    import { fade } from 'svelte/transition';
    import { createEventDispatcher } from 'svelte';
    import { imask } from '@imask/svelte';
    const dispatch = createEventDispatcher()
    export const question = null;
    export let form
</script>

<form class="flex flex-1 flex-col h-full w-full items-center justify-center" in:fade on:submit|preventDefault={() => dispatch('next')}>
    <div class="flex-1 flex flex-col items-left justify-center">
        <div class="form-control">
            <label class="label md:gap-4 font-serif flex-col items-start md:flex-row whitespace-nowrap">
                <span class="label-text text-2xl lg:text-6xl md:text-5xl">{$form.first_name ? 'First Name:' : 'What is your'}</span>
                <input type="text" bind:value={$form.first_name} placeholder="First Name" class="input md:input-ghost md:px-0 italic lg:input-lg text-3xl lg:text-6xl md:text-5xl w-full" name="first_name" id="first_name" required>
            </label>
        </div>
        <div class="form-control">
            <label class="label md:gap-4 font-serif flex-col items-start md:flex-row whitespace-nowrap">
                <span class="label-text text-2xl lg:text-6xl md:text-5xl">{$form.last_name ? 'Last Name:' : 'What is your'}</span>
                <input type="text" bind:value={$form.last_name} placeholder="Last Name" class="input md:input-ghost md:px-0 italic lg:input-lg text-3xl lg:text-6xl md:text-5xl w-full" name="last_name" id="last_name" required>
            </label>
        </div>
        <div class="form-control">
            <label class="label md:gap-4 font-serif flex-col items-start md:flex-row whitespace-nowrap">
                <span class="label-text text-2xl lg:text-6xl md:text-5xl">What is your birthday?</span>
                <input type="text" use:imask={{ mask: '00/00/0000' }} pattern="\d&lcub;1,2&rcub;/\d&lcub;1,2&rcub;/\d&lcub;4&rcub;" bind:value={$form.birth_date} placeholder="DD/MM/YYYY" class="input md:input-ghost md:px-0 italic lg:input-lg text-3xl lg:text-6xl md:text-5xl w-full" name="birth_date" id="birth_date" required>
            </label>
        </div>
    </div>
    <div class="flex mb-4 items-center justify-center gap-4">
        <button type="submit" class="btn btn-primary w-40">Continue</button>
    </div>
</form>
