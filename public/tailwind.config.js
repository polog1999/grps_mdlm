import { join } from 'path';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './app/Livewire/**/*.php',
        './app/Filament/**/*.php',
        // Usamos join y __dirname para asegurar la ruta en Windows
        join(__dirname, 'vendor/filament/**/*.blade.php'),
    ],
    theme: {
        extend: {},
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
}