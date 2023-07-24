declare module '*.md' {
    import { SvelteComponent } from 'svelte'
    export const frontmatter : object
    export default SvelteComponent
}
