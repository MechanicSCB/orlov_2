<script>
export default {
    layout: '',
}
</script>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import Checkbox from "@/Components/Checkbox.vue";

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />

    <div class="min-h-screen flex flex-col sm:justify-center items-center">
        <div class="w-full max-w-[400px] mt-6 px-6 py-4 overflow-hidden">
            <Link href="#">« Назад</Link>

            <h2 class="text-3xl">Вход в профиль</h2>

            <div class="my-4 flex justify-between">
                <a href="socialite/vkontakte">
                    <PrimaryButton class="!bg-[#4a76a8] h-full normal-case !text-[15px]">
                        <svg class="w-4 mr-3" xmlns="http://www.w3.org/2000/svg" height="19" viewBox="0 0 34 19"
                             width="34">
                            <path
                                d="m31.5584982 1.28655527c.2223912-.7416123.0000002-1.28655527-1.0584372-1.28655527h-3.5c-.8899347 0-1.3002074.47075088-1.5227219.98984863 0 0-1.7798696 4.33838569-4.3012897 7.15645072-.8157426.81574267-1.1865797 1.07529155-1.631547 1.07529155-.2224529 0-.5561938-.25954888-.5561938-1.00109946v-6.93393617c0-.88993476-.2465735-1.28655527-.9882476-1.28655527h-5.5c-.556132 0-.8905943.41303906-.8905943.80449184 0 .84364187 1.2606484 1.03819551 1.3905943 3.41135488v5.15412836c0 1.13002492-.2040763 1.33490212-.6490437 1.33490212-1.1865179 0-4.07272614-4.35776695-5.78451424-9.34419156-.33546923-.96918821-.67193969-1.36068564-1.56644202-1.36068564h-3.5c-1 0-1.20001221.47075088-1.20001221.98984863 0 .92703081 1.18657969 5.52496537 5.52490365 11.60606287 2.8921954 4.1529054 6.96710872 6.4040885 10.67510852 6.4040885 2.2247752 0 2.5-.5 2.5-1.3612483v-3.1387517c0-1 .2107621-1.1995723.9152784-1.1995723.5190977 0 1.4090325.2595489 3.4855469 2.2618712 2.3730977 2.3730977 2.7643343 3.4377011 4.0991747 3.4377011h3.5c1 0 1.5-.5 1.2115578-1.486722-.3156423-.9834253-1.4486683-2.4102444-2.9520878-4.1016239-.8158044-.9640651-2.0394184-2.0022606-2.4102555-2.5214818-.5190977-.6673585-.3707752-.96406514 0-1.55729326 0 0 4.2642554-6.00696708 4.7092227-8.04632377z"
                                fill="#fff" fill-rule="evenodd"></path>
                        </svg>
                        vk.com
                    </PrimaryButton>
                </a>
                <a href="socialite/odnoklassniki">
                    <PrimaryButton class="!bg-[#ee8208] h-full normal-case !text-[15px]">
                        <svg class="w-5 mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024">
                            <path
                                d="M592.2 686.1c40.4-9.2 79.3-25.2 115.101-47.7 27.1-17.1 35.3-52.899 18.199-80.1-17.1-27.2-52.899-35.4-80.1-18.3-81.2 51.1-185.7 51-266.9 0-27.199-17.1-63-8.9-80.1 18.3-17.1 27.1-8.9 63 18.2 80.1 35.8 22.5 74.7 38.5 115.1 47.7L321 796.9c-22.699 22.699-22.699 59.399 0 82.1 11.4 11.3 26.2 17 41 17 14.9 0 29.7-5.7 41.101-17L512 770.1 620.9 879c22.7 22.7 59.4 22.7 82.1 0 22.7-22.7 22.7-59.5 0-82.1L592.2 686.1M512 244.2c45.301 0 82.101 36.8 82.101 82.1 0 45.2-36.8 82-82.101 82-45.199 0-82.1-36.8-82.1-82 0-45.3 36.901-82.1 82.1-82.1zm0 280.2c109.301 0 198.2-88.9 198.2-198.101C710.2 216.9 621.301 128 512 128c-109.3 0-198.199 88.9-198.199 198.2 0 109.3 88.899 198.2 198.199 198.2z"
                                fill="#fff"></path>
                        </svg>
                        ok.ru
                    </PrimaryButton>
                </a>
                <a href="socialite/yandex">
                    <PrimaryButton class="!bg-[#3b5998] h-full">
                        <svg class="w-5 fill-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path
                                d="M16.403,9H14V7c0-1.032,0.084-1.682,1.563-1.682h0.868c0.552,0,1-0.448,1-1V3.064c0-0.523-0.401-0.97-0.923-1.005 C15.904,2.018,15.299,1.999,14.693,2C11.98,2,10,3.657,10,6.699V9H8c-0.552,0-1,0.448-1,1v2c0,0.552,0.448,1,1,1l2-0.001V21 c0,0.552,0.448,1,1,1h2c0.552,0,1-0.448,1-1v-8.003l2.174-0.001c0.508,0,0.935-0.381,0.993-0.886l0.229-1.996 C17.465,9.521,17.001,9,16.403,9z"></path>
                        </svg>
                    </PrimaryButton>
                </a>
                <a href="socialite/google">
                    <PrimaryButton class="!bg-[#4285f4] h-full !text-[15px]">
                        <i class="bi bi-google"></i>
                    </PrimaryButton>
                </a>
            </div>

            <div class="flex items-center mb-4">
                <hr class="w-full">
                <span class="px-2">или</span>
                <hr class="w-full">
            </div>

            <InputError class="mb-4 pl-10 pr-24 py-6 bg-[#f8d7da] border border-[#f5c6cb] !text-[#721c24] rounded"/>

            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="email" value="Email" />
                    <TextInput
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="mt-1 block w-full"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password" value="Пароль" />
                    <TextInput
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full"
                        required
                        autocomplete="current-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="block mt-4">
                    <label class="flex items-center">
                        <Checkbox v-model:checked="form.remember" name="remember" />
                        <span class="ml-2 text-sm text-gray-600">Запомнить меня</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <Link v-if="canResetPassword" :href="route('password.request')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Забыли пароль?
                    </Link>

                    <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Войти
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>
