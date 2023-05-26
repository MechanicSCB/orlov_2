<script setup>
    defineProps({
        src: String,
        alt: String,
    });
</script>

<template>
    <img class="mr-3 max-w-[28px] rounded-full aspect-square object-cover" :src="src" alt="alt">
</template>


