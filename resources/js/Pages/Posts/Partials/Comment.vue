<script setup>
import {router, useForm} from "@inertiajs/vue3";
import Avatar from "@/Layouts/Partials/Avatar.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Vote from "@/Pages/Posts/Partials/Vote.vue";
import {inject} from "vue";

let props = defineProps({comment:Object})
let form = useForm({
    comment: '',
    parent_id: '',
});

let visibleForm = inject('visibleForm');
function submit() {
    form.parent_id = props.comment.id;
    form.post(route('posts.comments.store', router.page.props.post.id),{
        preserveScroll: true,
    });

    form.comment = '';
    visibleForm.value = null;
}

function toggleForm(){
    if(visibleForm.value === form){
        visibleForm.value = null;
    }else{
        visibleForm.value = form;
    }
}
</script>

<template>
    <div :id="'comment_' + comment.id" class="flex justify-between">
        <!-- Comment user -->
        <div class="flex items-center text-sm">
            <Avatar :src="comment.user.profile_photo_url"></Avatar>
            <div class="flex flex-col">
                <Link :href="route('users.show', comment.user)" class="font-bold hover:underline">{{ comment.user.name }}</Link>
                <span class="mt-1 text-gray-500 text-xs">{{ comment.published_at }}</span>
            </div>
        </div>

        <!-- Vote -->
        <Vote model-class="Comment" :model="comment" :votes="comment.votes_sum_value"></Vote>
    </div>

    <div class="comment-body mt-1 text-sm" v-html="comment.body"></div>

    <button @click="toggleForm" class="mt-2 px-2 py-1 rounded bg-gray-100 hover:bg-gray-200 text-sm mb-3">
        <i class="bi bi-reply"></i>
        Ответить
    </button>

    <!-- Add Comment -->
    <form v-if="visibleForm === form" class="my-4" @submit.prevent="submit">
        <div v-if="$page.props.auth.user">
            <p class="text-xs text-gray-500">Написать ответ</p>
            <textarea v-model="form.comment" name="comment" cols="30" rows="1"
                      class="my-2 w-full border-gray-200 rounded !normal-case" placeholder="Введите текст ответного комментария"></textarea>
            <PrimaryButton type="submit" class="!bg-blue-500 hover:!bg-blue-600 !font-bold"
                       :disabled="form.processing"  preserve-scroll>Отправить
            </PrimaryButton>
            <button @click="toggleForm" class="ml-3 px-4 py-1 rounded bg-gray-100 hover:bg-gray-200">Отмена
            </button>
        </div>
        <div v-else>
            Чтобы оставить комментарий, <Link class="reference" :href="route('login')">Войдите</Link> или <Link class="reference" :href="route('register')">Зарегистрируйтесь</Link> на сайте.
        </div>
    </form>
</template>

<style>
.comment-body a{
    color: #007bff;
    text-decoration: none;
}
.comment-body a:hover{
    color: #0056b3;
    text-decoration: underline;
}
</style>
