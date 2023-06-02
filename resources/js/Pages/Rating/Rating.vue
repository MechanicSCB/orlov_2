<script>
import RatingList from "./RatingList.vue";
export default {
    components: {RatingList},
    props: ['weekBestUsers', 'monthBestUsers', 'bestUsers'],
    computed:{
        currentMonth (){
            const months = ['январь', 'февраль', 'март', 'апрель', 'май', 'июнь', 'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь'];

            return months[new Date().getMonth()];
        }
    },
    mounted() {
        // console.log(this.weekBestUsers);
    },
}

</script>
<template>
    <Head title="Рейтинг пользователей">
        <meta typeof="description" content="Рейтинг пользователей" head-key="description">
    </Head>

    <div class="flex flex-col">
        <!-- Title -->
        <h1 class="text-3xl font-bold mb-4">Рейтинг пользователей</h1>

        <p class="text-sm">За каждую проставленную и принятую системой геолокацию пользователю прибавляется рейтинг(+10 за обычные
            геолокации, +30 за сложные). Также рейтинг меняется, если другие пользователи ставят “лайк”, либо "дизлайк"
            к вашим комментариям и публикациям на сайте. За каждый “лайк” дается +1 к рейтингу, за “дизлайк” дается -1 к
            рейтингу. Сразу после регистрации каждому пользователю дается базовый рейтинг равный 10. Рейтинг
            пользователей нужен, чтобы добавить в процесс игровой элемент и быстрее заполнить нашу базу данных
            геолокаций. </p>

        <div class="mt-10 flex w-full flex-col md:flex-row md:space-x-12 space-y-8 md:space-y-0">
            <RatingList :users="weekBestUsers" rating-period="weekRating" column-title="За эту неделю"></RatingList>
            <RatingList :users="monthBestUsers" rating-period="monthRating" :column-title="'За '+ currentMonth"></RatingList>
            <RatingList :users="bestUsers" rating-period="rating" column-title="За всё время"></RatingList>
        </div>
    </div>
</template>
