declare namespace App {
    interface PaginationLinks {
        first: string
        last: string
        next: string | null
        prev: string | null
    }

    interface PaginationMeta {
        current_page: number
        from: number | null
        last_page: number
        links: Array<{
            active: boolean
            label: string
            url: string | null
        }>
        path: string
        per_page: number
        to: number | null
        total: number
    }

    interface Auth {
        user: User
    }

    interface User {
        id: number
        name: string
        email: string
        avatar: string
        stories?: Story[]
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

    interface Story {
        id: number
        title: string
        cover: string
        status: string
        user_id: number
        user?: User
        timeline_id: number
        timeline?: Timeline
        created_at: Date | string
        updated_at: Date | string
    }

    interface Timeline {
        //
    }
}
