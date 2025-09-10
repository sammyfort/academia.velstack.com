<script setup lang="ts">
import Layout from '@/layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';
import { chunkArray } from '@/lib/helpers';
import { Store } from 'lucide-vue-next';
import BackToTop from '@/components/BackToTop.vue';
import ProductsFilter from '@/components/products/ProductsFilter.vue';
import ProductCardV1 from '@/components/products/ProductCardV1.vue';
import { PaginatedDataI, ProductCategoryI, ProductI, RegionI } from '@/types';
import { useScrollPagination } from '@/inertia-essentials/scroll-paginate';
import { onMounted } from 'vue';
import AdvertisedProductsH from '@/components/products/AdvertisedProductsH.vue';
import AdvertisedProductsV from '@/components/products/AdvertisedProductsV.vue';
import ProductCardSkeleton from '@/components/skeletons/ProductCardSkeleton.vue';


const props = defineProps<{
    productsData: PaginatedDataI<ProductI>
    categories: ProductCategoryI[]
    regions: RegionI[]
    highestPrice: number
}>()

const {
    items: products,
    loading: loadingProducts,
    nextPage,
} = useScrollPagination<ProductI>({
    initialData: props.productsData.data,
    nextPageUrl: props.productsData.next_page_url,
    resourceKey: "productsData",
    preserveKeys: ['productsData'],
});

onMounted(() => {
    nextPage.value = props.productsData.next_page_url;
    products.value = props.productsData.data as ProductI[];
});

</script>

<template>
    <Head title="Products" />
    <Layout>
        <div class="w-full">
            <div class="w-full my-10 text-center">
                <div class="text-fade text-2xl font-semibold">Shop</div>
            </div>
            <div class="relative grid grid-cols-5 w-full gap-15">
                <div class=" lg:block col-span-5 lg:col-span-1">
                    <div class="sticky top-40">
                        <ProductsFilter
                            :regions="regions"
                            :categories="categories"
                            :highestPrice="highestPrice"
                        />
                    </div>
                </div>
                <div class="col-span-5 lg:col-span-3 text-gray-900 antialiased">
                    <div class="grid items-stretch gap-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                        <template v-for="(productsChunk, chunkIndex) in chunkArray<ProductI>(products as ProductI[], 15)" :key="`chunk-${chunkIndex}`">
                            <ProductCardV1
                                v-for="product in productsChunk"
                                :key="`product-${product.id}`"
                                :product="product"
                            />
                            <AdvertisedProductsH
                                v-show="productsChunk.length > 14"
                                container-class="md:col-span-3 lg:col-span-3 xl:col-span-4 sm:col-span-2 lg:hidden mt-4"
                            >
                                <div class="flex items-center gap-2 lg:text-2xl text-lg font-bold">
                                    <span>Popular Sellers</span>
                                </div>
                            </AdvertisedProductsH>
                        </template>
                        <ProductCardSkeleton v-show="loadingProducts" v-for="sk in [1, 2, 3, 4]" :key="sk" class="shadow-2xl" />
                    </div>
                    <div v-if="!products.length" class="text-center mt-10 text-xl font-bold text-fade">
                        No results found, broaden your search...
                    </div>
                </div>
                <div class="hidden lg:block lg:col-span-1">
                    <div class="sticky top-40">
                        <div class="mb-4 text-lg font-semibold text-fade flex gap-2 items-center">
                            Popular Sellers
                            <Store :size="22" class="text-primary"/>
                        </div>
                        <AdvertisedProductsV items-class="h-screen" class=""/>
                    </div>
                </div>
            </div>
        </div>
        <BackToTop />
    </Layout>
</template>

<style scoped>

</style>
