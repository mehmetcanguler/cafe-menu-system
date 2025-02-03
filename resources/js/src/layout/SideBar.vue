<template>
    <div>
        <router-link :to="{ name: 'dashboard' }" class="text-white mb-4 text-decoration-none d-block text-center">
            Admin Panel
        </router-link>
        <nav class="nav flex-column">

            <router-link :to="{ name: 'categories' }" class="sidebar-link" active-class="active">
                <i class="bi bi-folder me-2"></i>Kategoriler
            </router-link>
            <router-link :to="{ name: 'products' }" class="sidebar-link" active-class="active">
                <i class="bi bi-box me-2"></i>Ürünler
            </router-link>

        </nav>
    </div>
</template>

<style scoped>
.sidebar-link.active {
    background-color: #007bff;
    /* Seçili sayfa rengi */
    color: white;
}
</style>