/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      colors: {
        fushia: '#D64591',
        fushia_hover: '#B83D7A',
        lagon: '#6BC2CC',
        lagon_hover: '#5BA5AD',
        outremer: '#1A3863',
        light: '#F7F6F6',
        bg_input: '#D9D9D9',
        placeholder: '#8E8B8B',
        alerte_red: '#D10000',
      },
    },
  },
  plugins: [],
}
