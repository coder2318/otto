import daisyui from 'daisyui';
import typography from '@tailwindcss/typography';
import { fontFamily } from 'tailwindcss/defaultTheme'

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.ts",
        "./resources/**/*.svelte",
    ],
    theme: {
        fontFamily: {
            'serif': ['"Baskerville 10 Pro"', '"Baskerville 120 Pro"', ...fontFamily.serif],
            'sans': ['ABC Diatype', ...fontFamily.sans],
            'mono': fontFamily.mono
        }
    },
    plugins: [
        typography,
        daisyui,
    ],
    daisyui: {
        logs: false,
        themes: [{
            otto: {
                'primary': '#0C345C',
                'secondary': '#FFD886',
                'accent': '#F8AD9D',
                'neutral': '#FFFFFF',
                'base-100': '#F1EDE7',
                'base-200': '#ECE6DF',
                'base-300': '#D9CEBF',
                'success': '#C0DFB0',
            }
        }]
    }
}
