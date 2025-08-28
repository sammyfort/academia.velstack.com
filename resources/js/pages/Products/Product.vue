<script setup lang="ts">
import Layout from '@/layouts/Layout.vue';
import { Head } from '@inertiajs/vue3';
import { MediaI, ProductI } from '@/types';
import RateDialog from '@/components/RateDialog.vue';
import { watchOnce } from '@vueuse/core';
import { ref } from 'vue';
import { Carousel, type CarouselApi, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/ui/carousel';
import { Button } from '@/components/ui/button';
import { cediSign, whatsappChatLink } from '@/lib/helpers';
import StarRating from 'vue-star-rating';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import ReviewsList from '@/components/review/ReviewsList.vue';
import { Store } from 'lucide-vue-next';
import AdvertisedProductsH from '@/components/products/AdvertisedProductsH.vue';
import ShareToSocials from '@/components/ShareToSocials.vue';
import VideoEmbed from '@/components/VideoEmbed.vue';

type Props = {
    product: ProductI;
    media: MediaI[];
};

const props = defineProps<Props>();

const emblaMainApi = ref<CarouselApi>();
const emblaThumbnailApi = ref<CarouselApi>();
const selectedIndex = ref(0);

function onSelect() {
    if (!emblaMainApi.value || !emblaThumbnailApi.value) return;
    selectedIndex.value = emblaMainApi.value.selectedScrollSnap();
    emblaThumbnailApi.value.scrollTo(emblaMainApi.value.selectedScrollSnap());
}

function onThumbClick(index: number) {
    if (!emblaMainApi.value || !emblaThumbnailApi.value) return;
    emblaMainApi.value.scrollTo(index);
}

watchOnce(emblaMainApi, (emblaMainApi) => {
    if (!emblaMainApi) return;

    onSelect();
    emblaMainApi.on('select', onSelect);
    emblaMainApi.on('reInit', onSelect);
});

const wpChatLink = whatsappChatLink(
    props.product.whatsapp_mobile as string,
    `Hello, please I am inquiring about your product: ${props.product.name}\n\nProduct link:\n${route('products.show', props.product.id)}`
);

</script>

<template>
    <Head title="Product Details" />
    <Layout>
        <div class="mx-auto max-w-[1200px]">
            <div>Home > Products > {{ product.name }}</div>
            <div class="py-6">
                <div class="flex flex-col gap-6 md:gap-12 lg:flex-row">
                    <!-- Image Section -->
                    <div class="w-full lg:w-1/2">
                        <div class="grid gap-4">
                            <Carousel class="relative mx-auto max-w-lg" @init-api="(val) => (emblaMainApi = val)">
                                <CarouselContent class="flex">
                                    <CarouselItem v-for="(image, index) in media" :key="index">
                                        <img
                                            class="mx-auto h-auto max-h-[500px] w-full max-w-[100%] rounded-lg object-cover object-center lg:max-w-[500px]"
                                            :src="image.original_url"
                                            alt="Product gallery"
                                        />
                                    </CarouselItem>
                                </CarouselContent>
                                <CarouselPrevious class="hidden h-10 w-10 justify-center bg-gray-300 lg:flex" />
                                <CarouselNext class="hidden h-10 w-10 justify-center bg-gray-300 lg:flex" />
                            </Carousel>
                            <Carousel class="relative mx-auto w-full max-w-xs" @init-api="(val) => (emblaThumbnailApi = val)">
                                <CarouselContent class="ml-0 flex gap-1">
                                    <CarouselItem
                                        v-for="(image, index) in media"
                                        :key="index"
                                        class="basis-1/4 cursor-pointer pl-0"
                                        @click="onThumbClick(index)"
                                    >
                                        <div>
                                            <img
                                                :class="index === selectedIndex ? '' : 'opacity-50'"
                                                :src="image.original_url"
                                                class="max-h-30 max-w-full transform cursor-pointer rounded-lg object-cover object-center transition-all duration-700"
                                                :alt="`Gallery Image ${index}`"
                                            />
                                        </div>
                                    </CarouselItem>
                                </CarouselContent>
                            </Carousel>
                        </div>
                    </div>
                    <div class="flex w-full flex-col gap-2 lg:w-1/2">
                        <div class="border-gray-line border-b">
                            <h1 class="mb-4 text-3xl font-bold">{{ product.name }}</h1>
                            <div class="flex items-center">
                                <StarRating
                                    :star-size="15"
                                    :show-rating="false"
                                    :rating="product.total_average_rating"
                                    read-only
                                    active-color="#009689"
                                    :padding="3"
                                    :key="`rating-card-${product.id}`"
                                    :increment="0.01"
                                />
                                <span class="ml-2">({{ product.reviews_count }} Reviews)</span>
                                <RateDialog :ratable="product" ratable_type="product">
                                    <a href="#" class="ml-4 font-semibold text-primary">Write a review</a>
                                </RateDialog>
                            </div>
                            <div class="border-gray-line border-b py-4">
                                <p class="mb-2">
                                    Negotiation: <strong>{{ product.is_negotiable ? 'Negotiable' : 'Non-negotiable' }}</strong>
                                </p>
                                <p class="mb-2">
                                    Mobile number: <strong>{{ product.first_mobile }}</strong>
                                </p>
                                <p class="mb-2" v-if="product.second_mobile">
                                    Second mobile number: <strong>{{ product.second_mobile }}</strong>
                                </p>
                                <p class="mb-2">
                                    Region: <strong>{{ product.region.name }}</strong>
                                </p>
                                <p class="mb-2">
                                    Town: <strong>{{ product.town }}</strong>
                                </p>
                                <p class="mb-2" v-if="product.website">
                                    Company website:
                                    <a :href="product.website" target="_blank" class="underline hover:text-primary"><strong>visit website</strong></a>
                                </p>
                            </div>
                            <div class="py-4 text-2xl font-semibold">
                                <span class="text-sm">{{ cediSign() }}</span>
                                <span>{{ product.price }}</span>
                            </div>
                            <a v-if="wpChatLink" :href="wpChatLink" target="_blank">
                                <Button
                                    class="mb-4 rounded-full border border-transparent bg-primary px-4 py-2 font-semibold text-white transition-all hover:border-primary hover:bg-transparent hover:text-primary"
                                >
                                    Chat on WhatsApp
                                </Button>
                            </a>
                        </div>
                        <ShareToSocials />
                        <div>
                            <h3 class="mb-2 text-lg font-semibold">Product Description</h3>
                            <p v-html="product.short_description"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container mx-auto">
                <div class="py-12">
                    <Tabs default-value="description" class="w-full">
                        <div class="flex">
                            <TabsList class="grid grid-cols-3 gap-4 bg-transparent">
                                <TabsTrigger
                                    class="rounded-none hover:border-b-2 hover:border-b-primary data-[state=active]:border-b-2 data-[state=active]:border-b-primary data-[state=active]:bg-transparent data-[state=active]:text-primary data-[state=active]:shadow-none"
                                    value="description"
                                >
                                    Description
                                </TabsTrigger>
                                <TabsTrigger
                                    class="rounded-none hover:border-b-2 hover:border-b-primary data-[state=active]:border-b-2 data-[state=active]:border-b-primary data-[state=active]:bg-transparent data-[state=active]:text-primary data-[state=active]:shadow-none"
                                    value="additional-information"
                                >
                                    Additional information
                                </TabsTrigger>
                                <TabsTrigger
                                    class="rounded-none hover:border-b-2 hover:border-b-primary data-[state=active]:border-b-2 data-[state=active]:border-b-primary data-[state=active]:bg-transparent data-[state=active]:text-primary data-[state=active]:shadow-none"
                                    value="reviews"
                                >
                                    Reviews ({{ product.reviews_count }})
                                </TabsTrigger>
                            </TabsList>
                        </div>

                        <TabsContent class="border-s border-secondary/20 ps-3" value="description">
                            <div class="py-6" v-html="product.description"></div>
                        </TabsContent>
                        <TabsContent class="border-s border-secondary/20 ps-3" value="additional-information">
                            <div class="py-6">
                                <p class="mb-2">
                                    Negotiation: <strong>{{ product.is_negotiable ? 'Negotiable' : 'Non-negotiable' }}</strong>
                                </p>
                                <p class="mb-2">
                                    Mobile number: <strong>{{ product.first_mobile }}</strong>
                                </p>
                                <p class="mb-2" v-if="product.second_mobile">
                                    Second mobile number: <strong>{{ product.second_mobile }}</strong>
                                </p>
                                <p class="mb-2">
                                    Region: <strong>{{ product.region.name }}</strong>
                                </p>
                                <p class="mb-2">
                                    Town: <strong>{{ product.town }}</strong>
                                </p>
                                <p class="mb-2" v-if="product.website">
                                    Company website:
                                    <a :href="product.website" target="_blank" class="underline hover:text-primary"><strong>visit website</strong></a>
                                </p>
                                <div v-if="product.video_link" class="my-5 w-full">
                                    <div class="mb-3 text-lg font-semibold">Video Portfolio</div>
                                    <VideoEmbed :url="product.video_link" />
                                </div>
                                <p class="flex items-center gap-4">
                                    <Avatar class="h-13 w-13 border">
                                        <AvatarImage :src="product.user.avatar?.original_url ?? ''" />
                                        <AvatarFallback class="bg-gray-200 uppercase">{{ product.user.initials }}</AvatarFallback>
                                    </Avatar>
                                    <span>{{ product.user.fullname }}</span>
                                </p>
                            </div>
                        </TabsContent>
                        <TabsContent class="ps-3" value="reviews">
                            <div class="py-6">
                                <div class="mb-4 flex items-end justify-between">
                                    <h3 class="text-lg font-semibold">Customer Reviews</h3>
                                    <RateDialog :ratable="product" ratable_type="product">
                                        <Button>Write a review</Button>
                                    </RateDialog>
                                </div>
                                <div v-if="!product.reviews.length" class="text-xl">No reviews</div>
                                <ReviewsList v-else :ratable="product" :reviews="product.reviews" />
                            </div>
                        </TabsContent>
                    </Tabs>
                </div>
            </div>
            <div class="mt-10 hidden overflow-hidden lg:block">
                <div class="text-fade mb-4 flex items-center gap-2 text-lg font-semibold">
                    Popular Sellers
                    <Store :size="22" class="text-primary" />
                </div>
                <AdvertisedProductsH items-class="h-screen" class="" />
            </div>
        </div>
    </Layout>
</template>

<style scoped></style>
