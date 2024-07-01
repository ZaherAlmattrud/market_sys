import axios from 'axios';

const instance = axios.create({
  baseURL: 'http://localhost:8000/api', // قم بتعديل هذا الرابط بناءً على عنوان API الخاص بك
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

export default instance;
