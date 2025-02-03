<template>
    <div class="row">
        <div class="card">
            <div class="card-body">
                <form @submit.prevent="handleUpdate">
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
                            id="images" accept="image/*" />
                    </div>
                    <h5>Mevcut Resimler</h5>
                    <div class="row">
                        <div v-for="(image, index) in product.images" :key="index" class="col-md-3 mb-3">
                            <div class="card">
                                <img :src="image.url" class="card-img-top" alt="Kategori Resmi">
                                <div class="card-body text-center">
                                    <button type="button" @click="removeImage(image.id)"
                                        class="btn btn-danger btn-sm">Sil</button>
                                </div>
                            </div>
                        </div>
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
const product = ref({});

const handleFileChange = (event) => {
    images.value = Array.from(event.target.files);
};

const props = defineProps({
    id: { type: String, required: true },
});

const handleUpdate = async () => {
    const token = localStorage.getItem('auth_token');
    const formData = new FormData();
    formData.append('name', name.value);
    formData.append('description', description.value);
    formData.append('price', price.value);

    category_ids.value.forEach((id) => {
        formData.append('category_ids[]', id);
    })

    if (images.value.length > 0) {
        createImage();
    }

    try {
        const response = await axios.put(
            import.meta.env.VITE_LARAVEL_API_URL + '/products/' + props.id,
            formData,
            {
                headers: {
                    Authorization: `Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            }
        );

        name.value = '';
        description.value = '';
        price.value = '';
        category_ids.value = [];
        fileInput.value.value = "";

        Swal.fire({
            title: "Başarılı",
            text: "Ürün Güncellendi",
            icon: "success"
        });

        getProduct();


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

const getProduct = async () => {
    try {
        const token = localStorage.getItem('auth_token');
        const response = await axios.get(import.meta.env.VITE_LARAVEL_API_URL + `/products/${props.id}`, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        product.value = response.data.data;
        name.value = product.value.name;
        description.value = product.value.description;
        price.value = product.value.price;
        category_ids.value = product.value.category_ids;

    } catch (error) {
        console.log(error);
    }
}
getProduct();

const removeImage = async (id) => {
    if (window.confirm("Bu resmi silmek istediğinize emin misiniz?")) {
        const token = localStorage.getItem('auth_token');
        const response = await axios.delete(import.meta.env.VITE_LARAVEL_API_URL + `/products/image/${id}/`, {
            headers: {
                Authorization: `Bearer ${token}`
            },
        });
        getProduct();

        Swal.fire({
            title: "Başarılı",
            text: "Resim silindi",
            icon: "success"
        });
    }

}

const createImage = async () => {

    const token = localStorage.getItem('auth_token');
    const formData = new FormData();
    images.value.forEach((image) => {
        formData.append('images[]', image);
    })
    try {
         await axios.post(
            import.meta.env.VITE_LARAVEL_API_URL + '/products/' + props.id + '/images/multiple',
            formData,
            {
                headers: {
                    Authorization: `Bearer ${token}`,
                    'Content-Type': 'multipart/form-data'
                }
            }
        );
  

        fileInput.value.value = "";
        images.value = [];

        Swal.fire({
            title: "Başarılı",
            text: "Resimler eklendi",
            icon: "success"
        });
        getProduct();
    } catch (error) {
        console.error('Hata oluştu:', error.response?.data || error.message);
    }
}
</script>
