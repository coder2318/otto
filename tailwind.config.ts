import daisyui from 'daisyui';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.ts",
        "./resources/**/*.svelte",
    ],
    plugins: [
        typography,
        daisyui,
    ],
}
