<template>
    <img :src="props.url" :alt="props.alt" class="img-fluid m-1" width="100">
</template>

<script setup>
const props = defineProps({
    url: { type: String, required: true },
    alt: { type: String, required: null },
});
</script>