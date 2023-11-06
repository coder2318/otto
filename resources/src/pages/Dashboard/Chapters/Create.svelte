<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import qs from 'qs'
    import { fade } from 'svelte/transition'
    import { useForm, inertia, page } from '@inertiajs/svelte'
    import Fa from 'svelte-fa'
    import { faArrowLeft } from '@fortawesome/free-solid-svg-icons'

    export let story: { data: App.Story }
    export let timelines: { data: App.Timeline[] }

    let query = qs.parse($page.url.replace(window.location.pathname, '').slice(1))

    let el: HTMLFormElement
    const form = useForm({
        title: '',
        timeline_id: query.timeline_id ?? null,
    })

    const getRedirect = () =>
        ({
            record: 'dashboard.chapters.record',
            upload: 'dashboard.chapters.upload',
            write: 'dashboard.chapters.write',
        })[query.redirect] ?? null

    function submit() {
        $form.clearErrors()
        $form.transform((data) => ({ ...data, redirect: getRedirect() })).post(`/stories/${story.data.id}/chapters`)
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Create New Chapter</title>
</svelte:head>

<form on:submit|preventDefault={submit} bind:this={el} in:fade class="flex flex-1 flex-col items-center justify-center">
    <main class="container card relative m-4 mx-auto h-full rounded-xl bg-base-200 px-4">
        <svg
            xmlns="http://www.w3.org/2000/svg"
            width="75"
            height="121"
            viewBox="0 0 75 121"
            fill="none"
            class="absolute bottom-0 left-0 z-10"
        >
            <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M37.9472 54.6863C44.6642 70.2367 50.0021 87.607 42.4628 102.776C34.1072 119.587 17.4463 132.392 -1.28674 133.624C-19.2739 134.808 -33.1338 121.039 -46.1897 108.61C-59.8975 95.56 -76.7101 82.0394 -75.2982 63.1661C-73.8491 43.7948 -57.1812 29.1006 -39.6205 20.796C-24.4075 13.6016 -7.14036 16.9033 8.28038 23.6408C22.0801 29.6701 31.9755 40.8615 37.9472 54.6863Z"
                fill="white"
            />
            <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M39.499 41.0051C46.3876 41.2064 50.2454 48.3605 55.6671 52.6314C62.5092 58.0212 73.5934 60.2255 74.8633 68.863C76.1618 77.6957 67.9523 84.8285 61.266 90.7155C55.1046 96.1404 47.6943 100.022 39.499 99.9999C31.3248 99.978 23.3581 96.6607 17.8479 90.5991C12.5571 84.7788 10.2876 76.6899 11.1947 68.863C12.014 61.7949 17.3 56.6396 22.3613 51.6581C27.349 46.749 32.5172 40.8011 39.499 41.0051Z"
                fill="#F8AD9D"
            />
        </svg>
        <svg
            width="83"
            height="85"
            viewBox="0 0 83 85"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            class="absolute right-0 top-0 z-10"
        >
            <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M58.0883 -7.69024C70.9468 -5.99193 80.3409 2.58279 89.6308 11.6472C101.65 23.3747 120.661 33.9239 117.689 50.4639C114.686 67.1728 93.569 71.6264 77.754 77.738C64.1695 82.9877 49.2994 88.7018 36.4397 81.8621C23.9706 75.2302 21.8639 59.6069 18.0616 45.9895C13.8644 30.9576 4.72141 14.1704 14.0467 1.66426C23.494 -11.0055 42.435 -9.7577 58.0883 -7.69024Z"
                fill="#FFD886"
            />
            <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M14.7103 22.2224C24.3767 14.8792 39.1237 15.6626 49.3465 22.2716C58.6017 28.2551 59.4346 40.7496 60.364 51.7373C61.0854 60.2664 60.0841 69.4216 53.825 75.2209C48.3171 80.3242 39.9769 76.5143 32.5356 77.5214C22.141 78.9282 10.2979 89.6644 2.97995 82.1024C-4.2587 74.6222 6.8452 63.2435 8.84414 53.0396C10.9591 42.2434 5.97088 28.8614 14.7103 22.2224Z"
                fill="white"
            />
        </svg>

        <div class="card-body z-10 gap-4">
            {#if !query.timeline_id}
                <div class="form-control">
                    <label class="label" for="timeline_id">
                        <span class="label-text">Timeline</span>
                    </label>
                    <select
                        bind:value={$form.timeline_id}
                        class:select-error={$form.errors.timeline_id}
                        class="select select-bordered select-ghost"
                        name="timeline_id"
                    >
                        <option value={null} disabled>Select Timeline...</option>
                        {#each timelines.data as timeline}
                            <option value={timeline.id}>{timeline.title}</option>
                        {/each}
                    </select>
                    {#if $form.errors.timeline_id}
                        <span class="label-text-alt mt-1 text-left text-error">
                            {$form.errors.timeline_id}
                        </span>
                    {/if}
                </div>
            {/if}
            <div class="form-control">
                <textarea
                    class="textarea textarea-bordered textarea-ghost font-serif text-5xl"
                    class:input-error={$form.errors.title}
                    bind:value={$form.title}
                    name="title"
                    rows="10"
                    placeholder="Enter your question here..."
                />
                {#if $form.errors.title}
                    <span class="label-text-alt mt-1 text-left text-error">
                        {$form.errors.title}
                    </span>
                {/if}
            </div>
        </div>
    </main>

    <section class="container mx-auto mb-8 flex justify-between">
        <a href="/stories/{story.data.id}/chapters" class="btn btn-neutral rounded-full pl-0 font-normal" use:inertia>
            <span class="badge mask badge-accent mask-circle p-4"><Fa icon={faArrowLeft} /></span> Go Back
        </a>

        {#if $form.isDirty}
            <button type="submit" class="btn btn-secondary rounded-full" disabled={$form.processing}>
                {#if $form.processing}
                    <span class="loading loading-spinner" />
                {/if}
                Save
            </button>
        {/if}
    </section>
</form>
