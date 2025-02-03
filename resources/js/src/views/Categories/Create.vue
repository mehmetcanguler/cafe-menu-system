<template>
    <div class="row">
        <div class="card">
            <div class="card-body">
                <form @submit.prevent="handleCreate">
                    <div class="mb-3">
                        <label for="name" class="form-label">Kategori adı</label>
                        <input v-model="name" type="text" class="form-control" id="name"
                            placeholder="Kategori adını giriniz" required />
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

const handleFileChange = (event) => {
    images.value = Array.from(event.target.files);
};

const handleCreate = async () => {
    const token = localStorage.getItem('auth_token');
    const formData = new FormData();
    formData.append('name', name.value);

    images.value.forEach((file) => {
        formData.append('images[]', file);
    });

    try {
        const response = await axios.post(
            import.meta.env.VITE_LARAVEL_API_URL + '/categories',
            formData,
            {
                headers: {
                    Authorization: `Bearer ${token}`,
                    'Content-Type': 'multipart/form-data'
                }
            }
        );

        name.value = '';
        fileInput.value.value = "";

        Swal.fire({
            title: "Başarılı",
            text: "Kategori Eklendi",
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
</script>
