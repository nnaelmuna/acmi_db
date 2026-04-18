/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                acmi: {
                    darkblue: '#0C1C87',
                    softblue: '#DAE6FE',
                    blueprimer: '#1120B0',
                    blueaccent: '#3D4BC9',
                    yellowaccent: '#FE8A01',
                    bordercolor: '#BCBCBC',
                }
            },
            fontFamily: {
                poppins: ['Poppins', 'sans-serif'],
                serif: ['Source Serif Pro', 'serif'],
            },
        },
    },
    plugins: [],
};