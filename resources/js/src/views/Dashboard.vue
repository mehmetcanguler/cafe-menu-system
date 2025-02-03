<template>
    <h1>Hoşgeldiniz {{ name }} {{ username }}</h1>
</template>

<script setup>

const username = localStorage.getItem('username');
const name = localStorage.getItem('name');
</script>