/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  darkMode: ['class', '[data-theme="dark"]'],
  theme: {
    extend: {
      colors: {
        coffee: {
          50: 'hsl(24, 25%, 98%)',
          100: 'hsl(24, 25%, 95%)',
          200: 'hsl(24, 20%, 90%)',
          300: 'hsl(24, 20%, 80%)',
          400: 'hsl(24, 30%, 65%)',
          500: 'hsl(24, 30%, 50%)',
          600: 'hsl(24, 20%, 38%)',
          700: 'hsl(24, 15%, 25%)',
          800: 'hsl(24, 12%, 18%)',
          900: 'hsl(24, 10%, 12%)',
          950: 'hsl(24, 8%, 7%)',
        },
        caramel: {
          50: 'hsl(28, 65%, 95%)',
          500: 'hsl(28, 65%, 45%)',
          600: 'hsl(28, 70%, 38%)',
        },
        gold: {
          500: 'hsl(38, 70%, 50%)',
        }
      },
      fontFamily: {
        sans: ['Outfit', 'sans-serif'],
        serif: ['Playfair Display', 'serif'],
      },
      boxShadow: {
        'premium': '0 10px 30px rgba(40, 25, 15, 0.06)',
        'premium-lg': '0 20px 40px rgba(40, 25, 15, 0.1)',
      }
    },
  },
  plugins: [],
}
