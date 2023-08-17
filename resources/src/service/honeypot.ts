export type Honeypot = {
    enabled: boolean
    encryptedValidFrom: string
    nameFieldName: string
    unrandomizedNameFieldName: string
    validFromFieldName: string
}

export function addHoneypot(honeypot: Honeypot) {
    return (data: object = {}) => {
        if (!honeypot?.enabled) {
            return data
        }

        return {
            ...data,
            [honeypot.nameFieldName]: '',
            [honeypot.validFromFieldName]: honeypot.encryptedValidFrom,
        }
    }
}
