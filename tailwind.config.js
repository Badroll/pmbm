module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  safelist: [
    {
      pattern: /^(m|p)(t|b|l|r|x|y)?-(10|12|14|16|20|24|28|32|36|40|44|48|52|56|60|64|72|80|96)$/,
    },
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}