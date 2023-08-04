export function range(from: number, to: number | null = null): Array<number> {
    if (to === null) {
        to = from
        from = 0
    }

    return Array.from({ length: to - from }, (_, i) => from + i)
}

export function usd(
    amount: number,
    options?: Intl.NumberFormatOptions
): string {
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
