<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    export const layout = [Base]
</script>

<script lang="ts">
    import Navbar from '@/components/Static/Navbar.svelte'
    import Footer from '@/components/Static/Footer.svelte'
    import PageHeader from '@/components/Static/PageHeader.svelte'

    const questions = [
        {
            question: 'Lorem Ipsum is simply dummy text of the printing?',
            answer: `It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.`,
        },
        {
            question: 'Lorem Ipsum is simply dummy text of the printing?',
            answer: `It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.`,
        },
        {
            question: 'Lorem Ipsum is simply dummy text of the printing?',
            answer: `It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.`,
        },
        {
            question: 'Lorem Ipsum is simply dummy text of the printing?',
            answer: `It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.`,
        },
        {
            question: 'Lorem Ipsum is simply dummy text of the printing?',
            answer: `It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.`,
        },
    ]
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - FAQ</title>
</svelte:head>

<Navbar class="bg-primary text-primary-content" />

<PageHeader class="mb-16 mt-32 flex justify-center px-4 text-primary lg:mt-48">
    Frequently Asked <i>Questions</i>
</PageHeader>

<main class="container mx-auto mb-20 flex flex-col gap-8 px-4">
    {#each questions as { question, answer }}
        <div class="group collapse collapse-arrow rounded-xl shadow">
            <input type="radio" name="faq-accordion" class="peer" />

            <div
                class="collapse-title bg-neutral-focus p-8 font-serif text-2xl text-primary peer-checked:bg-neutral md:text-3xl lg:text-4xl"
            >
                {question}
            </div>

            <div
                class="collapse-content bg-neutral-focus peer-checked:bg-neutral"
            >
                <p class="md:text-md px-4 pb-4 lg:text-lg">
                    {answer}
                </p>
            </div>
        </div>
    {/each}
</main>

<Footer />
