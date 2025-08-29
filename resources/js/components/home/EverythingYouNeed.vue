<script setup lang="ts">

import AdvertisedSignboardV from '@/components/signboard/AdvertisedSignboardV.vue';
import { router, usePage } from '@inertiajs/vue3';

const page = usePage()
const user = page.props.auth.user
</script>

<template>
    <div class="sm:flex items-center sm:gap-x-8 mt-36">
        <div data-aos="fade-right" class="sm:w-2/3 relative aos-init aos-animate">
            <div class="bg-primary rounded-full absolute w-12 h-12 z-0 -left-4 -top-3 animate-pulse"></div>
            <h1 class="font-semibold text-2xl relative z-50 text-secondary lg:pr-10">
                Everything you need to showcase your business, <span class="text-primary">right from your signboard</span>
            </h1>
            <p class="py-5 lg:pr-32">
                Signboard helps local businesses in Ghana take their visibility online by letting them upload, manage, and promote their physical signboards digitally — searchable by location, category, and reviews.
            </p>
            <button
                v-show="!user"
                @click="router.visit(route('login'))"
                data-aos="flip-up"
                class="px-5 py-3 border border-primary text-primary font-medium my-14 btn-zoom bg-secondary rounded-full aos-init aos-animate">
                Get Started
            </button>
        </div>

        <div data-aos="fade-left" class="sm:w-1/3 relative mt-10 sm:mt-0 aos-init aos-animate">
            <AdvertisedSignboardV items-class="h-[550px]"/>
        </div>
    </div>
</template>

<style scoped>

</style>
