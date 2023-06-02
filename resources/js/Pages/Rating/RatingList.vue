<script setup>
import Avatar from "@/Layouts/Partials/Avatar.vue";

defineProps(['users', 'ratingPeriod', 'columnTitle']);
</script>

<template>
    <div class="w-full flex flex-col space-y-5">
        <h2 class="text-2xl">{{ columnTitle }}</h2>
        <div v-for="(user, index) in users" :key="user.id" class="flex justify-between items-center">
            <div class="flex items-center">
                <Avatar class="max-w-[40px] h-[40px] mr-6"  :src="user.profile_photo_url"></Avatar>
                <Link :href="route('users.show', user)" class="text-sm hover:underline" :class="index > 0 ? '' : 'font-bold'">{{ user.name }}</Link>
            </div>
            <div class="ml-2 text-sm text-[#28a745] font-bold">{{ user[ratingPeriod] }}</div>
        </div>
    </div>
</template>
