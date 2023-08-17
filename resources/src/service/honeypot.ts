export type Honeypot = {
    enabled: boolean
    encryptedValidFrom?: string
    nameFieldName?: string
    unrandomizedNameFieldName?: string
    validFromFieldName?: string
}

export function addHoneypot(honeypot: Honeypot) {
    return (data: object = {}) => ({
        ...data,
        [honeypot?.nameFieldName ?? 'honey']: '',
        [honeypot?.validFromFieldName ?? 'honeyEnc']:
            honeypot?.encryptedValidFrom,
    })
}
