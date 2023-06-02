<script setup>
import CommentsCount from "@/Pages/Posts/Partials/CommentsCount.vue";
import RatingList from "@/Pages/Rating/RatingList.vue";
import Avatar from "@/Layouts/Partials/Avatar.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

defineProps({
    comments:Object,
    posts:Object,
    weekBestUsers:Object, monthBestUsers:Object, bestUsers:Object,
});

function excerpt(text, len = 30) {
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
<script>
export default {
    computed:{
        currentMonth (){
            const months = ['январь', 'февраль', 'март', 'апрель', 'май', 'июнь', 'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь'];

            return months[new Date().getMonth()];
        }
    },
}
</script>

<template>
    <Head title="Люди не должны погибать на дорогах ">
        <meta typeof="description" content="Публикации пользователей" head-key="description">
    </Head>

    <div class="mt-2 flex text-sm flex-col lg:flex-row lg:space-x-8">
        <!-- Main post -->
        <div class="w-full lg:w-2/3">
            <iframe class="w-[640px] max-w-full h-[360px]" src="https://www.youtube.com/embed/p6mEwDRUdvk" title="YouTube video player"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen="" frameborder="0"></iframe>
            <br><br>
            <p>Меня зовут <a href="/author">Василий Орлов</a>, и сайт orlov-dtp.ru - это мой проект, нацеленный на
                уменьшение смертности на дорогах России. Этот сайт появился как логическое продолжение моего <a
                    target="_blank" rel="nofollow"
                    href="https://www.youtube.com/channel/UCEdi9MaYP4IEJjOTMOlkpeQ/featured">ютуб-канала</a>, на котором
                я рассказываю как не попасть в ДТП.
            </p>
            <br>
            <p>Основной целью этого сайта являются проекты <a href="/posts/course">Курс ПДД</a> и <a
                href="/posts/ocifrovka">Оцифровка ДТП</a>. И в качестве первого шага по оцифровке ДТП мы хотим
                проставить геолокации(точное место на карте) к 50 000 видеозаписей аварий. Вы можете помочь нам с этим и
                <a href="/geo/help">проставить геолокации</a> к нескольким ДТП, внеся свой вклад в наполнение базы
                данных ДТП.
            </p>
            <br>
            <p>Проставление геолокаций к ДТП - это сейчас основная функция сайта, но также вы ещё можете почитать на
                сайте <a href="/kak-uluchshit-situaciyu-na-nashih-dorogah">серию моих статей</a> о безопасности на
                дорогах, а также вы можете <a href="/posts/write" class="item">создавать собственные публикации</a>(например,
                опубликовать свое ДТП) и комментировать публикации других пользователей.
            </p>
        </div>
        <!-- Recent comments -->
        <div class="w-full lg:w-1/3 flex flex-col space-y-4">
            <h2 class="text-xl font-bold">Последние комментарии</h2>
            <div class="flex flex-col space-y-2.5" v-for="comment in comments" :key="comment.id">
                <Link class="flex items-center space-x-3" :href="route('users.show', comment.user)">
                    <Avatar :src="comment.user.profile_photo_url"></Avatar>
                    {{ comment.user.name }}
                </Link>
                <Link class="excerpt excerpt7" :href="route('posts.show', comment.post.slug) + '#comment_' + comment.id" v-html="comment.body"/>
                <Link class="font-bold hover:underline text-md" :href="route('posts.show', comment.post.slug)">
                    {{ excerpt(comment.post.title) }}
                    <i class="bi bi-chevron-right"></i>
                </Link>
            </div>
        </div>
    </div>

    <!-- Recent posts -->
    <div>
        <h1 class="my-6 text-3xl font-bold">Недавние публикации пользователей</h1>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <Link v-for="post in posts" class="mb-6 flex flex-col space-y-3" :href="route('posts.show', post.slug)">
                    <h1 class="excerpt excerpt3 text-xl font-bold">
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
                    <div v-html="excerpt(post.body, 200)"></div>
                    <div class="flex items-center text-xs">
                        <i class="bi bi-chat mr-2"></i>
                        <CommentsCount :comments_count="post.all_comments_count"></CommentsCount>
                    </div>
                </Link>
        </div>
    </div>

    <div class="w-full text-center">
        <Link :href="route('posts.index')">
            <PrimaryButton class="normal-case !bg-blue-500 hover:!bg-blue-600">Показать больше публикаций</PrimaryButton>
        </Link>
    </div>

    <!-- Rating -->
    <div class="mt-10 flex w-full flex-col md:flex-row md:space-x-12 space-y-8 md:space-y-0">
        <RatingList :users="weekBestUsers" rating-period="weekRating" column-title="За эту неделю"></RatingList>
        <RatingList :users="monthBestUsers" rating-period="monthRating" :column-title="'За '+ currentMonth"></RatingList>
        <RatingList :users="bestUsers" rating-period="rating" column-title="За всё время"></RatingList>
    </div>

</template>

<style>
.excerpt {
    word-break: break-word;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    white-space: pre-wrap;
}

.excerpt3 {
    -webkit-line-clamp: 3;
}

.excerpt7 {
    -webkit-line-clamp: 7;
}
</style>
