<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import Enhance from '@/components/Chapters/Enhance.svelte'

    export let chapter: { data: App.Chapter }
    export let prompts: { data: App.Prompt[] }
</script>

<Enhance type={'guests'} routePrefix={'/guests/chapters'} {chapter} {prompts} />
