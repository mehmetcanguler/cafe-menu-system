<template>
    <div class="row">
        <div class="d-flex justify-content-end">
            <router-link :to="{ name: 'categories.create' }" class="btn btn-success">
                Kategori Ekle
            </router-link>
        </div>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Resim</th>
                <th scope="col">Adı</th>
                <th scope="col">Slug</th>
                <th scope="col">İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="category in categories">
                <td>
                    <TableImageComponent v-for="image in category.images" :url=image.url :alt=image.alt />

                </td>
                <td>{{ category.name }}</td>
                <td>{{ category.slug }}</td>
                <td>
                    <router-link :to="{ name: 'categories.edit', params: { id: category.id } }"
                        class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil"></i>
                    </router-link>

                    <button @click="handleDelete(category.id)" class="btn btn-danger btn-sm">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>

        </tbody>
    </table>
</template>

<script setup>
import { ref } from 'vue';
import TableImageComponent from '../../Components/TableImageComponent.vue';
import Swal from 'sweetalert2'

const categories = ref([]);
const getCategories = async () => {
    try {
        const token = localStorage.getItem('auth_token');
        const response = await axios.get(import.meta.env.VITE_LARAVEL_API_URL + '/categories', {
            headers: {
                Authorization: `Bearer ${token}`
            },
        });

        categories.value = response.data.data;
    } catch (error) {
        console.log(error);
    }
}

getCategories();


const handleDelete = (id) => {
    if (window.confirm("Bu kategoriyi silmek istediğinize emin misiniz?")) {
        try {
            const token = localStorage.getItem('auth_token');
            const response = axios.delete(import.meta.env.VITE_LARAVEL_API_URL + `/categories/${id}`, {
                headers: {
                    Authorization: `Bearer ${token}`
                },
            });
            Swal.fire({
                title: "Başarılı",
                text: "Kategori silindi",
                icon: "success"
            });

            getCategories();
        } catch (error) {
            console.log(error);
        }
    }
}
</script>