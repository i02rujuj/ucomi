/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
      fontFamily: {
        inter: ["Inter", "sans-serif"],
        nunito: ["Nunito", "sans-serif"],
        poppins: ["Poppins", "sans-serif"],
        dancingscript: ["Dancing Script", "cursive"],
      },
      /* Imágenes para añadir desde Tailwind CSS */
      backgroundImage: (theme) => ({
          ucomi: "url('/public/img/bgucomi.jpg')",
      }),
    },
  },
  plugins: [],
}

