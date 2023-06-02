<script setup>
import Pagination from "@/Pages/Posts/Partials/Pagination.vue";

defineProps({comments:Object,});
</script>

<template>
    <h2 class="text-xl mb-5">Все мои комментарии</h2>
    <span v-if="comments.data.length === 0">Вы не оставляли комментарии</span>
    <div v-else>
        <div class="text-sm border-b border-gray-200 py-2" v-for="comment in comments.data">
            <span class="mt-2 text-gray-500 text-xs">{{ comment.published_at }}</span>
            <br>
            <div v-html="comment.body"></div>
        </div>

        <Pagination v-if="comments.last_page > 1" :links="comments.links" class="mt-6 mb-4"/>
    </div>
</template>
