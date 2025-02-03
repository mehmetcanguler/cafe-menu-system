<template>
    <div class="row">
        <div class="card">
            <div class="card-body">
                <form @submit.prevent="handleUpdate">

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
                    <h5>Mevcut Resimler</h5>
                    <div class="row">
                        <div v-for="(image, index) in category.images" :key="index" class="col-md-3 mb-3">
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

const props = defineProps({
    id: { type: String, required: true },
});

const name = ref('');
const images = ref([]);
const category = ref({});
const fileInput = ref(null);

const getCategory = async () => {
    const token = localStorage.getItem('auth_token');
    const response = await axios.get(import.meta.env.VITE_LARAVEL_API_URL + `/categories/${props.id}`, {
        headers: {
            Authorization: `Bearer ${token}`
        },
    });
    console.log(response.data.data);
    category.value = response.data.data;
    name.value = category.value.name;
};

getCategory();

const handleFileChange = (event) => {
    images.value = Array.from(event.target.files);
};

const removeImage = async (id) => {
    if (window.confirm("Bu resmi silmek istediğinize emin misiniz?")) {
        const token = localStorage.getItem('auth_token');
        const response = await axios.delete(import.meta.env.VITE_LARAVEL_API_URL + `/categories/image/${id}/`, {
            headers: {
                Authorization: `Bearer ${token}`
            },
        });
        getCategory();

        Swal.fire({
            title: "Başarılı",
            text: "Resim silindi",
            icon: "success"
        });
    }

}

const handleUpdate = async () => {
    const token = localStorage.getItem('auth_token');
    const formData = new FormData();
    formData.append('name', name.value);

    if (images.value.length > 0) {
        createImage();
    }

    try {
        const response = await axios.put(
            import.meta.env.VITE_LARAVEL_API_URL + '/categories/' + props.id,
            formData,
            {
                headers: {
                    Authorization: `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            }
        );

        Swal.fire({
            title: "Başarılı",
            text: "Kategori Güncellendi",
            icon: "success"
        });
    } catch (error) {
        console.log(error);
        Swal.fire({
            title: "Hata",
            text: error.response.data.message[0],
            icon: "error"
        });
    }
};
const createImage = async () => {

    const token = localStorage.getItem('auth_token');
    const formData = new FormData();
    images.value.forEach((image) => {
        formData.append('images[]', image);
    })
    try {
        await axios.post(
            import.meta.env.VITE_LARAVEL_API_URL + '/categories/' + props.id + '/images/multiple',
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
        getCategory();
    } catch (error) {
        console.error('Hata oluştu:', error.response?.data || error.message);
    }
}
</script>
