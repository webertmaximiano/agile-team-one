<script setup>
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
    <div class="site-layout">
                <!-- Page Content -->
            <main>
                <slot />
            </main>
    </div>
</template>
  
<!-- Estilos -->    
<style>

</style>
  