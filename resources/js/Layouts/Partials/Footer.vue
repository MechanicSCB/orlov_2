<script setup>
import Logo from "@/Layouts/Partials/Logo.vue";
import SocialIcons from "@/Layouts/Partials/SocialIcons.vue";
</script>

<template>
    <div class="mt-auto h-50 px-12 py-8 bg-[#222]">
        <Link class="mb-2" :href="route('home')"><Logo  class="w-[120px]"></Logo></Link>

        <div class="text-white flex justify-between flex-col desktop:flex-row text-sm text-white">
            <!-- Left footer menu -->
            <div class="mt-6 flex flex-col space-y-[10px] w-[300px] text-[#cacaca] w-full desktop:w-1/4 mb-8 pr-3">
                <Link class="hover:underline" :href="route('home')">Главная страница</Link>
                <Link class="hover:underline" :href="route('posts.show', 'about')">О проекте</Link>
                <Link class="hover:underline" :href="route('posts.show', 'course')">Курс ПДД</Link>
                <Link class="hover:underline" :href="route('posts.show', 'ocifrovka')">Оцифровка ДТП</Link>
                <Link class="hover:underline" :href="route('posts.show', 'kak-uluchshit-situaciyu-na-nashih-dorogah')">Как улучшить ситуацию на наших дорогах</Link>
                <Link class="hover:underline" :href="route('posts.show', 'lovushki')">Покажите детям видео про ловушки для пешеходов</Link>
            </div>

            <!-- Center footer menu -->
            <div class="mt-6 flex flex-col space-y-[10px] w-[300px] text-[#cacaca] w-full desktop:w-1/4 mb-8 pr-3">
                <Link class="hover:underline" :href="route('posts.show', 'about')">Политика конфиденциальности</Link>
                <Link class="hover:underline" :href="route('posts.show', 'about')">Пользовательское соглашение</Link>
                <Link class="hover:underline" :href="route('posts.show', 'about')">Контактная информация</Link>
                <Link class="hover:underline" :href="route('posts.show', 'about')">Поддержать проект</Link>
            </div>

            <!-- Right footer block -->
            <div class="pl-3 flex flex-col w-full desktop:w-1/2">
                <div class="flex">
                    <div class="w-1/6 max-w-[120px] desktop:max-w-[64px]">
                        <img class="rounded-full" src="/img/avatar.jpg">
                    </div>
                    <div class="pl-8 w-5/6">
                        <div class="text-xl"><b>Василий Орлов, автор проекта</b></div>
                        <div>Предлагаю конкретные решения как уменьшить смертность на дорогах и как улучшить жизнь водителей и пешеходов</div>
                    </div>
                </div>
                <SocialIcons></SocialIcons>
                <div>
                    <p class="mb-1">support@orlov-dtp.ru</p>
                    <p>© orlov-dtp.ru 2022</p>
                </div>
            </div>

        </div>
    </div>
</template>
