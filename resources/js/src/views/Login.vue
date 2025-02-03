<template>
  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow" style="max-width: 400px; width: 100%">
      <div class="card-body">
        <h3 class="card-title text-center mb-4">Giriş Yap</h3>
        <form @submit.prevent="handleLogin">
          <div class="mb-3">
            <label for="email" class="form-label">E posta Adresi</label>
            <input v-model="email" type="email" class="form-control" id="email" placeholder="E posta adresini giriniz"
              required />
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Şifre</label>
            <input v-model="password" type="password" class="form-control" id="password" placeholder="Şifre giriniz"
              required />
          </div>
          <div v-if="error" class="alert alert-danger">
            {{ error }}
          </div>
          <button type="submit" class="btn btn-primary w-100">Giriş Yap</button>
        </form>
        <p class="text-center mt-3">
          Hesabınız yok mu ? <router-link :to="{ name: 'register' }">Kayıt ol</router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import Swal from 'sweetalert2';

// Data for the form
const email = ref('');
const password = ref('');
const error = ref('');

// Router instance
const router = useRouter();

// Handle login
const handleLogin = async () => {
  try {
    const response = await axios.post(import.meta.env.VITE_LARAVEL_API_URL + '/auth/login', {
      email: email.value,
      password: password.value,
    });

    if (response.data.status) {
      // Save token to localStorage
      localStorage.setItem('auth_token', response.data.data.token);
      localStorage.setItem('username', response.data.data.user.username);
      localStorage.setItem('name', response.data.data.user.name);
      // Redirect to dashboard

      router.push('/');
    }
  } catch (error) {
      Swal.fire({
      title: "Hata",
      text: error.response.data.message[0],
      icon: "error"
    });
  }
};
</script>

<style scoped>
.container {
  min-height: 100vh;
}

.card {
  border-radius: 10px;
}

.card-body {
  padding: 30px;
}
</style>