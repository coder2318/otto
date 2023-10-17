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
        avatar?: string
        email: string
        details: {
            [key: string]: any
            social: {
                [key: string]: string
            }
        }
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
        pages?: number
        timeline_id: number
        timeline?: Timeline
        pages?: number
        created_at: Date | string
        updated_at: Date | string
        chapters?: Chapter[]
    }

    interface StoryType {
        id: number
        name: string
    }

    interface Chapter {
        id: number
        title: string
        context: ?string
        content: string | null
        edit: string | null
        order: number
        cover: string | null
        attachments: Attachment[] | null
        question?: TimelineQuestion | null
        status: string
        story_id: number | null
        story?: Story
        type?: string
        guest_id: number | null
        guest?: User | null
        processing: boolean
        user?: User | null
        created_at: Date | string
        updated_at: Date | string
    }

    interface Timeline {
        id: number
        title: string
        description: string
    }

    interface TimelineQuestion {
        id: number
        question: string
        order: number
        cover: string
        sub_questions: string[] | null
    }

    interface Attachment {
        id: number
        url: string
        name: string
        size: number
        transcribed: boolean
        is_media: boolean
        created_at: Date | string
    }

    interface TranscriptionsData {
        [filename: string]: string
    }

    interface BookCoverTemplate {
        id: number
        name: string
        back: string
        spine: string
        front: string
        cover?: string
        fields: Array<{
            name: string
            type: string
            key: string
        }>
    }

    interface Language {
        name: string
        code: string
    }

    interface Country extends Language {}
}
