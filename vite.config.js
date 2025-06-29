import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';


export default defineConfig({

    
    
     server: {
    host: '0.0.0.0', // ← استبدل بـ IP جهازك
    port: 5173,
    strictPort: true,
    hmr: { host: '192.168.1.114' }, // ← نفس IP
     watch: {
      usePolling: true,
    },
    cors: true, // ← هذا السطر الحاسم!
  },
  
  build: {
    rollupOptions: {
      output: {
        assetFileNames: '[name].[hash][extname]'
      }
    }
  },
    plugins: [
        vue(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            
        }),
       
    ],
});
