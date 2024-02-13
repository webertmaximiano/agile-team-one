<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Header from './Partials/Header.vue';
import SiteLayout from '../../Layouts/SiteLayout.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <SiteLayout>

        <Head title="EstouOn - Coloque seu Negócio Online!. Crie sua conta!" />

        <Header
            :canLogin="canLogin"
            :canRegister="canRegister"
        />

    </SiteLayout>
</template>
