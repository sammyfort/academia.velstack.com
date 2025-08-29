<script setup lang="ts">
import Layout from '@/layouts/Layout.vue';
import { Head } from '@inertiajs/vue3';
import ServiceCardV1 from '@/components/Services/ServiceCardV1.vue';
import { PaginatedDataI, RegionI, ServiceCategoryI, ServiceI } from '@/types';
import ServicesFilter from '@/components/Services/ServicesFilter.vue';
import { useScrollPagination } from '@/lib/useScrollPagination';
import { chunkArray } from '@/lib/helpers';
import { onMounted } from 'vue';
import AdvertisedServicesH from '@/components/Services/AdvertisedServicesH.vue';
import { Hammer, Star } from 'lucide-vue-next';
import AdvertisedServicesV from '@/components/Services/AdvertisedServicesV.vue';
import BackToTop from '@/components/BackToTop.vue';
import ServiceCardV1Skeleton from '@/components/skeletons/ServiceCardV1Skeleton.vue';


const props = defineProps<{
    servicesData: PaginatedDataI<ServiceI>,
    categories: ServiceCategoryI[],
    regions: RegionI[];
}>()

const {
    items: services,
    loading: loadingServices,
    nextPage,
} = useScrollPagination<ServiceI>({
    initialData: props.servicesData.data as ServiceI[],
    nextPageUrl: props.servicesData.next_page_url,
    extractResponseData: (page) => page.props.servicesData,
    preserveKeys: ['servicesData'],
});

onMounted(() => {
    nextPage.value = props.servicesData.next_page_url;
    services.value = props.servicesData.data as ServiceI[];
});

</script>

<template>
    <Head title="Service Providers"/>
    <Layout>
        <div class="w-full">
            <div class="w-full my-10 text-center">
                <div class="text-fade text-2xl font-semibold">Browse Service Providers</div>
            </div>
            <div class="relative grid grid-cols-5 w-full gap-15">
                <div class=" lg:block col-span-5 lg:col-span-1">
                    <div class="sticky top-40">
                        <ServicesFilter
                            :categories="categories"
                            :regions="regions"
                        />
                    </div>
                </div>
                <div class="col-span-5 lg:col-span-3 text-gray-900 antialiased">
                    <div class="grid items-stretch gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3">
                        <template v-for="(servicesChunk, chunkIndex) in chunkArray<ServiceI>(services, 15)" :key="`chunk-${chunkIndex}`">
                            <ServiceCardV1
                                v-for="service in servicesChunk"
                                :key="service.id"
                                :service="service"
                            />
                            <AdvertisedServicesH
                                v-show="servicesChunk.length > 14"
                                container-class="md:col-span-3 lg:col-span-3 xl:col-span-4 sm:col-span-2 lg:hidden mt-4"
                            >
                                <div class="flex items-center gap-2 lg:text-2xl text-lg font-bold">
                                    <Hammer :size="25" class="text-primary"/>
                                    <span>Trending Service Providers</span>
                                    <Star :size="25" class="text-primary"/>
                                </div>
                            </AdvertisedServicesH>
                        </template>
                        <ServiceCardV1Skeleton v-show="loadingServices" v-for="sk in [1, 2, 3, 4]" :key="sk" class="shadow-2xl" />
                    </div>
                    <div v-if="!services.length" class="text-center mt-10 text-xl font-bold text-fade">
                        No results found, broaden your search...
                    </div>
                </div>
                <div class="hidden lg:block lg:col-span-1">
                    <div class="sticky top-40">
                        <div class="mb-4 text-lg font-semibold text-fade flex gap-2 items-center">
                            Trending Service Providers
                            <Hammer :size="22" class="text-primary"/>
                        </div>
                        <AdvertisedServicesV items-class="h-screen" class=""/>
                    </div>
                </div>
            </div>
        </div>
        <BackToTop />
    </Layout>
</template>

<style scoped>

</style>
