import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    safelist: [
        {
            pattern: /bg-(red|orange|yellow|green|sky|indigo)-(200|300|500)/
        },
        {
            pattern: /text-(red|orange|yellow|green|sky|indigo)-(200|300|500)/
        },
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['v-sans', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms, typography],
};
