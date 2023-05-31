<script setup>
import {router, useForm} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Multiselect from 'vue-multiselect'
</script>

<script>
export default {
    data() {
        return {
            isRegionsSelectFormShowed: false,
            regionForm:useForm({
                favoriteRegions: router.page.props.auth.user.favorite_regions ?? null,
            }),
        }
    },
    methods: {
        submitFavoriteRegion(clear) {
            if(clear){
                this.regionForm.favoriteRegions = [];
            }

            this.isRegionsSelectFormShowed = false;
            this.regionForm.post(route('regions.chose-favorite'));
        },
        addTag (newTag) {
            const tag = {
                name: newTag,
                code: newTag.substring(0, 2) + Math.floor((Math.random() * 10000000))
            }
            this.options.push(tag)
            this.value.push(tag)
        }
    },
}
</script>

<template>
    <!--<div>{{ router.page.props.auth.user }}</div>-->
    <p class="mb-4">
        Вам показываются{{ $page.props?.accident?.difficulty ? ' сложные' : '' }}  ДТП из
        <span @click="isRegionsSelectFormShowed = true">
            <span class="text-blue-700 hover:underline cursor-pointer" v-for="(favoriteRegion,index) in $page.props.auth.user.favorite_regions" :key="favoriteRegion.id">
                {{ index?', ':' ' }}{{ favoriteRegion.name.trim()}}
            </span>
            <span class="text-blue-700 hover:underline cursor-pointer" v-if="$page.props.auth.user?.favorite_regions?.length === 0">случайных городов</span>
        </span>
    </p>

    <div v-if="isRegionsSelectFormShowed" class="fixed left-0 top-0 z-40 bg-opacity-25 w-full h-full">
        <div @click="isRegionsSelectFormShowed = false" class="absolute bg-opacity-25 bg-black w-full h-full"></div>
        <form class="relative mx-auto mt-10 max-w-[90%] w-[500px] bg-white rounded py-4 px-3" @submit.prevent="submitFavoriteRegion()">
            <h2 class="text-xl">Выберите город</h2>
            <hr class="my-4">
            <div class="mb-4">
                Вы можете выбрать город, а мы постараемся показывать ДТП только из этого города.
            </div>
            <multiselect v-model="regionForm.favoriteRegions" tag-placeholder="Add this as new tag" placeholder="Выберите город из списка" label="name" track-by="id" :options="$page.props.regions" :multiple="true" :taggable="true" @tag="addTag"></multiselect>

            <div class="mt-4">
                <PrimaryButton class="!bg-blue-500 hover:!bg-blue-600 normal-case" :disabled="regionForm.processing">
                    Выбрать города
                </PrimaryButton>
                <SecondaryButton @click.prevent="submitFavoriteRegion('clear')" class="ml-3 normal-case" :disabled="regionForm.processing">
                    Случайный город
                </SecondaryButton>
            </div>
        </form>
    </div>
</template>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
