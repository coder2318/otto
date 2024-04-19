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

    node.addEventListener('mousedown', start)
    node.addEventListener('mousemove', drag)
    node.addEventListener('mouseup', stop)
    node.addEventListener('mouseleave', stop)
    node.addEventListener('touchstart', start)
    node.addEventListener('touchmove', drag)
    node.addEventListener('touchend', stop)
    node.addEventListener('touchcancel', stop)
    node.addEventListener('touchleave', stop)

    return {
        destroy: () => {
            node.removeEventListener('mousedown', start)
            node.removeEventListener('mousemove', drag)
            node.removeEventListener('mouseup', stop)
            node.removeEventListener('mouseleave', stop)
            node.removeEventListener('touchstart', start)
            node.removeEventListener('touchmove', drag)
            node.removeEventListener('touchend', stop)
            node.removeEventListener('touchcancel', stop)
            node.removeEventListener('touchleave', stop)
        },
    }

    function start(event: MouseEvent) {
        offset = getMousePosition(event)
        selected = event.currentTarget as SVGElement
        offset.x -= parseFloat(selected.getAttributeNS(null, 'x'))
        offset.y -= parseFloat(selected.getAttributeNS(null, 'y'))
    }

    function drag(event: MouseEvent) {
        if (!selected || !offset) return
        event.preventDefault()
        callback()
        const pos = getMousePosition(event)
        const x = (pos.x - offset.x).toString()

        selected.setAttributeNS(null, 'x', x)
        selected.setAttributeNS(null, 'y', (pos.y - offset.y).toString())

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
