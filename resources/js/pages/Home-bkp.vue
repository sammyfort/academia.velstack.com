<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Layout from '@/layouts/GuestLayout.vue';
import HeroSection from '@/components/home/HeroSection.vue';
import TrustedBusinessesSection from '@/components/home/TrustedBusinessesSection.vue';
import Purpose from '@/components/home/Purpose.vue';
import FeaturesIntro from '@/components/home/FeaturesIntro.vue';
import Ratings from '@/components/home/Ratings.vue';
import CustomerEngagement from '@/components/home/CustomerEngagement.vue';
import EverythingYouNeed from '@/components/home/EverythingYouNeed.vue';
import BoostVisibility from '@/components/home/BoostVisibility.vue';
import UserInterfaceForBusinesses from '@/components/home/UserInterfaceForBusinesses.vue';
import ToolsForBusiness from '@/components/home/ToolsForBusiness.vue';
</script>

<template>
    <Head title="Home" />
    <Layout class="p-0 lg:p-0 md:p-0" style="padding: 0 !important;" :is-full-width="true">
        <div class="w-full">

            <HeroSection />

            <div class="container px-4 lg:px-8 mx-auto max-w-screen-xl text-gray-700 overflow-x-hidden">
                <TrustedBusinessesSection />
                <FeaturesIntro />
                <Purpose />
                <BoostVisibility />
                <UserInterfaceForBusinesses />
                <EverythingYouNeed />
                <ToolsForBusiness />
                <Ratings />
                <CustomerEngagement />
            </div>
        </div>
    </Layout>
</template>
