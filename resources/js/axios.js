import axios from 'axios';




const instance = axios.create({
  baseURL: 'http://localhost:8000', // قم بتعديل هذا الرابط بناءً على عنوان API الخاص بك
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
    withCredentials: true, // مهم جداً مع Sanctum
});

const token = localStorage.getItem("token");
if (token) {
  instance.defaults.headers.common["Authorization"] = `Bearer ${token}`;
}

export default instance;
