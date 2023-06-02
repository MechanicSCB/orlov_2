<script setup>
import UserLocations from "@/Pages/Locations/Partials/UserLocations.vue";
import Pagination from "@/Pages/Posts/Partials/Pagination.vue";

defineProps({locations:Object,});
</script>

<template>
    <UserLocations :locations="locations.data"></UserLocations>

    <Pagination v-if="locations.last_page > 1" :links="locations.links" class="mt-3 mb-4"/>
</template>
