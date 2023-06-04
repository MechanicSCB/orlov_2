<script setup>

import {ref} from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    accident: Object,
})

let instructionShow = ref(false);
</script>
<template>
    <div class="lg:w-7/12 pr-3">
        <p class="text-sm">
            Внимательно посмотрите видео, изучите текстовое описание и попробуйте определить точное место, где
            произошло ДТП.
        </p>
        <hr class="my-4">
        <p @click="instructionShow = !instructionShow" class="cursor-pointer">
            Краткая инструкция по заполнению
            <i :class="instructionShow ? 'bi bi-chevron-down' :'bi bi-chevron-up'"/>
        </p>
        <div v-if="instructionShow" class="mt-2 text-sm" aria-labelledby="headingOne1">
            <p>Из базы данных вам случайным образом загружается ДТП, к которому еще не поставлена геолокация. Вам нужно посмотреть видео, прочитать текстовое описание ДТП, и на основе этой информации найти на карте точное место аварии(по возможности, с точностью до нескольких метров). </p>
            <p>Место на карте удобнее всего искать с помощью Google или Яндекс панорам(региональные дороги и малые города России гораздо полнее отсняты у Google). На панорамах надо встать курсором на место столкновения, скопировать ссылку и отправить ее системе.</p>
            <p>Советы по определению места:</p>
            <ol class="mt-4 ml-10 list-decimal">
                <li>Координаты могут быть указаны прямо на самой видеозаписи, если у регистратора включена функция GPS. Но в этом случае их также будет не лишним проверить по панорамам.</li>
                <li>Адрес может быть написан в названии видео, описании к видео, либо в текстовом описании ДТП из СМИ.</li>
                <li>Иногда в тексте написан километр трассы, на котором произошла авария. Например, “На 239 километре трассы М-8 автомобиль Lada совершил выезд на встречную полосу...”. В этом случае надо найти этот километр на карте, и определить точное место с помощью визуальных ориентиров.</li>
                <li>Иногда бывает, что в тексте указан только город без указания улицы, например “ДТП произошло в Ульяновске”. В этом случае также надо попытаться разглядеть на видео какие-то ориентиры(названия учреждений, магазинов, указатели улиц на знаках и столбах и т.п.), и по ним найти точное место. Если никаких названий не видно, то можно ориентироваться по таким объектам как большой сквер, железная дорога, нетипичная геометрия улицы и т.п. Можно визуально поискать эти объекты на карте города, и найти так точное место ДТП.</li>
                <li>Иногда бывает, что описание ДТП очень скудное и неизвестен даже город или примерный километр трассы. В этом случае надо искать на видео указатели с наименованием улиц и населенных пунктов и пытаться по ним вычислить местоположение.</li>
                <li>Также можете посмотреть <a href="/geo/instruction">полную версию инструкции с видео</a>.</li>
            </ol>
            <PrimaryButton @click="instructionShow = false" class="mt-3 normal-case">Скрыть инструкцию</PrimaryButton>
        </div>
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
