<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import Write from '@/components/Chapters/Write.svelte'

    export let transcriptions: App.TranscriptionsData | null = null
    export let chapter: { data: App.Chapter }
</script>

<Write type={'guests'} routePrefix={'/guests/chapters'} {chapter} {transcriptions} />
