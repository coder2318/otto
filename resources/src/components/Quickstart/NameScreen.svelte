<script lang="ts">
    import { fade } from 'svelte/transition'
    import { createEventDispatcher } from 'svelte'
    import { imask } from '@imask/svelte'
    const dispatch = createEventDispatcher()
    export const question = null
    export let form
</script>

<form
    class="nameScreen flex h-full w-full flex-1 flex-col items-center justify-center"
    in:fade
    on:submit|preventDefault={() => dispatch('next')}
>
    <div class="items-left flex flex-1 flex-col justify-center">
        <div class="form-control">
            <label class=" label flex-col items-start whitespace-nowrap font-serif md:flex-row">
                <span class="fz_h2 label-text">{$form.first_name ? 'First Name:' : 'What is your'}</span>
                <input
                    type="text"
                    bind:value={$form.first_name}
                    placeholder="First Name"
                    class="input w-full italic md:input-ghost lg:input-lg md:px-0"
                    name="first_name"
                    id="first_name"
                    required
                />
            </label>
        </div>
        <div class="form-control">
            <label class="label flex-col items-start whitespace-nowrap font-serif md:flex-row">
                <span class="fz_h2 label-text">{$form.last_name ? 'Last Name:' : 'What is your'}</span>
                <input
                    type="text"
                    bind:value={$form.last_name}
                    placeholder="Last Name"
                    class="input w-full italic md:input-ghost lg:input-lg md:px-0"
                    name="last_name"
                    id="last_name"
                    required
                />
            </label>
        </div>
        <div class="form-control">
            <label class="label font-serif">
                <span class="fz_h2 label-text">What is your birthday?</span>
                <input
                    type="text"
                    use:imask={{ mask: '00/00/0000' }}
                    pattern="\d&lcub;1,2&rcub;/\d&lcub;1,2&rcub;/\d&lcub;4&rcub;"
                    bind:value={$form.birth_date}
                    placeholder="DD/MM/YYYY"
                    class="input w-full italic md:input-ghost lg:input-lg md:px-0"
                    name="birth_date"
                    id="birth_date"
                    required
                />
            </label>
        </div>
        <button type="submit" class="otto-btn-primary">Continue</button>
    </div>
</form>

<style lang="scss">
    .nameScreen {
        .otto-btn-primary {
            height: 48px;
            width: 200px;
            font-size: 16px;
            margin-bottom: 30px;
        }
        .label-text {
            color: #0c345c;
            white-space: nowrap;
            margin-right: 15px;
        }

        .form-control {
            margin-bottom: 34px;
            label {
                padding: 0;
            }
            input {
                font-size: 60px;
                padding-left: 15px;
            }
        }
    }

    @media (max-width: 1280px) {
        .nameScreen {
            .form-control {
                input {
                    font-size: 48px;
                }
                label {
                    flex-direction: column;
                    align-items: flex-start;
                }
            }
            .label-text {
                margin-right: 0;
                margin-bottom: 5px;
            }
        }
    }
    @media (max-width: 767px) {
        .nameScreen {
            .form-control {
                input {
                    font-size: 36px;
                }
            }
            .items-left {
                width: 100%;
            }
        }
    }
</style>
