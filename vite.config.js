import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';


export default defineConfig({

    
    
     server: {
    host: '192.168.1.102', // ← استبدل بـ IP جهازك
    port: 5173,
    strictPort: true,
    hmr: { host: '192.168.1.102' }, // ← نفس IP
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
