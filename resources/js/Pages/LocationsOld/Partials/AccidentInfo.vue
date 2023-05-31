<script setup>

const props = defineProps({
    accident: Object,
})
</script>
<template>
    <div class="lg:w-7/12 pr-3">
        <p class="text-sm">
            Внимательно посмотрите видео, изучите текстовое описание и попробуйте определить точное место, где
            произошло ДТП.
        </p>
        <hr class="my-4">
        <p>Краткая инструкция по заполнению <i class="bi bi-chevron-down"></i></p>
        <hr class="my-4">
        <div class="mb-3" v-for="(video,index) in accident.videos">
            <iframe :id="'accidentVideo_' + index" class="w-full h-[250px] sm:h-[250px] lg:h-[300px]"
                    :src="video.url" title="YouTube video player" frameborder="0"
                    allowfullscreen>
            </iframe>
        </div>
        <div class="accident-info mt-5 flex flex-col space-y-4">
            <p class="text-xs text-gray-500">Ориентировочная дата ДТП: {{ accident.date }}
                <span v-if="accident.region">Ориентировочный регион: {{ accident.region.name }}</span>
            </p>
            <h2 class="text-lg font-semibold">{{ accident.title }}</h2>
            <p>
                {{ accident.description }}
            </p>
            <p class="text-xs text-gray-500">Источник текста: rusdtp.ru</p>
            <p class="text-xs text-gray-500">
                Обратите внимание, что координаты могут быть указаны прямо на самой
                видеозаписи, если у регистратора
                включена функция GPS.
            </p>

            <!-- TODO: replace with accident photos -->
            <img src="https://523720.selcdn.ru/s1/dtp_image/15676-1-1.jpg" alt="">
            <img src="https://523720.selcdn.ru/s1/dtp_image/15676-2.jpg" alt="">
            <img src="https://523720.selcdn.ru/s1/dtp_image/15676-1-1.jpg" alt="">
        </div>
    </div>
</template>
