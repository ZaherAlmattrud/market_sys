#!/bin/bash

# 🚀 بدء Laravel على عنوان IP محدد
php artisan serve --host=192.168.1.114 --port=8000 &

# 🎨 بدء Vue 3 (Vite) على نفس الشبكة
npm run dev

