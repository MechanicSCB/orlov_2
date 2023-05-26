<script setup>
import {router} from "@inertiajs/vue3";

let props = defineProps({
    modelClass:String,
    model:Object,
    votes:String,
});

function authUserVote() {
    let voteValue = 0;
    let model = props.model;

    router.page.props.auth.user?.votes.forEach(function (vote) {
        if (vote.votable_id === model.id.toString()) {
            voteValue = vote.value;
        }
    });

    return voteValue;
}

</script>
<script>


</script>
<template>
    <!-- Vote -->
    <div class="flex space-x-3 text-sm h-6">
        <Link :class="'text-green-700 bg-gray-100 hover:bg-gray-200 rounded w-6 text-center'" method="post" as="button"
              :href="route(authUserVote() ? 'unvote' : 'vote')" :data="{ value: 1, modelType:modelClass, modelId:model.id }" preserve-scroll preserve-state>
            <i v-if="authUserVote()===1" class="bi bi-hand-thumbs-up-fill"></i>
            <i v-else class="bi bi-hand-thumbs-up"></i>
        </Link>
        <span :class="(votes > 0 ? 'text-[#28a745]' : votes < 0 ? 'text-[#dc3545]' : '')">{{ votes ?? 0 }}</span>
        <Link :class="'text-red-700 bg-gray-100 hover:bg-red-200 rounded w-6 text-center'" method="post" as="button"
              :href="route(authUserVote() ? 'unvote' : 'vote')" :data="{ value: -1, modelType:modelClass, modelId:model.id }" preserve-scroll preserve-state>
            <i v-if="authUserVote()===-1" class="bi bi-hand-thumbs-down-fill"></i>
            <i v-else class="bi bi-hand-thumbs-down"></i>
        </Link>
    </div>
</template>

