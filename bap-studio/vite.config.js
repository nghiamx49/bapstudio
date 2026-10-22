import { defineConfig } from 'vite';

// Builds src/js/main.js (which imports the SCSS and fonts) into dist/main.js + dist/main.css.
export default defineConfig({
  base: './',
  publicDir: false,
  build: {
    outDir: 'dist',
    emptyOutDir: true,
    target: 'es2019',
    cssCodeSplit: false,
    assetsInlineLimit: 0,
    rollupOptions: {
      input: 'src/js/main.js',
      output: {
        entryFileNames: 'main.js',
        chunkFileNames: '[name].js',
        assetFileNames: (info) => (info.name && info.name.endsWith('.css') ? 'main.css' : 'assets/[name]-[hash][extname]'),
      },
    },
  },
  css: { preprocessorOptions: { scss: { api: 'modern-compiler' } } },
});
