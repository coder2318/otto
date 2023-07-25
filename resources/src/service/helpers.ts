export function range(from: number, to: number|null = null): Array<number>
{
    if (to === null) {
        to = from;
        from = 0;
    }

    return Array.from({ length: to - from }, (_, i) => from + i);
}
