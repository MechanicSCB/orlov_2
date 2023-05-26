<script setup>
import Comment from "@/Pages/Posts/Partials/Comment.vue";

defineProps({parent: Object});

function collapseBranch(comment) {
    comment.branch = 'collapse';
}

function expandBranch(comment) {
    comment.branch = 'expand';
}

function toggleBranch(comment) {
    comment.branch = comment.branch === 'collapse' ? 'expand' : 'collapse';
}
</script>

<template>
    <!-- Comments -->
    <div class="flex flex-col w-full">
        <div :id="'comment-'+comment.id" v-for="comment in parent.comments" :key="comment.id">
            <Comment :comment="comment"></Comment>

            <div v-if="comment.branch !== 'collapse'" class="flex">
                <div class="border-l w-3 hover:border-blue-700 hover:border-l-2" @click="collapseBranch(comment)"></div>
                <!-- Comments -->
                <Comments :parent="comment"></Comments>
            </div>
            <div v-if="comment.branch === 'collapse'" @click="expandBranch(comment)"
                 class="text-blue-700 cursor-pointer text-sm hover:underline mb-6">Развернуть ветку
            </div>
        </div>
    </div>
</template>

