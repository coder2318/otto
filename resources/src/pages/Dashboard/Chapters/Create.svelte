<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { useForm, inertia } from '@inertiajs/svelte'
    import Fa from 'svelte-fa'
    import Otto from '@/components/SVG/otto.svg.svelte'
    import {
        faPencil,
        faKeyboard,
        faThumbsUp,
        faArrowLeft,
    } from '@fortawesome/free-solid-svg-icons'
    import FilePond from '@/components/FilePond.svelte'

    export let story: { data: App.Story }

    let el: HTMLFormElement
    const form = useForm({
        title: '',
        cover: null,
    })

    function submit() {
        $form.post(`/stories/${story.data.id}/chapters`)
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Create New Chapter</title>
</svelte:head>

<header
    class="container m-4 mx-auto grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4"
>
    <div class="step-breadcrumb">
        <span class="step-icon">
            <Fa icon={faPencil} />
        </span>
        Edit your question
    </div>
    <div class="step-breadcrumb inactive">
        <span class="step-icon">
            <Fa icon={faKeyboard} />
        </span>
        Tell your story
    </div>
    <div class="step-breadcrumb inactive">
        <span class="step-icon">
            <Otto class="w-4 leading-none" />
        </span>
        Colaborate with OTTO
    </div>
    <div class="step-breadcrumb inactive">
        <span class="step-icon">
            <Fa icon={faThumbsUp} />
        </span>
        Save
    </div>
</header>
<form on:submit|preventDefault={submit} bind:this={el}>
    <main class="container card m-4 mx-auto rounded-xl bg-base-300 px-4">
        <div class="card-body gap-4">
            <div class="form-control">
                <label class="label" for="title">
                    <span class="label-text">Title</span>
                </label>
                <input
                    class="input input-bordered"
                    class:input-error={$form.errors.title}
                    bind:value={$form.title}
                    type="text"
                    name="title"
                    placeholder="Title"
                />
                {#if $form.errors.title}
                    <span class="label-text-alt mt-1 text-left text-error">
                        {$form.errors.title}
                    </span>
                {/if}
            </div>
            <div class="form-control">
                <label class="label" for="cover">
                    <span class="label-text">Cover</span>
                </label>
                <FilePond
                    maxFiles="1"
                    storeAsFile={true}
                    name="cover"
                    onaddfile={(err, data) => ($form.cover = data.file)}
                />
                {#if $form.errors.cover}
                    <span class="label-text-alt mt-1 text-left text-error">
                        {$form.errors.cover}
                    </span>
                {/if}
            </div>
        </div>
    </main>

    <section class="container mx-auto mb-8 flex justify-between">
        <a
            href="/stories/{story.data.id}/chapters"
            class="btn btn-neutral rounded-full pl-0"
            use:inertia
        >
            <span class="badge mask badge-accent mask-circle p-4"
                ><Fa icon={faArrowLeft} /></span
            > Back
        </a>

        {#if $form.title}
            <button type="submit" class="btn btn-secondary rounded-full">
                Save
            </button>
        {/if}
    </section>
</form>

<style lang="scss">
    .step-breadcrumb {
        @apply rounded-none border-b-4 border-b-primary py-4 text-left;

        .step-icon {
            @apply badge mask badge-secondary mask-circle rounded-full p-4;
        }

        &.inactive {
            @apply hidden border-b-primary/20 md:block;

            .step-icon {
                @apply badge-neutral;
            }
        }
    }
</style>
