<script setup>
import { usePage } from '@inertiajs/vue3';

const imageUrls = usePage().props.imageUrls;
console.log('url da logo', imageUrls.logo)

</script>
<template>
    <div class="mt-2 mb-2">
        <img :src="imageUrls.logo" alt="" class="img-fluid">
    </div>
</template>
