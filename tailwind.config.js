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
                // Primary Colors (VS Code Dark Theme)
                "primary": "#007acc",

                // Background Colors
                "background": "#1e1e1e",
                "background-light": "#252526",
                "background-dark": "#1e1e1e",

                // Card/Surface Colors
                "card": "#252526",
                "surface": "#252526",
                "surface-light": "#2d2d2d",
                "surface-dark": "#252526",

                // Text Colors
                "text-primary": "#d4d4d4",
                "text-light-primary": "#d4d4d4",
                "text-dark-primary": "#d4d4d4",
                "text-muted": "#858585",
                "text-light-secondary": "#858585",
                "text-dark-secondary": "#9d9d9d",

                // Border Colors
                "border": "#333333",
                "border-light": "#404040",
                "border-dark": "#333333",

                // VS Code Specific (Mapped to existing tokens for consistency)
                'vscode-bg': '#1e1e1e',
                'vscode-card': '#252526',
                'vscode-primary': '#007acc',
                'vscode-primary-dark': '#005fa3',
                'vscode-text': '#d4d4d4',
                'vscode-muted': '#858585',
                'vscode-border': '#333333',
                // Used by solo/result.blade.php (was inline CDN config)
                'vscode-string': '#ce9178',
                'vscode-button': '#3c3c3c',
                'vscode-button-hover': '#4c4c4c',
                'terminal-bg': '#1e1e1e',
                'boss-red': '#FF5252',
                'success-green': '#4EC9B0',

                // Status Colors
                "status-green-bg": "rgb(56 161 105 / 0.1)",
                "status-green-text": "#38a169",
                "status-red-bg": "rgb(229 62 62 / 0.1)",
                "status-red-text": "#e53e3e",
                "status-gray-bg": "rgb(128 128 128 / 0.1)",
                "status-gray-text": "#808080",

                // Accent Colors
                "accent": "#007acc",
                "accent-hover": "#1a8ad4",

                // Semantic Colors
                "success": "#4ec9b0",
                "warning": "#dcdcaa",
                "error": "#f44747",
                "info": "#9cdcfe",

                // Legacy Game/Map Colors (preserved for map components)
                "game": {
                    "dark": "#0f172a",
                    "darker": "#020617",
                    "panel": "#1e293b",
                    "gold": "#f59e0b",
                    "green": "#10b981",
                    "red": "#ef4444",
                    "teal": "#14b8a6",
                },

                // Legacy Battle Colors
                'boss': '#8B0000',
                'damage': '#FF6B6B',
                'heal': '#51CF66',
                'ui': '#6366F1',
            },
            fontFamily: {
                sans: ['Outfit', ...defaultTheme.fontFamily.sans],
                "display": ["Outfit", "sans-serif"],
                'game': ['"Press Start 2P"', 'cursive'],
            },
            borderRadius: {
                "lg": "0.5rem",
                "xl": "0.75rem",
            },
        },
    },

    plugins: [
        forms,
        require('@tailwindcss/container-queries'),
    ],
};
