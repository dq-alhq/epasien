/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./*.php",
    "./layouts/**/*.php",
    "./pages/**/*.php",
    "./user/**/*.php",
    "./conf/**/*.php"
  ],
  theme: {
    extend: {
      colors: {
        nu: {
          50: "#f2fbf4",
          100: "#dcf6e3",
          200: "#bcecc9",
          500: "#12824b",
          600: "#0f6a3e",
          700: "#0d5633",
          900: "#0a2a1d"
        },
        sand: "#f6f2e8",
        ink: "#0f172a"
      },
      boxShadow: {
        soft: "0 24px 60px -28px rgba(15, 23, 42, 0.28)"
      },
      fontFamily: {
        sans: ["GT Walsheim Pro", "sans-serif"]
      }
    }
  },
  plugins: []
};
