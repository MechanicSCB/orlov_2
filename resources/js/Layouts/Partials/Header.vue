<script setup>
import Logo from '@/Layouts/Partials/Logo.vue';
import SocialIcons from "@/Layouts/Partials/SocialIcons.vue";
import {ref} from "vue";
import ResponsiveNavLink from '@/Layouts/Partials/ResponsiveNavLink.vue';
import ProfileDropdown from "@/Layouts/Partials/ProfileDropdown.vue";

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="min-h-[55px] pl-12 pt-2.5 pb-[5px] bg-[#222] text-white pr-4 xl:pr-12">
        <!-- Hamburger -->
        <div class="!absolute left-2 flex items-center xl:hidden ">
            <button
                class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-gray-500 transition"
                @click="showingNavigationDropdown = ! showingNavigationDropdown">
                <svg
                    class="h-6 w-6"
                    stroke="currentColor"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <path
                        :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"
                    />
                    <path
                        :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>
        </div>

        <!-- Header Content -->
        <div class="flex justify-between items-center space-x-3">
            <!-- Logo -->
            <div class="flex items-center xl:w-[200px]">
                <Link :href="route('home')">
                    <Logo class="block h-8 xl:h-10 w-auto"/>
                    <p class="hidden xl:block mt-1 text-center text-[0.9em] font-bold">
                        Люди не должны<br>
                        погибать на дорогах
                    </p>
                </Link>
            </div>

            <!-- Head Text -->
            <div class="hidden xl:flex text-xl space-x-6">
                <p>
                    Помогите проставить<br>
                    геолокации к ДТП!
                </p>
                <p class="text-center underline">
                    <Link class="text-2xl no-underline" :href="route('location.create')">Помочь</Link><br>
                    <Link class="text-sm no-underline" href="">Зачем это надо?</Link>
                </p>
                <p>
                    На данный момент проставлено<br>
                    19217 геолокаций из 50 000
                </p>
            </div>

            <!-- Head Text Small-->
            <div class="flex xl:hidden">
                <Link class="text-sm text-center hover:underline" :href="route('location.create')">
                    Геолокации:<br>
                    19217 из 50 000
                </Link>
            </div>

            <SocialIcons class="hidden xl:flex"></SocialIcons>

            <!-- Orlov Avatar -->
            <div class="hidden xl:flex xl:flex-col">
                <h2 class="text-3xl font-bold leading-normal">orlov-dtp.ru</h2>
                <div class="mt-1 flex items-center">
                    <div class="text-sm">
                        <p>Проект</p>
                        <a class="underline" href="">Василия Орлова</a>
                    </div>
                    <div class="ml-3 w-9">
                        <img class="rounded-full" src="/img/avatar_50.jpg" alt="">
                    </div>
                </div>
            </div>

            <!-- User/Login -->
            <ProfileDropdown class="flex xl:hidden"></ProfileDropdown>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div class="mt-[55px] pt-3 px-6 bg-[#222] text-white xl:hidden w-4/5 absolute z-10 flex flex-col"
         :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}"
    >
        <ResponsiveNavLink @click="showingNavigationDropdown = false" :href="route('home')">Главная страница</ResponsiveNavLink>
        <ResponsiveNavLink @click="showingNavigationDropdown = false" :href="route('location.create')">Проставлять геолокаций к ДТП</ResponsiveNavLink>
        <ResponsiveNavLink @click="showingNavigationDropdown = false" :href="route('posts.show', 'course')">Курс ПДД</ResponsiveNavLink>
        <ResponsiveNavLink @click="showingNavigationDropdown = false" :href="route('posts.show', 'ocifrovka')">Оцифровка ДТП</ResponsiveNavLink>
        <ResponsiveNavLink @click="showingNavigationDropdown = false" :href="route('posts.show', 'kak-uluchshit-situaciyu-na-nashih-dorogah')">Как улучшить ситуацию на наших дорогах?</ResponsiveNavLink>
        <ResponsiveNavLink @click="showingNavigationDropdown = false" :href="route('posts.show', 'lovushki')">Покажите детям видео про ловушки для пешеходов</ResponsiveNavLink>
        <ResponsiveNavLink @click="showingNavigationDropdown = false" :href="route('rating')">Рейтинг пользователей</ResponsiveNavLink>
        <ResponsiveNavLink @click="showingNavigationDropdown = false" :href="route('posts.index')">Публикации пользователей</ResponsiveNavLink>
        <ResponsiveNavLink @click="showingNavigationDropdown = false" :href="route('posts.create')"><i class="bi bi-pencil mr-2"></i>Написать пост</ResponsiveNavLink>
        <ResponsiveNavLink @click="showingNavigationDropdown = false" :href="route('posts.show', 'about')">О проекте</ResponsiveNavLink>
        <Link class="mt-3" @click="showingNavigationDropdown = false" :href="route('posts.index')">
            <button class="px-3 py-2 bg-[#f8f9fa] text-black rounded hover:bg-[#dae0e5]">
                <i class="bi bi-wallet mr-2"></i>Поддержать проект
            </button>
        </Link>
        <div class="mt-3 border-t border-gray-700 text-sm">

            <SocialIcons></SocialIcons>

            <Link class="mt-3 hover:underline mb-2" @click="showingNavigationDropdown = false" :href="route('posts.show', 'about')">
                Проект <span class="underline">Василия Орлова</span>
            </Link>
            <Link class="mt-2 block w-fit mb-8" @click="showingNavigationDropdown = false" :href="route('posts.show', 'about')">
                <img src="/img/avatar.jpg" alt="">
            </Link>
        </div>
    </div>
</template>


