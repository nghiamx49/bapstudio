import { defineConfig } from 'vite';
import { fileURLToPath } from 'node:url';

// Builds src/js/main.js (which imports the SCSS and fonts) into dist/main.js + dist/main.css.
// Behaviour shared with the parent theme (dialog, carousel, nav, load more) is imported
// from ../bap-studio/src/js through the "@parent" alias and bundled in.
export default defineConfig({
  base: './',
  publicDir: false,
  resolve: {
    alias: { '@parent': fileURLToPath(new URL('../bap-studio/src/js', import.meta.url)) },
    // Parent sources import "gsap"; resolve it from this theme's node_modules.
    dedupe: ['gsap'],
  },
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
