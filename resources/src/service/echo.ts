import Echo from 'laravel-echo';
import Pusher, { type Options } from 'pusher-js/worker';

const config : Options | any  = {
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    wsHost: import.meta.env.VITE_PUSHER_HOST,
    wsPort: import.meta.env.VITE_PUSHER_PORT,
    wssPort: import.meta.env.VITE_PUSHER_PORT,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: import.meta.env.VITE_PUSHER_SCHEME === 'https',
    encrypted: true,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
    // authorizer: (channel) => ({
    //     authorize: (socketId, callback) => {
    //         axios.post('/broadcasting/auth', {
    //             socket_id: socketId,
    //             channel_name: channel.name
    //         })
    //             .then((response) => {
    //                 callback(false, response.data)
    //             })
    //             .catch((error) => {
    //                 callback(true, error)
    //             })
    //     },
    // }),
};

export const pusher = new Pusher(config.key, config);
export const echo = new Echo({...config, client: pusher})
