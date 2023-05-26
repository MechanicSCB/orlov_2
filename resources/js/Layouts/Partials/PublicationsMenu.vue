<script setup>
import ProfileDropdown from "./ProfileDropdown.vue";

</script>
<template>
    <div class="hidden xl:flex justify-between border-b border-gray-200 px-14 py-3 h-12 text-sm">
        <!-- Left submenu -->
        <div class="flex">
            <Link class="pr-4 border-r hover:underline" :href="route('posts.index')" preserve-scroll>
                Публикации пользователей
            </Link>
            <Link class="px-4 border-r hover:underline" :href="route('rating')" preserve-scroll>
                Рейтинг пользователей
            </Link>
            <Link class="px-4 hover:underline" :href="route('posts.create')" preserve-scroll>
                <i class="bi bi-pencil mr-1"></i>
                Написать пост
            </Link>
        </div>

        <!-- ProfileDropdown -->
        <ProfileDropdown></ProfileDropdown>
    </div>
</template>
