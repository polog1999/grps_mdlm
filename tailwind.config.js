/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './app/Livewire/**/*.php',
        './app/Filament/**/*.php',
        './vendor/filament/**/*.blade.php', // <--- Ruta directa y limpia
    ],
    theme: {
        extend: {},
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
}