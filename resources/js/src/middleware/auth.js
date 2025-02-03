// src/middleware/auth.js

export function requireAuth(to, from, next) {
  const token = localStorage.getItem('auth_token');
  if (!token) {
    next({ name: 'login' });
  } else {
    next();
  }
}
