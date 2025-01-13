import defaultTheme from "tailwindcss/defaultTheme";

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "class",
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                bgDark: "#0e0e11",
                pTxt: "#27272A",
                pTxtDark: "#f4f4f5",
                sText: "#71717a",
                sTextDark: "#a1a1aa",
                accent: "#d4d4d8",
                flamingo: {
                    50: "#fef4ee",
                    100: "#fce5d8",
                    200: "#f8c8b0",
                    300: "#f3a17e",
                    400: "#ee7049",
                    500: "#eb5b38", // principal
                    600: "#db341b",
                    700: "#b52519",
                    800: "#91201b",
                    900: "#751d19",
                    950: "#3f0b0b",
                },
            },
        },
    },
    plugins: [],
};
