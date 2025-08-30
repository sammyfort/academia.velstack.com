<script setup lang="ts">
import { computed } from 'vue';
import { Camera, Maximize2 } from 'lucide-vue-next';
import { randomString } from '@/lib/helpers';

interface Props {
    featuredUrl?: string | null;
    galleryUrls?: string[];
    title?: string;
    className?: string;
    forPublic?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    featuredUrl: null,
    galleryUrls: () => [],
    title: 'Image Gallery',
    className: ''
});

const featuredId = randomString()
const galleryId = randomString()

const allImages = computed(() => {
    const images = [];
    if (props.featuredUrl) {
        images.push({ url: props.featuredUrl, isFeatured: true });
    }
    if (props.galleryUrls) {
        images.push(...props.galleryUrls.map(url => ({ url, isFeatured: false })));
    }
    return images;
});

const hasImages = computed(() => allImages.value.length > 0);
const hasAdditionalPhotos = computed(() => props.galleryUrls && props.galleryUrls.length > 0);

</script>


<template>
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6" :class="className">
        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
            <Camera class="w-5 h-5 text-primary" />
            {{ title }}
        </h3>

        <div v-if="hasImages">
            <div v-if="featuredUrl" class="mb-8">
                <div class="relative group cursor-pointer">
                    <a :href="featuredUrl" class="glightbox cursor-pointer" :data-gallery="`gallery-${featuredId}`">
                        <img
                            :src="featuredUrl"
                            alt="Featured Image"
                            class="w-full h-80 object-contain bg-gray-50 rounded-2xl shadow-lg transition-transform duration-300 group-hover:scale-[1.02]"
                        />
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300 rounded-2xl"></div>
                        <div class="absolute top-4 left-4">
                            <span class="bg-gradient-to-r from-yellow-400 to-primary text-white px-3 py-1 rounded-full text-sm font-medium shadow-lg">
                                Featured
                            </span>
                        </div>
                        <button
                            class="absolute top-4 right-4 p-2 bg-white/20 backdrop-blur-sm rounded-full text-white hover:bg-white/30 transition-colors duration-200"
                        >
                            <Maximize2 class="w-4 h-4" />
                        </button>
                    </a>
                </div>
            </div>

            <div v-if="hasAdditionalPhotos" class="space-y-4">
                <h4 class="text-lg font-semibold text-gray-800">Additional Photos</h4>
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                    <div
                        v-for="(url, idx) in galleryUrls"
                        :key="idx"
                        class="relative group cursor-pointer"
                    >
                        <a :href="url" class="glightbox cursor-pointer" :data-gallery="`gallery-${galleryId}`">
                            <img
                                :src="url"
                                :alt="`Gallery Image ${idx + 1}`"
                                class="w-full h-32 object-contain bg-gray-50 rounded-xl shadow-md transition-all duration-300 group-hover:shadow-xl group-hover:scale-105"
                            />
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-300 rounded-xl flex items-center justify-center">
                                <Maximize2 class="w-5 h-5 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="text-center py-12">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <Camera class="w-8 h-8 text-gray-400" />
            </div>
            <h4 class="text-lg font-medium text-gray-600 mb-2">No Images Available</h4>
            <p class="text-gray-500">
                {{ forPublic ? 'Owner has no uploads.' : 'Upload photos to showcase this location.' }}
            </p>
        </div>

    </div>
</template>
