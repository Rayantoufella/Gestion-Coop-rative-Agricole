import react from '@vitejs/plugin-react'
import { defineConfig } from 'vite'

// https://vite.dev/config/
export default defineConfig({
  plugins: [react()],
  server: {
    // Dans Docker, Vite ne voit pas toujours les fichiers modifiés :
    // avec "usePolling", il vérifie lui-même les fichiers régulièrement.
    watch: {
      usePolling: true,
    },
  },
})
