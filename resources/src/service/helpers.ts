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

export function strToHtml(str: string): string {
    return str
        .split('\n\n')
        .map((x) => '<p>' + x.trim().replaceAll('\n', '<br/>') + '</p>')
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
            a + `<tspan x="${node.getAttributeNS(null, 'x')}" dy="${a ? test.getBBox().height : 0}">${line}</tspan>`,
        ''
    )
}

export function groupBy(array: Array<any>, key: string): any {
    return array.reduce((result: any, currentValue: any) => {
        result[currentValue[key]] = result[currentValue[key]] || []
        result[currentValue[key]].push(currentValue)
        return result
    }, {})
}

export async function listFonts() {
    try {
        return await window.queryLocalFonts()
    } catch {
        const fontCheck = new Set(
            [
                // Windows 10
                'Arial',
                'Arial Black',
                'Bahnschrift',
                'Calibri',
                'Cambria',
                'Cambria Math',
                'Candara',
                'Comic Sans MS',
                'Consolas',
                'Constantia',
                'Corbel',
                'Courier New',
                'Ebrima',
                'Franklin Gothic Medium',
                'Gabriola',
                'Gadugi',
                'Georgia',
                'HoloLens MDL2 Assets',
                'Impact',
                'Ink Free',
                'Javanese Text',
                'Leelawadee UI',
                'Lucida Console',
                'Lucida Sans Unicode',
                'Malgun Gothic',
                'Marlett',
                'Microsoft Himalaya',
                'Microsoft JhengHei',
                'Microsoft New Tai Lue',
                'Microsoft PhagsPa',
                'Microsoft Sans Serif',
                'Microsoft Tai Le',
                'Microsoft YaHei',
                'Microsoft Yi Baiti',
                'MingLiU-ExtB',
                'Mongolian Baiti',
                'MS Gothic',
                'MV Boli',
                'Myanmar Text',
                'Nirmala UI',
                'Palatino Linotype',
                'Segoe MDL2 Assets',
                'Segoe Print',
                'Segoe Script',
                'Segoe UI',
                'Segoe UI Historic',
                'Segoe UI Emoji',
                'Segoe UI Symbol',
                'SimSun',
                'Sitka',
                'Sylfaen',
                'Symbol',
                'Tahoma',
                'Times New Roman',
                'Trebuchet MS',
                'Verdana',
                'Webdings',
                'Wingdings',
                'Yu Gothic',
                // macOS
                'American Typewriter',
                'Andale Mono',
                'Arial',
                'Arial Black',
                'Arial Narrow',
                'Arial Rounded MT Bold',
                'Arial Unicode MS',
                'Avenir',
                'Avenir Next',
                'Avenir Next Condensed',
                'Baskerville',
                'Big Caslon',
                'Bodoni 72',
                'Bodoni 72 Oldstyle',
                'Bodoni 72 Smallcaps',
                'Bradley Hand',
                'Brush Script MT',
                'Chalkboard',
                'Chalkboard SE',
                'Chalkduster',
                'Charter',
                'Cochin',
                'Comic Sans MS',
                'Copperplate',
                'Courier',
                'Courier New',
                'Didot',
                'DIN Alternate',
                'DIN Condensed',
                'Futura',
                'Geneva',
                'Georgia',
                'Gill Sans',
                'Helvetica',
                'Helvetica Neue',
                'Herculanum',
                'Hoefler Text',
                'Impact',
                'Lucida Grande',
                'Luminari',
                'Marker Felt',
                'Menlo',
                'Microsoft Sans Serif',
                'Monaco',
                'Noteworthy',
                'Optima',
                'Palatino',
                'Papyrus',
                'Phosphate',
                'Rockwell',
                'Savoye LET',
                'SignPainter',
                'Skia',
                'Snell Roundhand',
                'Tahoma',
                'Times',
                'Times New Roman',
                'Trattatello',
                'Trebuchet MS',
                'Verdana',
                'Zapfino',
            ].sort()
        )

        await document.fonts.ready

        const fontAvailable = new Set()

        for (const font of fontCheck.values()) {
            if (document.fonts.check(`12px "${font}"`)) {
                fontAvailable.add(font)
            }
        }

        return [...fontAvailable.values()]
    }
}
