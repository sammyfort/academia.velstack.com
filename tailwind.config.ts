// tailwind.config.js
module.exports = {
  darkMode: 'class', // we toggle `class="dark"` on <html> or <body>
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.{vue,js,ts,jsx,tsx}',
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
