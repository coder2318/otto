<script lang="ts">
    import { useForm } from '@inertiajs/svelte'
    import Fa from 'svelte-fa'
    import { faClose, faEnvelope } from '@fortawesome/free-solid-svg-icons'

    export let story_id: number
    let modal: HTMLDialogElement
    let question: App.TimelineQuestion = null

    const form = useForm({
        name: '',
        email: '',
    })

    function submit() {
        const url = question.chapter
            ? `/chapters/${question.chapter.id}/invite`
            : `/stories/${story_id}/questions/${question.id}/invite`

        $form.post(url, {
            onSuccess: () => {
                modal.close()
            },
        })
    }

    export function invite(q: App.TimelineQuestion) {
        modal.showModal()
        question = q
    }
</script>

<dialog bind:this={modal} class="modal">
    <div class="modal-box">
        <form method="dialog">
            <button class="btn btn-circle btn-neutral float-right"><Fa icon={faClose} /></button>
        </form>
        <h3 class="text-2xl text-primary">Get a <i>Friend's Perspective</i></h3>
        <p class="py-4">
            <span class="flex gap-2 text-primary">
                <Fa icon={faEnvelope} />
                Send Request on Email
            </span>
        </p>
        <form class="flex flex-col gap-4" on:submit|preventDefault={submit}>
            <div class="form-control">
                <input
                    name="name"
                    type="text"
                    placeholder="Name"
                    class="input input-bordered"
                    required
                    bind:value={$form.name}
                />
                {#if $form.errors.name}
                    <span class="label-text-alt mt-1 text-left text-error">
                        {$form.errors.name}
                    </span>
                {/if}
            </div>
            <div class="form-control">
                <input
                    name="email"
                    type="email"
                    placeholder="Email"
                    class="input input-bordered"
                    required
                    bind:value={$form.email}
                />
                {#if $form.errors.email}
                    <span class="label-text-alt mt-1 text-left text-error">
                        {$form.errors.email}
                    </span>
                {/if}
            </div>
            <button type="submit" class="btn btn-primary">Send Request</button>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
