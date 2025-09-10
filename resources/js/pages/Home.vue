<script setup lang="ts">
import Layout from '@/layouts/GuestLayout.vue';
import HeroSection from '@/components/home/HeroSection.vue';
import InputSelect from '@/components/InputSelect.vue';
import { InputSelectOption } from '@/types';
import { useForm } from '@inertiajs/vue3';
import InputText from '@/components/InputText.vue';
import TrustedBusinessesSection from '@/components/home/TrustedBusinessesSection.vue';
import AdvertisedServicesH from '@/components/Services/AdvertisedServicesH.vue';
import { router } from '@inertiajs/vue3';
import AdvertisedJobsH from '@/components/jobs/AdvertisedJobsH.vue';
import { Button } from '@/components/ui/button';
import AdvertisedProductsH from '@/components/products/AdvertisedProductsH.vue';

const searchForm = useForm({
    directory: '',
    q: '',
})


</script>

<template>
  <Layout class="p-0 lg:p-0 md:p-0" style="padding: 0 !important;" :is-full-width="true">
        <div class="w-full">
            <HeroSection />
        </div>
  </Layout>
</template>
