<script lang="ts">
    import { Editor } from '@tiptap/core'
    import { onMount, onDestroy, createEventDispatcher } from 'svelte'
    import StarterKit from '@tiptap/starter-kit'
    import Focus from '@tiptap/extension-focus'
    import Placeholder from '@tiptap/extension-placeholder'
    import { strToHtml } from '@/service/helpers'
    import Image from '@tiptap/extension-image'

    const dispatch = createEventDispatcher()

    let fileInput: HTMLInputElement
    let element: HTMLElement
    let wrapper: HTMLElement
    let toolbar: HTMLElement
    let actualParent: HTMLElement = document

    const NAVBAR_HEIGHT = 99
    const TOOLBAR_PADDING_TOP = 16

    export let editor: Editor = null
    export let content = ''
    export let autofocus = false

    let contentType = 'text'

    const ImageResize = Image.extend({
        addAttributes() {
            return {
                src: {
                    default: null,
                },
                alt: {
                    default: null,
                },
                style: {
                    default:
                        'max-width: 100%; height: auto; cursor: pointer; margin-top: 0 !important; margin-bottom: 0 !important; position: relative;',
                    parseHTML: (element) => {
                        const width = element.getAttribute('width')
                        return width
                            ? `max-width: ${width}px; height: auto; cursor: pointer; margin-top: 0 !important; margin-bottom: 0 !important; position: relative;`
                            : `${element.style.cssText}`
                    },
                },
                title: {
                    default: null,
                },
                loading: {
                    default: null,
                },
                srcset: {
                    default: null,
                },
                sizes: {
                    default: null,
                },
                crossorigin: {
                    default: null,
                },
                usemap: {
                    default: null,
                },
                ismap: {
                    default: null,
                },
                width: {
                    default: null,
                },
                height: {
                    default: null,
                },
                referrerpolicy: {
                    default: null,
                },
                longdesc: {
                    default: null,
                },
                decoding: {
                    default: null,
                },
                class: {
                    default: null,
                },
                id: {
                    default: null,
                },
                name: {
                    default: null,
                },
                draggable: {
                    default: true,
                },
                tabindex: {
                    default: null,
                },
                'aria-label': {
                    default: null,
                },
                'aria-labelledby': {
                    default: null,
                },
                'aria-describedby': {
                    default: null,
                },
            }
        },
        addNodeView() {
            return ({ node, editor, getPos }) => {
                const {
                    view,
                    options: { editable },
                } = editor
                const { style } = node.attrs
                const $wrapper = document.createElement('div')
                const $container = document.createElement('div')
                const $caption = document.createElement('div')
                const $img = document.createElement('img')
                const iconStyle = 'width: 24px; height: 24px; cursor: pointer;'
                const dispatchNodeView = () => {
                    if (typeof getPos === 'function') {
                        const newAttrs = Object.assign(Object.assign({}, node.attrs), {
                            style: `${$img.style.cssText}`,
                        })
                        view.dispatch(view.state.tr.setNodeMarkup(getPos(), null, newAttrs))
                    }
                }
                const paintPositionContoller = () => {
                    const $postionController = document.createElement('div')
                    const $leftController = document.createElement('img')
                    const $centerController = document.createElement('img')
                    const $rightController = document.createElement('img')
                    const $deleteController = document.createElement('img')

                    if (node?.attrs?.title ?? null) {
                        $caption.setAttribute(
                            'style',
                            `position: absolute; bottom: -2px; background: #fff; width: 100%; opacity:.8;`
                        )
                    }

                    const controllerMouseOver = (e) => {
                        e.target.style.opacity = 0.3
                    }
                    const controllerMouseOut = (e) => {
                        e.target.style.opacity = 1
                    }
                    $postionController.setAttribute(
                        'style',
                        'min-width: 170px;position: absolute; top: 0%; left: 50%;  height: 25px; z-index: 999; background-color: rgba(255, 255, 255, 0.7); border-radius: 4px; border: 2px solid #6C6C6C; cursor: pointer; transform: translate(-50%, -50%); display: flex; justify-content: space-between; align-items: center; padding: 0 10px;'
                    )
                    $leftController.setAttribute(
                        'src',
                        'https://fonts.gstatic.com/s/i/short-term/release/materialsymbolsoutlined/format_align_left/default/20px.svg'
                    )
                    $leftController.setAttribute('style', iconStyle)
                    $leftController.addEventListener('mouseover', controllerMouseOver)
                    $leftController.addEventListener('mouseout', controllerMouseOut)
                    $centerController.setAttribute(
                        'src',
                        'https://fonts.gstatic.com/s/i/short-term/release/materialsymbolsoutlined/format_align_center/default/20px.svg'
                    )
                    $centerController.setAttribute('style', iconStyle)
                    $centerController.addEventListener('mouseover', controllerMouseOver)
                    $centerController.addEventListener('mouseout', controllerMouseOut)
                    $rightController.setAttribute(
                        'src',
                        'https://fonts.gstatic.com/s/i/short-term/release/materialsymbolsoutlined/format_align_right/default/20px.svg'
                    )
                    $rightController.setAttribute('style', iconStyle)
                    $rightController.addEventListener('mouseover', controllerMouseOver)
                    $rightController.addEventListener('mouseout', controllerMouseOut)
                    $leftController.addEventListener('click', () => {
                        $img.setAttribute('style', `${$img.style.cssText} margin: 0 auto 0 0;`)
                        dispatchNodeView()
                    })
                    $centerController.addEventListener('click', () => {
                        $img.setAttribute('style', `${$img.style.cssText} margin: 0 auto;`)
                        dispatchNodeView()
                    })
                    $rightController.addEventListener('click', () => {
                        $img.setAttribute('style', `${$img.style.cssText} margin: 0 0 0 auto;`)
                        dispatchNodeView()
                    })

                    $deleteController.setAttribute(
                        'src',
                        'https://fonts.gstatic.com/s/i/short-term/release/materialsymbolsoutlined/delete/default/20px.svg'
                    )
                    $deleteController.setAttribute('style', iconStyle + ' margin-left: 2vw')
                    $deleteController.addEventListener('mouseover', controllerMouseOver)
                    $deleteController.addEventListener('mouseout', controllerMouseOut)

                    $deleteController.addEventListener('click', (event) => {
                        let id = node?.attrs?.id

                        dispatch('removeImage', { id })

                        dispatchNodeView()

                        editor.commands.deleteSelection()
                    })

                    $postionController.appendChild($leftController)
                    $postionController.appendChild($centerController)
                    $postionController.appendChild($rightController)
                    $postionController.appendChild($deleteController)
                    $container.appendChild($postionController)
                }
                $wrapper.setAttribute('style', `display: flex;`)
                $wrapper.appendChild($container)
                $container.setAttribute('style', `${style}`)
                $container.appendChild($img)

                if (node?.attrs?.title ?? null) {
                    $caption.setAttribute(
                        'style',
                        `position: absolute; bottom: 0; background: #fff; width: 100%; opacity:.8`
                    )
                    $caption.setAttribute('class', 'p-2 italic')
                    $caption.appendChild(document.createTextNode(node.attrs.title))

                    $container.appendChild($caption)
                }

                Object.entries(node.attrs).forEach(([key, value]) => {
                    if (value === undefined || value === null) return
                    $img.setAttribute(key, value)
                })
                if (!editable) return { dom: $img }
                const dotsPosition = [
                    'top: -4px; left: -4px; cursor: nwse-resize;',
                    'top: -4px; right: -4px; cursor: nesw-resize;',
                    'bottom: -4px; left: -4px; cursor: nesw-resize;',
                    'bottom: -4px; right: -4px; cursor: nwse-resize;',
                ]
                let isResizing = false
                let startX, startWidth, startHeight
                $container.addEventListener('click', () => {
                    if ($container.childElementCount > 3) {
                        for (let i = 0; i < 5; i++) {
                            $container.removeChild($container.lastChild)
                        }
                    }
                    paintPositionContoller()
                    $container.setAttribute(
                        'style',
                        `position: relative; border: 1px dashed #6C6C6C; ${style} cursor: pointer;`
                    )
                    Array.from({ length: 4 }, (_, index) => {
                        const $dot = document.createElement('div')
                        $dot.setAttribute(
                            'style',
                            `position: absolute; width: 9px; height: 9px; border: 1.5px solid #6C6C6C; border-radius: 50%; ${dotsPosition[index]}`
                        )
                        $dot.addEventListener('mousedown', (e) => {
                            e.preventDefault()
                            isResizing = true
                            startX = e.clientX
                            startWidth = $container.offsetWidth
                            startHeight = $container.offsetHeight
                            const onMouseMove = (e) => {
                                if (!isResizing) return
                                const deltaX = index % 2 === 0 ? -(e.clientX - startX) : e.clientX - startX
                                const aspectRatio = startWidth / startHeight
                                const newWidth = startWidth + deltaX
                                const newHeight = newWidth / aspectRatio
                                $container.style.width = newWidth + 'px'
                                $container.style.height = newHeight + 'px'
                                $img.style.width = newWidth + 'px'
                                $img.style.height = newHeight + 'px'
                            }
                            const onMouseUp = () => {
                                if (isResizing) {
                                    isResizing = false
                                }
                                dispatchNodeView()
                                document.removeEventListener('mousemove', onMouseMove)
                                document.removeEventListener('mouseup', onMouseUp)
                            }
                            document.addEventListener('mousemove', onMouseMove)
                            document.addEventListener('mouseup', onMouseUp)
                        })
                        $container.appendChild($dot)
                    })
                })
                document.addEventListener('click', (e) => {
                    const $target = e.target
                    const isClickInside = $container.contains($target) || $target.style.cssText === iconStyle
                    if (!isClickInside) {
                        const containerStyle = $container.getAttribute('style')
                        const newStyle =
                            containerStyle === null || containerStyle === void 0
                                ? void 0
                                : containerStyle.replace('border: 1px dashed #6C6C6C;', '')
                        $container.setAttribute('style', newStyle)
                        if ($container.childElementCount > 3) {
                            for (let i = 0; i < 5; i++) {
                                $container.removeChild($container.lastChild)
                            }
                        }
                    }
                })
                return {
                    dom: $wrapper,
                }
            }
        },
    })

    const handleScroll = () => {
        const toolbarTop = wrapper.getBoundingClientRect().top

        const stickyEdge = NAVBAR_HEIGHT + TOOLBAR_PADDING_TOP

        if (toolbarTop <= stickyEdge) {
            toolbar.style.position = 'fixed'
            toolbar.style.top = `${stickyEdge}px`
            toolbar.style.width = `${wrapper.offsetWidth}px`
        } else {
            toolbar.style.position = 'static'
        }
    }

    onMount(() => {
        let contentType = (element.getAttribute('contentType') ?? 'text').toLowerCase()
        let foundHtmlTags = content.search('<p>') !== -1

        if (contentType === 'text' || !foundHtmlTags) {
            content = strToHtml(content, true)
        }
        editor = new Editor({
            editorProps: {
                attributes: {
                    class: 'focus:outline-none',
                },
            },
            element: element,
            extensions: [
                StarterKit,
                Placeholder.configure({
                    placeholder: element.getAttribute('placeholder') ?? 'Write something...',
                }),
                // @ts-ignore
                Focus,
                Image,
                ImageResize,
            ],
            content: content,
            onTransaction: () => (editor = editor),
            onCreate: ({ editor }) => {
                if (autofocus) {
                    editor.chain().focus('end').run()
                }
            },
            onUpdate: ({ editor }) => {
                let editorContent =
                    contentType === 'html' ? editor.getHTML() : editor.getText({ blockSeparator: '\n\n' })

                if (editorContent !== content) {
                    content = editorContent
                }
            },
        })

        if (actualParent) {
            actualParent.addEventListener('scroll', handleScroll)
        }

        return () => editor?.destroy()
    })

    function updateContent(content: string) {
        if (editor) {
            let editorContent = contentType === 'html' ? editor.getHTML() : editor.getText({ blockSeparator: '\n\n' })
            if (editorContent !== content) {
                const { from, to } = editor.state.selection
                editor.commands.setContent(content, false)
                editor.commands.setTextSelection({ from, to })
            }
        } else {
            return
        }
    }

    function uploadImage(event) {
        dispatch('uploadImage', {
            files: event.target.files,
            callback: function (image) {
                if (editor && image) {
                    editor.chain().focus().setImage({ src: image.url, title: image.caption, id: image.id }).run()

                    fileInput.value = null
                } else {
                    return
                }
            },
        })
    }

    onDestroy(() => {
        if (actualParent) {
            actualParent.removeEventListener('scroll', handleScroll)
        }
    })

    $: updateContent(content)
</script>

<div bind:this={wrapper} class="prose mx-auto w-full max-w-none focus:outline-none">
    <input bind:this={fileInput} type="file" accept="image/*" class="hidden" on:change={uploadImage} />

    <slot name="top" />
    {#if editor}
        <div bind:this={toolbar} class="z-10 bg-transparent">
            <button
                on:click={() => editor.chain().focus().toggleBold().run()}
                disabled={!editor.can().chain().focus().toggleBold().run()}
                class={editor.isActive('bold') ? 'is-active' : ''}
            >
                bold
            </button>
            <button
                on:click={() => editor.chain().focus().toggleItalic().run()}
                disabled={!editor.can().chain().focus().toggleItalic().run()}
                class={editor.isActive('italic') ? 'is-active' : ''}
            >
                italic
            </button>
            <button
                on:click={() => editor.chain().focus().toggleHeading({ level: 1 }).run()}
                class={editor.isActive('heading', { level: 1 }) ? 'is-active' : ''}
            >
                h1
            </button>
            <button
                on:click={() => editor.chain().focus().toggleHeading({ level: 2 }).run()}
                class={editor.isActive('heading', { level: 2 }) ? 'is-active' : ''}
            >
                h2
            </button>
            <button
                on:click={() => editor.chain().focus().toggleHeading({ level: 3 }).run()}
                class={editor.isActive('heading', { level: 3 }) ? 'is-active' : ''}
            >
                h3
            </button>
            <button
                on:click={() => editor.chain().focus().toggleHeading({ level: 4 }).run()}
                class={editor.isActive('heading', { level: 4 }) ? 'is-active' : ''}
            >
                h4
            </button>
            <button
                on:click={() => editor.chain().focus().toggleHeading({ level: 5 }).run()}
                class={editor.isActive('heading', { level: 5 }) ? 'is-active' : ''}
            >
                h5
            </button>
            <button
                on:click={() => editor.chain().focus().toggleHeading({ level: 6 }).run()}
                class={editor.isActive('heading', { level: 6 }) ? 'is-active' : ''}
            >
                h6
            </button>
            <button
                on:click={() => editor.chain().focus().toggleBulletList().run()}
                class={editor.isActive('bulletList') ? 'is-active' : ''}
            >
                bullet list
            </button>
            <button
                on:click={() => editor.chain().focus().toggleOrderedList().run()}
                class={editor.isActive('orderedList') ? 'is-active' : ''}
            >
                ordered list
            </button>
            <button on:click={() => fileInput.click()} class={editor.isActive('orderedList') ? 'is-active' : ''}>
                add image
            </button>
        </div>
    {/if}
    <div class="w-full" bind:this={element} {...$$restProps} />
</div>

<style lang="scss">
    :global(.tiptap p.is-editor-empty:first-child::before) {
        @apply pointer-events-none float-left h-0 text-neutral-content/60;
        content: attr(data-placeholder);
    }

    button {
        font-size: inherit;
        font-family: inherit;
        color: #000;
        margin: 0.1rem;
        border: 1px solid black;
        border-radius: 0.3rem;
        padding: 0.1rem 0.4rem;
        background: white;
        accent-color: black;
    }

    button.is-active {
        background-color: black;
        color: white;
    }
</style>
