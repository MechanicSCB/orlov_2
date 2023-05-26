<script setup>
import {useForm} from "@inertiajs/vue3";
import Comments from "@/Pages/Posts/Partials/Comments.vue";
import Avatar from "@/Layouts/Partials/Avatar.vue";
import CommentsCount from "@/Pages/Posts/Partials/CommentsCount.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Vote from "@/Pages/Posts/Partials/Vote.vue";

let props = defineProps({
    post:Object,
});

let form = useForm({
    comment:'',
});

function submit() {
    form.post(route('posts.comments.store', props.post.id),{
        preserveScroll:true,
    });
    form.comment = '';
}
</script>

<template>
    <Head :title="post.title">
        <meta typeof="description" :content="post.title" head-key="description">
    </Head>
    <div class="flex flex-col space-y-2">
        <!-- Title -->
        <h1 class="text-3xl font-bold mb-4">{{ post.title }}</h1>

        <!-- User -->
        <Link v-if="post.user" :href="route('users.show', post.user)" class="flex items-center text-sm hover:underline">
            <Avatar :src="post.user.profile_photo_url"></Avatar>
            <span>{{ post.user.name }}</span>
        </Link>

        <!-- Published -->
        <span class="text-gray-500 text-xs mb-4">{{ post.published_at }}</span>

        <!-- Video -->
        <iframe v-for="video in post.videos" :key="video.id"
                class="w-[400px] h-[250px] sm:w-[600px] sm:h-[400px]  md:w-[800px] md:h-[500px] max-w-full"
                :src="video.embedUrl" title="YouTube video player" frameborder="0"
                allowfullscreen></iframe>

        <!-- Text -->
        <div id="post-text" class="mb-8" v-html="post.body"></div>

        <!-- Photos -->
        <div class="mb-3" v-for="photo in post.photos">
            <img class="max-h-[500px]" :src="'/storage/' + photo.path" alt="">
        </div>

        <!-- Comments Count -->
        <div class="flex justify-between items-center mt-2">
            <div class="flex">
                <div class="flex text-xl">
                    <i class="bi bi-chat mr-2"></i>
                    <Link :href="route('posts.show', post.slug)">
                        <CommentsCount :comments_count="post.all_comments_count"></CommentsCount>
                    </Link>
                </div>
            </div>

            <!-- Vote -->
            <Vote model-class="Post" :model="post" :votes="post.votes_sum_value"></Vote>
        </div>
        <hr>
        Еще публикации: <br>

        <h2 class="text-2xl py-4">Комментарии</h2>
        <!-- Add Comment -->
        <form v-if="$page.props.auth.user" @submit.prevent="submit" class="">
            <textarea v-model="form.comment" name="comment" id="" cols="30" rows="2"
                      class="w-full rounded border-gray-200" placeholder="Написать комментарий..."></textarea>

            <PrimaryButton type="submit" class="mt-1 !bg-blue-500 hover:!bg-blue-600 !font-bold" :disabled="form.processing">Отправить</PrimaryButton>
        </form>
        <div v-else>
            Чтобы оставить комментарий, <Link class="reference" :href="route('login')">Войдите</Link> или <Link class="reference" :href="route('register')">Зарегистрируйтесь</Link> на сайте.
        </div>

        <hr>

        <!-- Comments -->
        <Comments :parent="post"></Comments>
    </div>
</template>
<style>
#post-text p {
    margin-bottom: 1rem;
}

a.reference,
#post-text a {
    color: #007bff;
    text-decoration: none;
}

a.reference:hover,
#post-text a:hover {
    color: #0056b3;
    text-decoration: underline;
}
</style>
