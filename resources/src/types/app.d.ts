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
}
