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
        name: string
        price: number
        description: string
        slug: string
    }
}
