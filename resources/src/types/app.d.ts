declare namespace App {
    interface Auth {
        user: User
    }

    interface User {
        id: number
        name: string
        email: string
        avatar: string
    }

    interface Plan {
        id: number
        slug: string
        name: string
        description: string
        prices: {
            [price_id: string]: {
                interval: string
                interval_count: number
                value: number
                currency: string
            }
        }
        features: string[]
    }
}
