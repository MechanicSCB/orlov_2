<script setup>
import {useForm} from "@inertiajs/vue3";
import {ref} from "vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import AirSummernote from "@/Pages/Posts/Partials/AirSummernote.vue";

let form = useForm({
    title: '',
    body: '',
    videos: [''],
    photos: [],
});

let submit = () => {
    // if (photoInput.value) {
    //     form.photos = photos;
    // }

    form.post(route('posts.store'));
};

function summernoteSubmit(data, id) {
    form[id] = data;
}

const photos = ref([]);
const photoInput = ref(null);

const selectNewPhoto = () => {
    photoInput.value.click();
};

const addPhoto = () => {
    const photo = photoInput.value.files[0];

    if (!photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        form.photos.push(photo);
        photos.value.push(e.target.result);
    };

    reader.readAsDataURL(photo);
};

const removePhoto = (index) => {
    index = form.photos.length - index - 1;
    form.photos.splice(index, 1);
    photos.value.splice(index, 1);
};

</script>

<template>
    <Head title="Написать пост"/>

    <div class="flex flex-col space-y-2">
        <!-- Title -->
        <h1 class="mb-3 text-3xl font-bold">Написать пост</h1>

        <form @submit.prevent="submit" class="w-full mt-8 flex flex-col md:flex-row space-x-0 md:space-x-8"
              @submitted="updateProfileInformation">
            <div class="w-full md:w-7/12">
                <div class="mb-6">
                    <label class="block mb-2 text-sm" for="title">Название поста</label>

                    <input v-model="form.title" class="border border-gray-400 p-2 w-full rounded" type="text"
                           name="title"
                           id="title"
                           placeholder="Введите название поста"
                           required/>

                    <InputError :message="form.errors.title" class="h-0"/>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 text-sm" for="body">Текст поста</label>

                    <AirSummernote v-on:submitted="summernoteSubmit" classes="rounded"
                                   summernoteId="body" id="body"/>

                    <InputError :message="form.errors.body" class="h-0"/>
                </div>

                <label class="block mb-2 text-sm" for="videos_0">Ссылки на видео</label>

                <div v-for="(video,index) in form.videos" class="flex mb-2">
                    <input v-model="form.videos[index]" type="text" :id="'videos_' + index" name="videos[]"
                           placeholder="Введите ссылку на видео YouTube или VK"
                           class="border border-gray-400 p-2 rounded flex-1">
                    <button type="button" v-if="index > 0" class="ml-3 border border-gray-400 rounded px-2 py-1"
                            @click="form.videos.splice(index, 1)"><i class="bi bi-x"></i></button>
                </div>

                <PrimaryButton type="button" @click="form.videos.push('')" class="!bg-[#6c757d] normal-case">
                    <i class="bi bi-plus"></i> Добавить видео
                </PrimaryButton>

                <PrimaryButton type="submit"
                           class="mt-12 !block !bg-blue-500 hover:!bg-blue-600 !text-lg normal-case py-2 font-normal !mb-7"
                           :disabled="form.processing">Опубликовать
                </PrimaryButton>
            </div>
            <div class="w-full md:w-5/12 text-sm">
                <!-- Profile Photo File Input -->
                <input
                    ref="photoInput"
                    type="file"
                    class="hidden"
                    accept="image/*"
                    @change="addPhoto"
                >
                <label for="photo">Фотографии</label>
                <button @click.prevent="selectNewPhoto" type="button"
                        class="mt-2 w-full rounded bg-[#f4f4f4] border border-[#ced4da] py-3 mb-6">Загрузить фотографии
                </button>

                <!-- New Profile Photo Preview -->
                <div class="grid grid-cols-4 gap-4">
                    <div v-for="(photo, index) in photos.slice().reverse()"
                         class="relative w-full aspect-square text-right text-white bg-cover bg-no-repeat bg-center"
                    >
                        <img class="w-full h-full object-cover rounded" :src="photo" alt="">
                        <button class="absolute top-1 right-1 text-3xl font-bold opacity-50 hover:opacity-75" type="button" @click="removePhoto(index)">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>
<style>
.note-editing-area .note-editable {
    border-radius: 0.25rem;
}
</style>
