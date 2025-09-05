// vite.config.js
import { resolve } from 'path';
import { defineConfig } from 'vite';

export default defineConfig({
  build: {
    // Folder output hasil build
    outDir: 'dist',
    // Hasilkan manifest.json untuk integrasi lebih lanjut (opsional)
    manifest: true,
    rollupOptions: {
      // Tentukan file input Anda di sini
      input: {
        main: resolve(__dirname, 'src/main.js'),
        styles: resolve(__dirname, 'src/main.scss'),
      },
    },
  },
});