<script setup>
defineProps({
    member:Object,
});
</script>
<template>
    <div class="p-1 rounded border w-fit"><img class="w-40 h-40 object-cover" :src="member.profile_photo_url" alt=""></div>
    <h1 class="mt-4 text-3xl">{{ member.name }}</h1>
    <div class="mt-2 text-sm">Рейтинг: <span class="font-bold bg-[#007bff1a] px-1">{{ member.rating ?? 0 }}</span></div>
    <div v-if="member.info" class="mt-4 text-sm"><h2 class="text-xl mb-2">О себе:</h2> {{ member.info }}</div>
    <div>
        <h2 class="mt-10 text-xl mb-3">Последние комментарии</h2>
        <div v-for="comment in member.latest_comments" :key="comment.id" class="flex flex-col space-y-2 mb-4 w-full lg:w-7/12">
            <Link :href="route('users.show', member)" class="flex items-center hover:underline">
                <img class="w-6 h-6 rounded-full" :src="member.profile_photo_url" alt="">
                <span class="ml-2 text-xs">{{ member.name }}</span>
            </Link>
            <div class="text-sm flex">
                <Link class="w-5/6 hover:underline" :href="route('posts.show', comment.post) + '#comment-' + comment.id" v-html="comment.body"/>
                <div class="w-1/6 text-right"><span class="bg-[#f0f0f0] px-2 py-1 font-semibold" :class="comment.votes_sum_value > 0 ? 'text-[#28a745]': comment.votes_sum_value < 0 ? 'text-red-500': 'font-normal'">{{ comment.votes_sum_value ?? 0 }}</span></div>
            </div>
            <Link :href="route('posts.show', comment.post)" class="text-sm font-bold hover:underline">{{ comment.post.title }} <i class="bi bi-chevron-right"></i></Link>
        </div>
        <div v-if="member.latest_comments.length === 0">Пользователь не оставлял комментарии</div>
    </div>
</template>

