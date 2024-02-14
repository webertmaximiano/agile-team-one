<script setup>
import { usePage } from '@inertiajs/vue3';

const imageUrls = usePage().props.imageUrls;
//console.log('url da logo', imageUrls.logo)

</script>
<template>
        <img :src="imageUrls.logo" alt="" class="img-fluid">
</template>
