<template>
    <div class="row">
        <div class="d-flex justify-content-end">
            <router-link :to="{ name: 'products.create' }" class="btn btn-success">
                Ürün Ekle
            </router-link>
        </div>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Resim</th>
                <th scope="col">Adı</th>
                <th scope="col">Slug</th>
                <th scope="col">Açıklama</th>
                <th scope="col">İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="product in products">
                <td>
                    <TableImageComponent v-for="image in product.images" :url=image.url :alt=image.alt />
                </td>
                <td>{{ product.name }}</td>
                <td>{{ product.slug }}</td>
                <td class="text-truncate">{{ product.description }}</td>
                <td>
                    <div class="d-flex gap-2">
                        <div class="col-lg-2">
                            <router-link :to="{ name: 'products.edit', params: { id: product.id } }"
                                class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil"></i>

                            </router-link>
                        </div>
                        <div class="col-lg-2">
                            <button @click="handleDelete(product.id)" class="btn btn-danger btn-sm">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>



                </td>
            </tr>

        </tbody>
    </table>
</template>

<script setup>
import { ref } from 'vue';
import TableImageComponent from '../../Components/TableImageComponent.vue';
import Swal from 'sweetalert2'

const products = ref([]);
const getProducts = async () => {
    try {
        const token = localStorage.getItem('auth_token');
        const response = await axios.get(import.meta.env.VITE_LARAVEL_API_URL + '/products', {
            headers: {
                Authorization: `Bearer ${token}`
            },
        });

        products.value = response.data.data;
    } catch (error) {
        console.log(error);
    }
}

getProducts();


const handleDelete = (id) => {
    if (window.confirm("Bu ürünü silmek istediğinize emin misiniz?")) {
        try {
            const token = localStorage.getItem('auth_token');
            const response = axios.delete(import.meta.env.VITE_LARAVEL_API_URL + `/products/${id}`, {
                headers: {
                    Authorization: `Bearer ${token}`
                },
            });

            Swal.fire({
                title: "Başarılı",
                text: "Ürün silindi",
                icon: "success"
            });

            getProducts();
        } catch (error) {
            Swal.fire({
                title: "Hata",
                text: error.response.data.message[0],
                icon: "error"
            });
        }
    }
};
</script>