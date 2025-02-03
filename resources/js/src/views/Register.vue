<template>
    <div class="container d-flex justify-content-center align-items-center vh-100">
      <div class="card shadow" style="max-width: 400px; width: 100%">
        <div class="card-body">
          <h3 class="card-title text-center mb-4">Kayıt Ol</h3>
          <form @submit.prevent="handleRegister">
            <div class="mb-3">
              <label for="email" class="form-label">Mağaza İsminiz</label>
              <input v-model="name" type="text" class="form-control" id="name" placeholder="Mağaza İsmini giriniz" required />
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Kullanıcı adı</label>
              <input v-model="username" type="text" class="form-control" id="username" placeholder="Kullanıcı adını giriniz" required />
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">E posta Adresi</label>
              <input v-model="email" type="email" class="form-control" id="email" placeholder="E posta adresini giriniz" required />
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Şifre</label>
              <input v-model="password" type="password" class="form-control" id="password" placeholder="Şifre giriniz"
                required />
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Şifre Tekrar</label>
              <input v-model="password_confirmation" type="password" class="form-control" id="password_confirmation" placeholder="Şifre tekrar giriniz"
                required />
            </div>
            <div v-if="error" class="alert alert-danger">
              {{ error }}
            </div>
            <button type="submit" class="btn btn-primary w-100">Kayıt ol</button>
          </form>
          <p class="text-center mt-3">
            Hesabınız var mı ? <router-link :to="{name: 'login'}">Giriş Yap</router-link>
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
  
  const email = ref('');
  const name = ref('');
  const username = ref('');
  const password = ref('');
  const password_confirmation = ref('');
  const error = ref('');
  
  const router = useRouter();
  
  const handleRegister = async () => {
    try {
      const response = await axios.post(import.meta.env.VITE_LARAVEL_API_URL + '/auth/register', {
        name: name.value,
        email: email.value,
        password: password.value,
        password_confirmation: password_confirmation.value,
        username: username.value
      });
  
      if (response.data.status) {
        // Save token to localStorage
        localStorage.setItem('auth_token', response.data.data.token);
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