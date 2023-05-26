<script setup>
import Header from "@/Layouts/Partials/Header.vue";
import MainMenu from "@/Layouts/Partials/MainMenu.vue";
import PublicationsMenu from "@/Layouts/Partials/PublicationsMenu.vue";
import Footer from "@/Layouts/Partials/Footer.vue";
import Flash from "@/Layouts/Partials/Flash.vue";

</script>

<template>
    <div class="min-h-screen flex flex-col w-full">
        <Header></Header>
        <MainMenu></MainMenu>
        <PublicationsMenu></PublicationsMenu>

        <main class="mx-auto w-full sm:max-w-[540px] md:max-w-[720px] lg:max-w-[960px] xl:max-w-[1140px] px-[15px] py-7">
            <Flash></Flash>
            <slot/>
        </main>

        <Footer></Footer>
    </div>
</template>
