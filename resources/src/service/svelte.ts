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
