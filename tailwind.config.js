/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./public/**/*.html",
    "./storage/framework/views/*.php",
    "./app/View/Components/**/*.php"
  ],
  theme: {
    extend: {
      fontFamily: {
        'arabic': ['Noto Sans Arabic', 'Cairo', 'Amiri', 'Arial', 'sans-serif']
      },
      colors: {
        'primary': {
          50: '#f0fdf4',
          100: '#dcfce7',
          200: '#bbf7d0',
          300: '#86efac',
          400: '#4ade80',
          500: '#22c55e',
          600: '#16a34a',
          700: '#15803d',
          800: '#166534',
          900: '#14532d',
        }
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms')
  ],
  safelist: [
    'border-red-500',
    'border-gray-300',
    'text-red-600',
    'bg-red-100',
    'bg-green-100',
    'bg-yellow-100',
    'bg-blue-100'
  ]
}
