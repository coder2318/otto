export function autosize(node: HTMLTextAreaElement, options: any = { offset: 0 }) {
    function _resize() {
        node.style.height = 'auto'
        node.style.height = node.scrollHeight + options.offset + 'px'
    }

    _resize()
    node.addEventListener('input', _resize)

    return {
        destroy: () => node.removeEventListener('input', _resize),
    }
}

export function textEdit(node: HTMLElement) {
    const paste = (event: ClipboardEvent) => {
        event.preventDefault()
        const text = event.clipboardData?.getData('text/plain') || ''
        document.execCommand('insertText', false, text)
    }

    node.addEventListener('paste', paste)

    return {
        destroy: () => node.removeEventListener('paste', paste),
    }
}

export function draggable(node: SVGElement, root: SVGElement, callback: Function) {
    let selected: SVGElement | null = null
    let offset: { x: number; y: number } | null = null

    document.addEventListener('mousedown', start)
    document.addEventListener('mousemove', drag)
    document.addEventListener('mouseup', stop)
    document.addEventListener('mouseleave', stop)
    document.addEventListener('touchstart', start)
    document.addEventListener('touchmove', drag)
    document.addEventListener('touchend', stop)
    document.addEventListener('touchcancel', stop)
    document.addEventListener('touchleave', stop)

    return {
        destroy: () => {
            document.removeEventListener('mousedown', start)
            document.removeEventListener('mousemove', drag)
            document.removeEventListener('mouseup', stop)
            document.removeEventListener('mouseleave', stop)
            document.removeEventListener('touchstart', start)
            document.removeEventListener('touchmove', drag)
            document.removeEventListener('touchend', stop)
            document.removeEventListener('touchcancel', stop)
            document.removeEventListener('touchleave', stop)
        },
    }

    function start(event: MouseEvent) {
        let element = event?.target as SVGElement
        if (!element?.hasAttribute('[data-draggable]')) {
            element = element.closest('[data-draggable]')
        }
        selected = element as SVGElement
        offset = getMousePosition(event)
        offset.x -= parseFloat(selected?.getAttributeNS(null, 'x'))
        offset.y -= parseFloat(selected?.getAttributeNS(null, 'y'))
    }

    function drag(event: MouseEvent) {
        if (!selected || !offset) return
        event.preventDefault()
        callback()

        const pos = getMousePosition(event)
        const x = (pos.x - offset.x).toString()
        const y = (pos.y - offset.y).toString()

        selected.setAttributeNS(null, 'x', x)
        selected.setAttributeNS(null, 'y', y)

        selected.setAttributeNS(null, 'width', `calc(100% - ${x.includes('%') ? x : `${Math.abs(+x)}px`})`)

        selected.childNodes.forEach((child) => {
            if (child instanceof SVGTSpanElement && child.getAttributeNS(null, 'x')) {
                child.setAttributeNS(null, 'x', (pos.x - offset.x).toString())
            }
        })
    }

    function stop() {
        selected = null
        offset = null
    }

    function getMousePosition(event: MouseEvent) {
        // @ts-ignore
        const ctm = root.getScreenCTM()

        return {
            x: (event.clientX - ctm.e) / ctm.a,
            y: (event.clientY - ctm.f) / ctm.d,
        }
    }
}
