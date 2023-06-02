<script setup>
// import {Inertia} from "@inertiajs/inertia";

defineProps({
    locations:Object,
});

const getLocationStatus = (location) => {
    if(location.note){
        return location.note;
    }

    if(! location.verified_at){
        return 'Ожидает подтверждения другим пользователем'
    }

    return "Принята (совпала с ответом другого пользователя) <br> Вам начислено +" + (location.rating?.value ?? '?') + " рейтинга";
};

</script>
<template>
    <div class="mt-6">
        <h2 class="my-4 text-lg">Геолокации, которые вы уже проставили</h2>
        <div v-if="locations.length === 0" class="text-sm">
            Вы ещё не проставляли геолокации
        </div>
        <div v-else class="text-sm">
            <div class="w-full flex font-bold space-x-3 border-t border-gray-200 py-3">
                <h3 class="w-1/4">Ссылка на ДТП и дата</h3>
                <h3 class="w-1/4">Геолокация, которую вы указали</h3>
                <h3 class="w-1/2">Статус проверки геолокации</h3>
            </div>
            <div v-for="location in locations" :key="location.id"
                 class="w-full flex space-x-3 border-t border-gray-200 py-3"
            >
                <div class="w-1/4">
                    <Link class="excerpt excerpt1 text-blue-700" href="">{{ location.accident.title }}</Link>
                    <p class="text-xs">{{ location.created_at }}</p>
                </div>
                <div class="w-1/4">{{ location.lat ?? '?' }}, {{ location.long ?? '?' }}</div>
                <div class="w-1/2" :class="location.verified_at ? 'text-[#28a745] font-bold' : ''" v-html="getLocationStatus(location)"></div>
            </div>
        </div>
    </div>
</template>
