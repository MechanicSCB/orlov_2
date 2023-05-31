<script setup>
// import UserLocations from "./Components/UserLocations";
// import FavoriteRegionsSelection from "./Components/FavoriteRegionsSelection";

import AccidentInfo from "@/Pages/Locations/Partials/AccidentInfo.vue";
import LocationForm from "@/Pages/Locations/Partials/LocationForm.vue";

const props = defineProps({
    accident: Object,
    locations:Object,
})
</script>

<template>
    <Head :title="accident.id + ' - Проставление' + (accident.difficulty ? ' сложных' : '') + ' геолокаций'"/>

    <div class="flex flex-col space-y-2">
        <!-- Title -->
        <h1 :class="accident.location_status === 3 ? 'text-red-700' : ''" class="text-3xl mb-4 font-bold">
            Проставление{{ accident.difficulty ? ' сложных' : '' }} геолокаций ({{ accident.id }})
        </h1>

        <!-- Favorite Regions Selection -->
        <!--<FavoriteRegionsSelection></FavoriteRegionsSelection>-->

        <div class="flex w-full flex-col-reverse lg:flex-row">
            <!-- Accident info -->
            <AccidentInfo :accident="accident"></AccidentInfo>

            <!-- Location Submit Form -->
            <LocationForm :accident="accident"></LocationForm>
        </div>

        <!-- Locations added by auth user -->
        <!--<UserLocations :locations="locations"></UserLocations>-->

        <Link class="!mt-4 text-[#007bff] hover:text-[#0056b3] hover:underline"
              :href="route('user.locations.index')">Посмотреть все геолокации, которые вы уже проставили</Link>
    </div>
</template>

