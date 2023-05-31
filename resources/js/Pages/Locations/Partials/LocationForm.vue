<script setup>
import {useForm} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import YandexMap from "@/Pages/Locations/Partials/YandexMap.vue";
</script>

<script>
export default {
    props: ['accident'],
    data() {
        return {
            coords: '',
            form: useForm({
                lat: '0.0',
                long: '0.0',
                accident_id: '',
                approximately: false,
                note: null,
                comment: null,
            }),
        }
    },
    watch: {
        coords: function (val) {
            // TODO parse links and add coords validation
            this.form.lat = val.split(",")[0];
            this.form.long = val.split(",")[1];
        },
    },
    methods: {
        setLocationCoords(locationCoords) {
            this.$data.coords = locationCoords[0] + ',' + locationCoords[1];
            this.form.lat = locationCoords[0];
            this.form.long = locationCoords[1];
        },
        submit(note) {
            this.form.note = note;
            this.form.accident_id = this.$page.props.accident.id;
            this.coords = '';
            this.form.post(route('locations.store'));
            this.form.comment = '';
        },
    },
}
</script>

<template>
    <div class="border-l-0 lg:border-l border-gray-200 pl-3 lg:w-5/12 flex flex-col space-y-3">
        <h3 class="text-lg">Укажите геолокацию ({{ accident.lat }}, {{ accident.long }})</h3>
        <p class="text-sm">
            Укажите ссылку на Google/Яндекс панораму, координаты одной строкой через запятую, либо вручную
            поставьте точку на карте:
        </p>
        <input class="border-gray-400 rounded text-sm py-1"
               id="yandexCoordsInput"
               v-model="coords"
               type="text"
               placeholder="Ссылка на Google/Яндекс панораму, либо координаты одной строкой через запятую">

        <YandexMap @sendLocationCoords="setLocationCoords"
                   :coords="[accident.lat ?? 55.752246, accident.long ?? 37.62222]" :accidentId="accident.id">
        </YandexMap>

        <p class="text-sm text-gray-500">
            Может быть удобнее искать не на мини-карте, а открыть карту в отдельном окне.
        </p>
        <form @submit.prevent="submit()">
            <div class="flex justify-between">
                <div class="flex items-center">
                    <span>Широта:</span>
                    <input v-model="form.lat" class="border-0 text-center w-full" type="text" disabled/>
                </div>
                <div class="flex items-center">
                    <span>Долгота:</span>
                    <input v-model="form.long" class="border-0 text-center w-full" type="text" disabled/>
                </div>
            </div>

            <PrimaryButton id="locationSubmitButton" type="submit" class="!bg-blue-500 hover:!bg-blue-600 mb-3"
                           :class="{'!bg-blue-400 hover:!bg-blue-400 cursor-not-allowed': ! (form.long > 0)}"
                           :disabled="form.processing || ! (form.long > 0)">
                Отправить геолокацию
            </PrimaryButton>
            <br>
            <div v-if="accident.difficulty === 0">
                <input v-model="form.approximately" type="checkbox" name="approximately" id="approximately"/>
                <label for="approximately" class="ml-3">Геолокацию удалось определить только примерно</label>
            </div>
        </form>

        <hr class="my-5">

        <button @click="submit('undetected')"
                class="bg-[#007bff] hover:bg-[#0069d9] text-white rounded text-sm px-4 py-2">
            Не получилось определить геолокацию этого ДТП даже с точностью до нескольких километров
        </button>
        <button @click="submit('skipped')"
                class="bg-[#007bff] hover:bg-[#0069d9] text-white rounded text-sm px-4 py-2">
            Не хочу определять геолокацию этого ДТП, показать следующее
        </button>
        <button @click="submit('problems')"
                class="bg-[#007bff] hover:bg-[#0069d9] text-white rounded text-sm px-4 py-2">
            Технические проблемы с видео, либо видео не имеет отношения к ДТП (конфликты, приколы и т.п.)
        </button>

        <hr class="my-5">

        <h3 class="text-lg">Оставить комментарий</h3>
        <textarea v-model="form.comment" name="comment" id="comment" cols="30" rows="3"
                  placeholder="Ваш комментарий насчёт геолокации этого ДТП"></textarea>
        <button @click="submit()" class="bg-[#007bff] hover:bg-[#0069d9] text-white rounded px-4 py-2">
            Оставить комментарий
        </button>
        <div class="mt-6 leading-5 text-xs text-gray-500">
            <p>Ваш текущий ретинг: {{ $page.props.auth.user.rating }}</p>
            <div>
                Сейчас вы проставляете {{ accident.difficulty ? 'сложные ' : 'обычные' }} геолокации.
                <p v-if="$page.props.auth.user.rating < $page.props.complicatedAllowedRating">Когда ваш рейтинг
                    достигнет {{ $page.props.complicatedAllowedRating }}, вам откроется возможность проставлять сложные
                    геолокации.</p>
                <p v-else>Перейти к проставлению
                    <Link v-if="accident.difficulty" class="underline" :href="route('location.create')">обычных
                        геолокаций
                    </Link>
                    <Link v-else class="underline" :href="route('location.create', ['complicated'])">сложных
                        геолокаций
                    </Link>
                </p>
            </div>
        </div>
    </div>
</template>
