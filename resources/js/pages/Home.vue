<script setup lang="ts">
import Layout from '@/layouts/Layout.vue';
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

const searchOptions: InputSelectOption[] = [
    {label: 'Service Providers', value: 'services'},
    {label: 'Signboards', value: 'signboards'},
    {label: 'Jobs', value: 'jobs'},
    {label: 'Products', value: 'products'},
]

function performSearch(){
    searchForm.get(route('home.search'))
}

</script>

<template>
  <Layout class="p-0 lg:p-0 md:p-0" style="padding: 0 !important;" :is-full-width="true">
        <div class="w-full">
            <HeroSection />

            <div class="flex flex-col">
                <div class="mx-auto max-w-[1200px]">
                    <div class="px-3 py-10">
                        <div class="text-2xl font-semibold mb-5 text-center">Browse directories</div>
                        <div class="flex justify-center items-end px-5 py-7 gap-7 flex-wrap">
                            <InputSelect
                                :options="searchOptions"
                                label="Directory"
                                :form="searchForm"
                                model="directory"
                                class="w-full md:w-md"
                            />
                            <InputText
                                label="Search For"
                                container-class="w-full md:w-md"
                                placeholder="Type what you're looking for..."
                                model="q"
                                :form="searchForm"
                            />
                            <div class="w-full md:w-auto">
                                <Button @click="performSearch" :processing="searchForm.processing" class="w-full md:w-auto md:px-10">Search</Button>
                            </div>
                        </div>
                    </div>

                    <div class="px-3 py-10">
                        <div class="text-2xl font-semibold mb-5 text-center">What are you looking for ?</div>
                        <div class="flex flex-wrap justify-center p-10 gap-7 text-center">
                            <div
                                @click="router.visit(route('services.index'))"
                                class="flex items-center transition-all ease-in-out duration-500 hover:bg-primary hover:text-secondary cursor-pointer justify-center font-bold rounded-[100%] h-[120px] w-[120px] p-10 bg-gray-300"
                            >
                                <span>Service Providers</span>
                            </div>
                            <div
                                @click="router.visit(route('signboards.index'))"
                                class="flex items-center transition-all ease-in-out duration-500 hover:bg-primary hover:text-secondary cursor-pointer justify-center font-bold rounded-[100%] h-[120px] w-[120px] p-10 bg-gray-300"
                            >
                                <span>Signboards</span>
                            </div>
                            <div
                                @click="router.visit(route('jobs.index'))"
                                class="flex items-center transition-all ease-in-out duration-500 hover:bg-primary hover:text-secondary cursor-pointer justify-center font-bold rounded-[100%] h-[120px] w-[120px] p-10 bg-gray-300">
                                <span>Jobs</span>
                            </div>
                            <div
                                @click="router.visit(route('products.index'))"
                                class="flex items-center transition-all ease-in-out duration-500 hover:bg-primary hover:text-secondary cursor-pointer justify-center font-bold rounded-[100%] h-[120px] w-[120px] p-10 bg-gray-300"
                            >
                                <span>Buy</span>
                            </div>
                        </div>
                    </div>
                    <div class="container px-4 lg:px-0 mx-auto max-w-screen-xl text-gray-700">
                        <TrustedBusinessesSection />
                    </div>
                    <div class="container px-4 lg:px-0 mx-auto max-w-screen-xl text-gray-700 mt-10 mb-10">
                        <h1 class="text-center mb-3 text-gray-400 text-2xl font-medium ">Top Service Providers</h1>
                        <AdvertisedServicesH />
                    </div>
                    <div class="container px-4 lg:px-0 mx-auto max-w-screen-xl text-gray-700 mt-10 mb-10">
                        <h1 class="text-center mb-3 text-gray-400 text-2xl font-medium ">Newly Posted Jobs</h1>
                        <AdvertisedJobsH />
                    </div>
                    <div class="container px-4 lg:px-0 mx-auto max-w-screen-xl text-gray-700 mt-10 mb-10">
                        <h1 class="text-center mb-3 text-gray-400 text-2xl font-medium ">Popular Sellers</h1>
                        <AdvertisedProductsH />
                    </div>
                </div>
            </div>

        </div>
  </Layout>
</template>
