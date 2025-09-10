<script setup lang="ts">
import Layout from '@/layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';
import SignboardCardV1 from '@/components/signboard/SignboardCardV1.vue';
import { PaginatedDataI, RegionI, SignboardCategoryI, SignboardI } from '@/types';
import AdvertisedSignboardV from '@/components/signboard/AdvertisedSignboardV.vue';
import { onMounted } from 'vue';
import BackToTop from '@/components/BackToTop.vue';
import { Star, Factory } from 'lucide-vue-next';
import AdvertisedSignboardsH from '@/components/signboard/AdvertisedSignboardsH.vue';
import { chunkArray } from '@/lib/helpers';
import { useScrollPagination } from '@/lib/useScrollPagination';
import ServiceCardV1Skeleton from '@/components/skeletons/ServiceCardV1Skeleton.vue';
import SignboardsFilter from '@/components/signboard/SignboardsFilter.vue';

type Props = {
    signboardsData: PaginatedDataI<SignboardI>;
    categories: SignboardCategoryI[];
    regions: RegionI[];
};

const props = defineProps<Props>();

const {
    items: signboards,
    loading: loadingSignboards,
    nextPage,
} = useScrollPagination<SignboardI>({
    initialData: props.signboardsData.data as SignboardI[],
    nextPageUrl: props.signboardsData.next_page_url,
    extractResponseData: (page) => page.props.signboardsData,
    preserveKeys: ['signboardsData'],
});

onMounted(() => {
    nextPage.value = props.signboardsData.next_page_url;
    signboards.value = props.signboardsData.data as SignboardI[];
});
</script>

<template>
    <Head title="Signboards" />
    <Layout>
        <div class="w-full">
            <div class="w-full my-10 text-center">
                <div class="text-fade text-2xl font-semibold">Browse Signboards</div>
            </div>
            <div class="relative grid grid-cols-5 w-full gap-15">
                <div class=" lg:block col-span-5 lg:col-span-1">
                    <div class="sticky top-40">
                        <SignboardsFilter
                            :categories="categories"
                            :regions="regions"
                        />
                    </div>
                </div>
                <div class="col-span-5 lg:col-span-3 text-gray-900 antialiased">
                    <div class="grid items-stretch gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3">
                        <template v-for="(signboardChunk, chunkIndex) in chunkArray<SignboardI>(signboards, 15)" :key="`chunk-${chunkIndex}`">
                            <SignboardCardV1
                                v-for="signboard in signboardChunk"
                                :key="signboard.id"
                                :signboard="signboard"
                            />
                            <AdvertisedSignboardsH
                                v-show="signboardChunk.length > 14"
                                container-class="md:col-span-3 lg:col-span-3 xl:col-span-4 sm:col-span-2 lg:hidden mt-4"
                            >
                                <div class="flex items-center gap-2 lg:text-2xl text-lg font-bold">
                                    <Factory :size="25" class="text-primary"/>
                                    <span>Top Signboards</span>
                                    <Star :size="25" class="text-primary"/>
                                </div>
                            </AdvertisedSignboardsH>
                        </template>
                        <ServiceCardV1Skeleton v-show="loadingSignboards" v-for="sk in [1, 2, 3, 4]" :key="sk" class="shadow-2xl" />
                    </div>
                    <div v-if="!signboards.length" class="text-center mt-10 text-xl font-bold text-fade">
                        No results found, broaden your search...
                    </div>
                </div>
                <div class="hidden lg:block lg:col-span-1">
                    <div class="sticky top-0">
                        <div class="mb-4 text-lg font-semibold text-fade flex gap-2 items-center">
                            Top Signboards
                            <Factory :size="22" class="text-primary"/>
                        </div>
                        <AdvertisedSignboardV items-class="h-screen" />
                    </div>
                </div>
            </div>
        </div>
        <BackToTop />
    </Layout>
    <BackToTop />
</template>

<style scoped></style>
