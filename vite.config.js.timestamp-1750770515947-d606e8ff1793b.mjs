// vite.config.js
import { defineConfig } from "file:///F:/MyProjects/market_sys/node_modules/vite/dist/node/index.js";
import laravel from "file:///F:/MyProjects/market_sys/node_modules/laravel-vite-plugin/dist/index.js";
import vue from "file:///F:/MyProjects/market_sys/node_modules/@vitejs/plugin-vue/dist/index.mjs";
var vite_config_default = defineConfig({
  server: {
    host: "0.0.0.0",
    // ← استبدل بـ IP جهازك
    port: 5173,
    strictPort: true,
    hmr: { host: "192.168.1.102" },
    // ← نفس IP
    watch: {
      usePolling: true
    },
    cors: true
    // ← هذا السطر الحاسم!
  },
  build: {
    rollupOptions: {
      output: {
        assetFileNames: "[name].[hash][extname]"
      }
    }
  },
  plugins: [
    vue(),
    laravel({
      input: ["resources/css/app.css", "resources/js/app.js"],
      refresh: true
    })
  ]
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCJGOlxcXFxNeVByb2plY3RzXFxcXG1hcmtldF9zeXNcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfZmlsZW5hbWUgPSBcIkY6XFxcXE15UHJvamVjdHNcXFxcbWFya2V0X3N5c1xcXFx2aXRlLmNvbmZpZy5qc1wiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9pbXBvcnRfbWV0YV91cmwgPSBcImZpbGU6Ly8vRjovTXlQcm9qZWN0cy9tYXJrZXRfc3lzL3ZpdGUuY29uZmlnLmpzXCI7aW1wb3J0IHsgZGVmaW5lQ29uZmlnIH0gZnJvbSAndml0ZSc7XG5pbXBvcnQgbGFyYXZlbCBmcm9tICdsYXJhdmVsLXZpdGUtcGx1Z2luJztcbmltcG9ydCB2dWUgZnJvbSAnQHZpdGVqcy9wbHVnaW4tdnVlJztcblxuXG5leHBvcnQgZGVmYXVsdCBkZWZpbmVDb25maWcoe1xuXG4gICAgXG4gICAgXG4gICAgIHNlcnZlcjoge1xuICAgIGhvc3Q6ICcwLjAuMC4wJywgLy8gXHUyMTkwIFx1MDYyN1x1MDYzM1x1MDYyQVx1MDYyOFx1MDYyRlx1MDY0NCBcdTA2MjhcdTA2NDAgSVAgXHUwNjJDXHUwNjQ3XHUwNjI3XHUwNjMyXHUwNjQzXG4gICAgcG9ydDogNTE3MyxcbiAgICBzdHJpY3RQb3J0OiB0cnVlLFxuICAgIGhtcjogeyBob3N0OiAnMTkyLjE2OC4xLjEwMicgfSwgLy8gXHUyMTkwIFx1MDY0Nlx1MDY0MVx1MDYzMyBJUFxuICAgICB3YXRjaDoge1xuICAgICAgdXNlUG9sbGluZzogdHJ1ZSxcbiAgICB9LFxuICAgIGNvcnM6IHRydWUsIC8vIFx1MjE5MCBcdTA2NDdcdTA2MzBcdTA2MjcgXHUwNjI3XHUwNjQ0XHUwNjMzXHUwNjM3XHUwNjMxIFx1MDYyN1x1MDY0NFx1MDYyRFx1MDYyN1x1MDYzM1x1MDY0NSFcbiAgfSxcbiAgXG4gIGJ1aWxkOiB7XG4gICAgcm9sbHVwT3B0aW9uczoge1xuICAgICAgb3V0cHV0OiB7XG4gICAgICAgIGFzc2V0RmlsZU5hbWVzOiAnW25hbWVdLltoYXNoXVtleHRuYW1lXSdcbiAgICAgIH1cbiAgICB9XG4gIH0sXG4gICAgcGx1Z2luczogW1xuICAgICAgICB2dWUoKSxcbiAgICAgICAgbGFyYXZlbCh7XG4gICAgICAgICAgICBpbnB1dDogWydyZXNvdXJjZXMvY3NzL2FwcC5jc3MnLCAncmVzb3VyY2VzL2pzL2FwcC5qcyddLFxuICAgICAgICAgICAgcmVmcmVzaDogdHJ1ZSxcbiAgICAgICAgICAgIFxuICAgICAgICB9KSxcbiAgICAgICBcbiAgICBdLFxufSk7XG4iXSwKICAibWFwcGluZ3MiOiAiO0FBQWdRLFNBQVMsb0JBQW9CO0FBQzdSLE9BQU8sYUFBYTtBQUNwQixPQUFPLFNBQVM7QUFHaEIsSUFBTyxzQkFBUSxhQUFhO0FBQUEsRUFJdkIsUUFBUTtBQUFBLElBQ1QsTUFBTTtBQUFBO0FBQUEsSUFDTixNQUFNO0FBQUEsSUFDTixZQUFZO0FBQUEsSUFDWixLQUFLLEVBQUUsTUFBTSxnQkFBZ0I7QUFBQTtBQUFBLElBQzVCLE9BQU87QUFBQSxNQUNOLFlBQVk7QUFBQSxJQUNkO0FBQUEsSUFDQSxNQUFNO0FBQUE7QUFBQSxFQUNSO0FBQUEsRUFFQSxPQUFPO0FBQUEsSUFDTCxlQUFlO0FBQUEsTUFDYixRQUFRO0FBQUEsUUFDTixnQkFBZ0I7QUFBQSxNQUNsQjtBQUFBLElBQ0Y7QUFBQSxFQUNGO0FBQUEsRUFDRSxTQUFTO0FBQUEsSUFDTCxJQUFJO0FBQUEsSUFDSixRQUFRO0FBQUEsTUFDSixPQUFPLENBQUMseUJBQXlCLHFCQUFxQjtBQUFBLE1BQ3RELFNBQVM7QUFBQSxJQUViLENBQUM7QUFBQSxFQUVMO0FBQ0osQ0FBQzsiLAogICJuYW1lcyI6IFtdCn0K
