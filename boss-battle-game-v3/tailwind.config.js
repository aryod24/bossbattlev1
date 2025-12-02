import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                "primary": "#f9d406",
                "background-light": "#f8f8f5",
                "background-dark": "#23200f",
                "text-light-primary": "#1c1a0d",
                "text-dark-primary": "#f8f8f5",
                "text-light-secondary": "#9e9147",
                "text-dark-secondary": "#a9a277",
                "surface-light": "#fcfbf8",
                "surface-dark": "#2a2712",
                "border-light": "#e9e5ce",
                "border-dark": "#3f3a1d",
                "status-green-bg": "rgb(56 161 105 / 0.1)",
                "status-green-text": "#38a169",
                "status-red-bg": "rgb(229 62 62 / 0.1)",
                "status-red-text": "#e53e3e",
                "status-gray-bg": "rgb(128 128 128 / 0.1)",
                "status-gray-text": "#808080",
                'boss': '#8B0000',
                'damage': '#FF6B6B',
                'heal': '#51CF66',
                'ui': '#6366F1',
                'info': '#22D3EE',
                'warning': '#F59E0B',
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                "display": ["Spline Sans", "sans-serif"],
                'game': ['"Press Start 2P"', 'cursive'],
            },
            borderRadius: {
                "lg": "2rem",
                "xl": "3rem",
            },
        },
    },

    plugins: [forms],
};
