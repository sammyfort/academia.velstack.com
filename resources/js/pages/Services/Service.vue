<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/layouts/Layout.vue'
import { ArrowLeft, Eye, MapPin, Star, Award, Map, Phone, MessageCircle } from 'lucide-vue-next';
import { Button } from '@/components/ui/button'
import { AverageRatingsI, RatingsDistributionI, ServiceI } from '@/types';
import ImagePreview from '@/components/ImagePreview.vue';
import { Badge } from '@/components/ui/badge';
import AdvertisedServicesH from '@/components/Services/AdvertisedServicesH.vue';
import ReviewsDetails from '@/components/ReviewsDetails.vue';
import { computed } from 'vue';
import ShareToSocials from '@/components/ShareToSocials.vue';
import VideoEmbed from '@/components/VideoEmbed.vue';
import { whatsappChatLink } from '@/lib/helpers';

const props = defineProps<{
    service: ServiceI,
    ratings: AverageRatingsI;
    distributions: RatingsDistributionI;
}>()

const reviews = computed(()=> props.service.reviews);

const wpChatLink = whatsappChatLink(
    props.service.whatsapp_mobile,
    `Hello, please I am inquiring about your service: ${props.service.title}\n\nService link:\n${route('services.show', props.service.id)}`
)

</script>

<template>
    <Head :title="service.title" />

    <Layout>
        <div class="min-h-screen w-full bg-gradient-to-br from-slate-50 via-white to-blue-50">
            <div class="relative overflow-hidden bg-secondary">
                <div class="absolute inset-0 bg-black opacity-20"></div>
                <div class="relative px-6 py-8">
                    <div class="max-w-6xl mx-auto">
                        <Link :href="route('services.index')"
                              class="inline-flex items-center gap-2 text-white/80 hover:text-white mb-6 transition-colors"
                        >
                            <ArrowLeft class="w-5 h-5" />
                            <span>Back to Service Providers</span>
                        </Link>

                        <div class="flex flex-col lg:flex-row gap-8 items-start">
                            <div class="flex-shrink-0 w-full md:w-auto">
                                <div class="relative overflow-hidden w-full h-50 md:w-35 md:h-35 bg-gradient-to-br from-slate-200 to-slate-300 rounded-2xl">
                                    <div class="relative w-full h-full">
                                        <img :src="service.featured as string" alt="Service image" class="w-full h-full object-cover" />
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent"></div>
                                    </div>

                                    <div v-if="service.is_promoted" class="absolute top-4 left-4 z-20">
                                        <span class="bg-yellow-400/90 backdrop-blur-sm text-yellow-900 text-sm font-bold px-3 py-2 rounded-full flex items-center gap-1">
                                            <Star class="w-4 h-4 fill-current" />
                                            Trending Artisan
                                        </span>
                                    </div>

                                    <div class="absolute top-4 right-4 z-20">
                                        <span class="bg-secondary/50 backdrop-blur-sm text-white text-xs font-bold px-2 py-1 rounded-full">
                                            {{ service.user.mobile }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex-grow w-full">
                                <h1 class="text-3xl text-center md:text-start md:text-3xl font-black text-white mb-4 tracking-tight leading-tight">
                                    {{ service.title }}
                                </h1>

                                <div class="flex items-center gap-4 mb-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white font-bold">
                                            {{ service.user?.initials }}
                                        </div>
                                        <div class="text-slate-300">
                                            <p class="font-semibold">{{ service.user?.fullname }}</p>
                                            <div class="flex items-center gap-1">
                                                <MapPin class="w-4 h-4" />
                                                <p>{{ service.town }}</p>
                                            </div>
                                            <div class="flex items-center mt-1 gap-1">
                                                <Eye :size="18"/>
                                                <span class="text-sm">{{ service.views_count }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10 max-w-[1200px] mx-auto flex flex-col">
                <div class="text-xl font-semibold text-fade">
                    Top Trending Service Providers
                </div>
                <AdvertisedServicesH/>
            </div>

            <div class=" pb-12 pt-5">
                <div class="max-w-6xl mx-auto">
                    <div class="grid lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-2 space-y-8">
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                                <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-3">
                                    <Award :size="20" class="text-primary" />
                                    Service Description
                                </h2>
                                <div class="prose prose-slate max-w-none">
                                    <p v-html="service.description" class="text-slate-700 leading-relaxed text-lg">
                                    </p>
                                </div>
                            </div>
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                                <h2 class="text-xl font-bold text-slate-900 mb-6">Field of Service</h2>
                                <div class="flex flex-wrap gap-3">
                                    <span
                                        class="bg-gradient-to-r from-primary/10 to-primary/5 text-primary text-sm font-semibold px-4 py-2 rounded-full border border-primary/20">
                                        {{ service.category.name }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-slate-900 mb-6">Gallery</h2>
                                <ImagePreview :featured-url="service.featured" :gallery-urls="service.gallery"/>
                            </div>
                            <div v-if="service.video_link">
                                <div class="mb-3 text-lg font-semibold">Video Portfolio</div>
                                <VideoEmbed :url="service.video_link" />
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sticky top-6">
                                <h3 class="text-xl font-bold text-slate-900 mb-6">Service Details</h3>
                                <div>
                                    <div v-if="service.business_name" class="flex items-center text-sm py-2 justify-between border-b border-slate-100">
                                        <span class="text-slate-600">Business</span>
                                        <span class="font-semibold text-slate-900">
                                            {{ service.business_name }}
                                        </span>
                                    </div>
                                    <div class="flex items-center text-sm py-2 justify-between border-b border-slate-100">
                                        <span class="text-slate-600">Artisan</span>
                                        <span class="font-semibold text-slate-900">
                                            {{ service.user.fullname }}
                                        </span>
                                    </div>
                                    <div class="flex items-center text-sm py-2 justify-between border-b border-slate-100">
                                        <span class="text-slate-600">Years of exp.</span>
                                        <span class="font-semibold text-slate-900">
                                            {{ service.years_experience }} Year(s)
                                        </span>
                                    </div>
                                    <div class="flex items-center text-sm py-2 justify-between border-b border-slate-100">
                                        <span class="text-slate-600">Mobile</span>
                                        <span class="font-semibold text-slate-900">
                                            {{ service.first_mobile }}
                                        </span>
                                    </div>
                                    <div v-if="service.second_mobile" class="flex items-center text-sm py-2 justify-between border-b border-slate-100">
                                        <span class="text-slate-600">Second Mobile</span>
                                        <span class="font-semibold text-slate-900">
                                            {{ service.second_mobile }}
                                        </span>
                                    </div>
                                    <div v-if="service.email" class="flex items-center text-sm py-2 justify-between border-b border-slate-100">
                                        <span class="text-slate-600">Email</span>
                                        <span class="font-semibold text-slate-900 text-sm">
                                            {{ service.email }}
                                        </span>
                                    </div>
                                    <div v-if="service.address" class="flex items-center text-sm py-2 justify-between border-b border-slate-100">
                                        <span class="text-slate-600">Address</span>
                                        <span class="font-semibold text-slate-900">
                                            {{ service.address }}
                                        </span>
                                    </div>
                                    <div v-if="service.region" class="flex items-center text-sm py-2 justify-between border-b border-slate-100">
                                        <span class="text-slate-600">Region</span>
                                        <span class="font-semibold text-slate-900">
                                            {{ service.region.name }}
                                        </span>
                                    </div>
                                    <div class="flex items-center text-sm py-2 justify-between border-b border-slate-100">
                                        <span class="text-slate-600">Town</span>
                                        <span class="font-semibold text-slate-900">
                                            {{ service.town }}
                                        </span>
                                    </div>
                                    <div class="flex items-center text-sm py-2 justify-between border-b border-slate-100">
                                        <span class="text-slate-600">Status</span>
                                        <Badge variant="primary">Active</Badge>
                                    </div>
                                    <div class="flex items-center text-sm py-2 justify-between border-b border-slate-100">
                                        <span class="text-slate-600">Joined On</span>
                                        <span class="font-semibold text-slate-900">
                                            {{ new Date(service.created_at).toLocaleDateString() }}
                                        </span>
                                    </div>
                                    <div v-if="service.gps_lat && service.gps_lon" class="flex items-center text-sm py-2 justify-between border-b border-slate-100">
                                        <span class="text-slate-600">GPS</span>
                                        <a
                                            :href="`https://maps.google.com/?q=${service.gps_lat},${service.gps_lon}`"
                                            target="_blank"
                                            class="flex items-center gap-1 text-primary underline"
                                        >
                                            <Map :size="15" />
                                            <span class="font-medium text-sm">View on Google Maps</span>
                                        </a>
                                    </div>
                                    <div class="flex items-center text-sm py-2 justify-between border-b border-slate-100">
                                        <ShareToSocials />
                                    </div>
                                    <div class="flex items-center text-sm py-2 justify-between border-b border-slate-100">
                                        <Button size="sm" variant="secondary" as-child>
                                            <a
                                                :href="`tel:${service.first_mobile}`"
                                                target="_blank"
                                                class="flex items-center gap-2"
                                            >
                                                <Phone :size="17"/> Call
                                            </a>
                                        </Button>
                                        <Button v-if="wpChatLink" size="sm" variant="secondary">
                                            <a
                                                :href="wpChatLink"
                                                target="_blank"
                                                class="flex items-center gap-2"
                                            >
                                                <MessageCircle :size="17"/> Chat on WhatsApp
                                            </a>
                                        </Button>
                                    </div>
                                </div>

                                <div class="mt-8 space-y-3" v-if="$page.props.auth.user && service.user_id === $page.props.auth.user.id">
                                    <Link :href="route('my-services.show', service.id)" class="block">
                                        <Button class="w-full bg-primary hover:bg-primary-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 flex items-center justify-center gap-2">
                                            View on dashboard
                                        </Button>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6 pt-10">
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sticky top-6">
                            <h3 class="text-xl font-bold text-slate-900 mb-6">Ratings Distribution and Reviews</h3>
                            <div>
                                <ReviewsDetails
                                    ratable_type="service"
                                    :ratable="service"
                                    :ratings="ratings"
                                    :distributions="distributions"
                                    :reviews="reviews"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </Layout>
</template>

<style scoped>

</style>
