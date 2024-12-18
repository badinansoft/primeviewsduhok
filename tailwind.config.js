/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'selector', // or 'media' or 'class'
    content: [
        // You will probably also need these lines
        "./resources/**/**/*.blade.php",
        "./resources/**/**/*.js",
        "./app/View/Components/**/**/*.php",
        "./app/Livewire/**/**/*.php",

        // Add mary
        "./vendor/robsontenorio/mary/src/View/Components/**/*.php"
    ],
    theme: {
        extend: {},
    },

    daisyui: {
        themes: ["light", "dark", "cupcake"],
    },

    // Add daisyUI
    plugins: [require("daisyui")]
}
