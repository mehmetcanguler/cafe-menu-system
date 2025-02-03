<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button">
                <span class="navbar-toggler-icon"></span>
            </button>
       
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a target="_blank" class="nav-link" :href="clientUrl" role="button">
                            <i class="bi bi-box-arrow-up-right"></i>
                            Menüyü Görüntüle
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a @click="handleLogout" class="nav-link" href="#" role="button">
                            <i class="bi bi-box-arrow-right"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { useRouter } from 'vue-router';

const username = localStorage.getItem('username');
const clientUrl = import.meta.env.VITE_LARAVEL_APP_URL + '/' + username;
console.log(clientUrl,username);


const router = useRouter();
const handleLogout = async () => {
    try {
        const token = localStorage.getItem('auth_token');
        const response = await axios.post(import.meta.env.VITE_LARAVEL_API_URL + '/auth/logout', {}, {
            headers: {
                Authorization: `Bearer ${token}`,
            }
        });
        localStorage.removeItem('auth_token');
        router.push('/login');
    } catch (error) {
        error.value = 'Invalid credentials. Please try again.';
    }
};
</script>