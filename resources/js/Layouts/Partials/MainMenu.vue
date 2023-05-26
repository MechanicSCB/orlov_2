<template>
    <div class="hidden xl:flex justify-between bg-[#f1f3f6] px-14 py-4 text-sm">
        <!-- Left submenu -->
        <div class="flex space-x-6 mr-2">
            <Link class="hover:underline" :href="route('posts.show', 'about')" preserve-scroll>О проекте</Link>
            <Link class="hover:underline" :href="route('posts.show', 'course')" preserve-scroll>Курс ПДД</Link>
            <Link class="hover:underline" :href="route('posts.show', 'ocifrovka')" preserve-scroll>Оцифровка ДТП</Link>
            <Link class="hover:underline" :href="route('posts.show', 'kak-uluchshit-situaciyu-na-nashih-dorogah')" preserve-scroll>Как улучшить ситуацию на наших дорогах</Link>
            <Link class="hover:underline" :href="route('posts.show', 'lovushki')" preserve-scroll>Покажите детям видео про ловушки для пешеходов</Link>
        </div>

        <!-- Right submenu -->
        <div>
            <i class="bi bi-wallet mr-1"></i>
            <Link class="hover:underline" :href="route('posts.index')" preserve-scroll>Поддержать проект</Link>
        </div>
    </div>
</template>
