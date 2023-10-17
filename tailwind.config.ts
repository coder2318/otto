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
            'serif': ['baskerville-urw', ...fontFamily.serif],
            'sans': ['ABC Diatype', ...fontFamily.sans],
            'mono': fontFamily.mono
        },
        // fontSize: {
        //     sm: '0.8rem',
        //     base: '1rem',
        //     xl: ['18px', '1.6'],
        //     '2xl': ['20px', '1.6'],
        //     '3xl': ['24px', '1.1'],
        //     '4xl': ['28px', '1.1'],
        //     '5xl': ['32px', '1.1'],
        //     '6xl': ['48px', '1.1'],
        //     '7xl': ['54px', '1.1'],
        //     '8xl': ['60px', '1.1'],
        //     '9xl': ['86px', '1.2'],
        // }
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
