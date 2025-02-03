<template>
    <div class="row">
        <div class="card">
            <div class="card-body">
                <form @submit.prevent="handleCreate">
                    <div class="mb-3">
                        <label for="name" class="form-label">Kategoriler</label>
                        <select v-model="category_ids" class="form-select" id="category_ids" required multiple>
                            <option v-for="category in categoriesList" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Ürün adı</label>
                        <input v-model="name" type="text" class="form-control" id="name" required />
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Ürün Fiyat</label>
                        <input v-model="price" step="0.01" type="number" class="form-control" id="price" required />
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Ürün Açıklama</label>
                        <textarea v-model="description" type="text" class="form-control" id="description"
                            required> </textarea>
                    </div>
                    <div class="mb-3">
                        <label for="images" class="form-label">Resimler</label>
                        <input ref="fileInput" multiple @change="handleFileChange" type="file" class="form-control"
                            id="images" accept="image/*" required />
                    </div>

                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2'

const name = ref('');
const images = ref([]);
const fileInput = ref(null);
const description = ref('');
const price = ref('');
const category_ids = ref([]);
const categoriesList = ref([]);

const handleFileChange = (event) => {
    images.value = Array.from(event.target.files);
};

const handleCreate = async () => {
    const token = localStorage.getItem('auth_token');
    const formData = new FormData();
    formData.append('name', name.value);
    formData.append('description', description.value);
    formData.append('price', price.value);

    category_ids.value.forEach((id) => {
        formData.append('category_ids[]', id);
    })

    images.value.forEach((file) => {
        formData.append('images[]', file);
    });

    try {
        const response = await axios.post(
            import.meta.env.VITE_LARAVEL_API_URL + '/products',
            formData,
            {
                headers: {
                    Authorization: `Bearer ${token}`,
                    'Content-Type': 'multipart/form-data'
                }
            }
        );

        name.value = '';
        description.value = '';
        price.value = '';
        category_ids.value = [];
        images.value = [];
        fileInput.value.value = "";

        Swal.fire({
            title: "Başarılı",
            text: "Ürün Eklendi",
            icon: "success"
        });

    } catch (error) {
        Swal.fire({
      title: "Hata",
      text: error.response.data.message[0],
      icon: "error"
    });
    }
};

const getCategoriesList = async () => {
    try {
        const token = localStorage.getItem('auth_token');
        const response = await axios.get(import.meta.env.VITE_LARAVEL_API_URL + '/categories/list', {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });

        categoriesList.value = response.data.data;
    } catch (error) {
        console.log(error);
    }
}
getCategoriesList();
</script>
