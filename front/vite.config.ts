import { defineConfig, loadEnv } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'

// https://vite.dev/config/
export default defineConfig(({ mode }) => {
  // Load env file based on `mode` in the current directory.
  // Set the third parameter to '' to load all env regardless of the `VITE_` prefix.
  const env = loadEnv(mode, process.cwd(), '')
  
  return {
    plugins: [vue()],
    // Expose env variables to your app
    define: {
      __APP_ENV__: JSON.stringify(env.APP_ENV),
    },
    resolve: {
      alias: {
        '@': path.resolve(__dirname, './src')
      }
    }
  }
})
