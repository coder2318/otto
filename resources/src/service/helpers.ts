export function range(from: number, to: number | null = null): Array<number> {
    if (to === null) {
        to = from
        from = 0
    }

    return Array.from({ length: to - from }, (_, i) => from + i)
}

export function usd(amount: number, options?: Intl.NumberFormatOptions): string {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        ...options,
    }).format(amount)
}

export function truncate(str: string, num: number): string {
    return str.length > num ? str.slice(0, num) + '...' : str
}

export function msToTime(s: number): string {
    const pad = (n, z = 2) => ('00' + n).slice(-z)
    const ms = s % 1000
    s = (s - ms) / 1000
    const secs = s % 60
    s = (s - secs) / 60
    const mins = s % 60

    return pad(mins) + ':' + pad(secs)
}

export function bytes(bytes: number): string {
    const units = ['bytes', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB']
    let l = 0

    while (bytes >= 1024 && ++l) {
        bytes = bytes / 1024
    }
    return bytes.toFixed(bytes < 10 && l > 0 ? 1 : 0) + ' ' + units[l]
}

export function fileToBase64(file: Blob): Promise<string> {
    return new Promise((resolve, reject) => {
        const reader = new FileReader()
        reader.onload = () => resolve(reader.result as string)
        reader.onerror = reject
        reader.readAsDataURL(file)
    })
}

export function strToHtml(str: string, trim: boolean = false): string {
    return str
        .split('\n\n')
        .map((x) => {
            x = x.replaceAll('\n', '<br/>')
            if (!trim) {
                x = x
                    .replace(/^\s*/, '&nbsp;'.repeat(x.length - x.trimStart().length))
                    .replace(/\s*$/, '&nbsp;'.repeat(x.length - x.trimEnd().length))
            }
            return '<p>' + x.trim() + '</p>'
        })
        .join('')
}

export function strRandom(length: number): string {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
    let result = ''
    for (let i = 0; i < length; i++) {
        result += chars.charAt(Math.floor(Math.random() * chars.length))
    }
    return result
}

export function svgTextWrap(node: SVGTextElement, text: string, maxWidth: number) {
    if (text === null) {
        text = ''
        if (node.children.length > 1) {
            node.childNodes.forEach((n) => (text += n.textContent + ' '))
        } else {
            text = node.textContent
        }
        text = text.trim()
    }

    if (typeof text === 'string') {
        const words = text.split(' ').filter((w) => w)
        const test = document.createElementNS('http://www.w3.org/2000/svg', 'tspan')
        node.innerHTML = ''
        node.appendChild(test)
        const lines = []
        let line = ''

        words.forEach((word) => {
            const testLine = line + word + ' '
            test.innerHTML = testLine

            if (test.getBBox().width <= maxWidth) {
                line = testLine
            } else {
                lines.push(line.trim())
                line = word + ' '
            }
        })

        lines.push(line.trim())

        node.innerHTML = lines.reduce(
            (a, line) =>
                a + `<tspan x="${node.getAttribute('x')}" dy="${a ? test.getBBox().height : 0}">${line}</tspan>`,
            ''
        )
    }
}

export function svgTextInside(node, text?: string) {
    const isSvg = node.parentElement.tagName === 'svg'
    const element = isSvg ? node : node.parentElement

    const x = element.getAttributeNS(null, 'x')
    const rootWidth = Number(element.parentElement.getAttributeNS(null, 'width'))
    const newWidth = Math.max(0, rootWidth - x)
    element.setAttribute('width', newWidth.toString())

    if (typeof text === 'string') {
        node.innerText = text?.trim() || ''
    }
}

export function groupBy(array: Array<any>, key: string): any {
    return array.reduce((result: any, currentValue: any) => {
        result[currentValue[key]] = result[currentValue[key]] || []
        result[currentValue[key]].push(currentValue)
        return result
    }, {})
}

export const loadFonts = (fonts: App.Font[]) => {
    for (const font of fonts) {
        const fontFace = new FontFace(font.name, `url('${font.path}')`)
        try {
            fontFace.load()

            const style = document.createElement('style')

            style.textContent = `
                @font-face {
                    font-family: '${font.value}';
                    src: url('${font.path}') format('truetype');
                }
            `
            document.head.appendChild(style)
        } catch (error) {
            console.error(font, error)
        }
    }
}
