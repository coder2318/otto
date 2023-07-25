declare module '*.md' {
    import { SvelteComponent } from 'svelte'
    export const frontmatter : Object
    export default SvelteComponent
}
