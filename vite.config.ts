import { defineConfig } from 'vite'
import { createHtmlPlugin } from 'vite-plugin-html'
import vueDevTools from 'vite-plugin-vue-devtools'

export default defineConfig({
  plugins: [
    // Vue DevTools plugin must be registered BEFORE the HTML plugin
    vueDevTools(),
    createHtmlPlugin({})
  ]
}) 