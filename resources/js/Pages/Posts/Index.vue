<script setup>
import Pagination from "@/Pages/Posts/Partials/Pagination.vue";
import Avatar from "@/Layouts/Partials/Avatar.vue";
import CommentsCount from "@/Pages/Posts/Partials/CommentsCount.vue";

let props = defineProps({
    posts: Object,
});

function excerpt(text, len = 200) {
    if (!text) {
        return text;
    }

    text = text.replace(/<\/?[^>]+(>|$)/g, "");

    if (text.length > len) {
        text = text.substring(0, len) + '...';
    }

    return text;
}
</script>

<template>
    <h1 class="mt-3 text-3xl font-bold mb-6">Публикации пользователей</h1>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div class="mb-6" v-for="post in posts.data">
            <Link class="flex flex-col space-y-3" :href="route('posts.show', post.slug)">
                <h1 class="excerpt text-xl font-bold">
                    {{ post.title }}
                </h1>
                <div class="flex justify-between text-xs items-center" v-if="post.user">
                    <div class="flex items-center">
                        <Avatar :src="post.user.profile_photo_url" class="max-w-[24px]"></Avatar>
                        <span>{{ post.user.name }}</span>
                    </div>
                    <span class="text-gray-500">{{ post.published_at }}</span>
                </div>
                <div v-if="post.preview">
                    <img class="h-[200px] w-full object-cover object-center" :src="'/storage/'+ post.preview">
                </div>
                <div v-html="excerpt(post.body)"></div>
                <div class="flex items-center text-xs">
                    <i class="bi bi-chat mr-2"></i>
                    <CommentsCount :comments_count="post.all_comments_count"></CommentsCount>
                </div>

            </Link>
        </div>
    </div>

    <!-- Paginator -->
    <Pagination :links="posts.links" class="mt-3 mb-4"/>
</template>

<style>
.excerpt {
    word-break: break-word;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    white-space: pre-wrap;
}

.post-body {
    word-break: break-word;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    white-space: pre-wrap;
}

</style>
