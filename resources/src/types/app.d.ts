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
        discount?: string
    }

    interface Story {
        id: number
        title: string
        activeUserCoverTemplate?: BookCoverTemplate
        status: string
        user_id: number
        user?: User
        book?: any | null
        book_preview?: any | null
        pages?: number
        words?: number
        progress?: number
        timeline_id: number
        timeline?: Timeline
        created_at: Date | string
        updated_at: Date | string
        chapters?: Chapter[]
        font: string
    }

    interface StoryType {
        id: number
        name: string
    }

    interface Font {
        value: string
        path: string
        name: string
    }

    interface Chapter {
        id: number
        title: string
        context?: string
        content: string | null
        edit: string | null
        order: number
        cover: string | null
        attachments: Attachment[] | null
        hasUntranscribedAttachments: boolean | null
        question?: TimelineQuestion | null
        images?: any[] | null
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
        context: string | null
        order: number
        cover?: string
        covers?: string[]
        chapter_id: number | undefined
        chapter?: Chapter | null
        chapters?: Chapter[] | null
        sub_questions: string[] | null
        created_at: Date | string
        updated_at: Date | string
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
        template: CoverTemplate
        parameters: { [key: string]: any }
        template_id: number
    }

    interface CoverTemplate {
        id: number
        name: string
        back: string
        back_image: string
        back_image_file: string
        spine: string
        front: string
        front_image: string
        front_image_file: string
        base: string
        cover?: string
        fields: Array<{
            name: string
            type: string
            key: string
            defaultValue: any
        }>
    }

    interface Language {
        name: string
        code: string
    }

    interface Country extends Language {}

    interface Prompt {
        id: number
        title: string
        description: string
        icon: string
        perspective: boolean
        content: string
        created_at: Date | string
        updated_at: Date | string
    }
}
