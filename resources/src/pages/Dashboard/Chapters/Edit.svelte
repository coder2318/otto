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
    import record from '@/assets/img/chapter-record.jpg'
    import upload from '@/assets/img/chapter-upload.jpg'
    import write from '@/assets/img/chapter-type.jpg'

    export let story: { data: App.Story }
    export let chapter: { data: App.Chapter }

    let el: HTMLFormElement
    const form = useForm({
        title: chapter.data?.title ?? '',
    })

    function submit() {
        $form.patch(`/stories/${story.data.id}/chapters/${chapter.data.id}`)
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {chapter.data.title}</title>
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

<main class="container card m-4 mx-auto rounded-xl bg-base-300 px-4">
    <form
        class="card-body gap-4"
        on:submit|preventDefault={submit}
        bind:this={el}
    >
        <input
            class="input card-title input-ghost font-serif text-2xl text-primary"
            bind:value={$form.title}
            contenteditable="true"
        />
        <div class="grid grid-cols-1 gap-8 p-4 md:grid-cols-3">
            <a class="card bg-neutral transition-transform hover:scale-105">
                <figure>
                    <img src={record} alt="record" />
                </figure>
                <div class="card-body">
                    <h2 class="card-title">Record your Answer</h2>
                </div>
            </a>
            <a class="card bg-neutral transition-transform hover:scale-105">
                <figure>
                    <img src={upload} alt="upload" />
                </figure>
                <div class="card-body">
                    <h2 class="card-title">Upload File</h2>
                </div>
            </a>
            <a class="card bg-neutral transition-transform hover:scale-105">
                <figure>
                    <img src={write} alt="write" />
                </figure>
                <div class="card-body">
                    <h2 class="card-title">Type your Story</h2>
                </div>
            </a>
        </div>
    </form>
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

    {#if $form.title != chapter.data.title}
        <button
            type="submit"
            class="btn btn-secondary rounded-full"
            on:click|preventDefault={() => el.submit()}
        >
            Save
        </button>
    {/if}
</section>

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
