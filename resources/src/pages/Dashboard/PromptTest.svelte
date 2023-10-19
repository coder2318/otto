<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    const data = {
        prompt: '',
        question: '',
        content: '',
    }

    const controller = new AbortController()
    let loading = false
    let response = ''

    async function submit() {
        if (loading) {
            return
        }

        response = ''
        loading = true

        let res

        try {
            res = await fetch('/api/test-prompt', {
                method: 'POST',
                signal: controller.signal,
                body: JSON.stringify(data),
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json, text/plain',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
        } catch (err) {
            return (loading = false)
        }

        if (res.status !== 200) {
            return (loading = false)
        }

        const reader = await res.body.pipeThrough(new TextDecoderStream()).getReader()

        reader.read().then(function pump({ done, value }) {
            if (done) {
                return (loading = false)
            }
            response += value
            return reader.read().then(pump)
        })
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Prompt Testing</title>
</svelte:head>

<main class="container mx-auto mt-8 px-4">
    <div class="card m-4 mx-auto rounded-xl bg-base-200 px-4">
        <form class="card-body gap-4" on:submit|preventDefault={submit}>
            <h1 class="text-2xl text-primary">Prompt Testing</h1>

            <div class="form-control">
                <label for="prompt" class="label">
                    <span class="label-text">Prompt</span>
                </label>
                <textarea
                    class="textarea textarea-bordered"
                    bind:value={data.prompt}
                    name="prompt"
                    rows="3"
                    placeholder="Enter your prompt here..."
                    required
                />
            </div>

            <div class="form-control">
                <label for="question" class="label">
                    <span class="label-text">Question</span>
                </label>
                <input
                    type="text"
                    class="input input-bordered"
                    bind:value={data.question}
                    name="question"
                    required
                    placeholder="Enter your question here..."
                />
            </div>

            <div class="form-control">
                <label for="content" class="label">
                    <span class="label-text">Content</span>
                </label>
                <textarea
                    class="textarea textarea-bordered"
                    bind:value={data.content}
                    name="content"
                    rows="3"
                    placeholder="Enter your content here..."
                    required
                />
            </div>

            <div class="card-actions">
                <button class="btn btn-primary" type="submit" disabled={loading}
                    >{#if loading}
                        <span class="loading loading-spinner" />
                    {/if}Submit</button
                >
            </div>

            {#if response}
                <pre class="whitespace-pre-wrap rounded-lg bg-neutral p-4 text-primary">{response}</pre>
            {/if}
        </form>
    </div>
</main>
