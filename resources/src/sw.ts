import { cleanupOutdatedCaches, precacheAndRoute } from 'workbox-precaching'

declare const self: ServiceWorkerGlobalScope

cleanupOutdatedCaches()
precacheAndRoute(self.__WB_MANIFEST)

self.addEventListener('install', () => {
    self.skipWaiting()
})

self.addEventListener('push', function (event) {
    if (Notification.permission !== 'granted') return

    const data = event.data?.json()

    if (data) {
        event.waitUntil(this.registration.showNotification(data?.title ?? import.meta.env.VITE_APP_NAME, data))
    }
})

self.addEventListener('notificationclick', function (event) {
    event.notification.close()

    if (event.action) {
        return event.waitUntil(this.clients.openWindow(event.action))
    }

    if (event.notification.data?.url) {
        return event.waitUntil(this.clients.openWindow(event.notification.data?.url))
    }
})
