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
