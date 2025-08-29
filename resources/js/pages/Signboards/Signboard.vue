<script setup lang="ts">
import Layout from '@/layouts/Layout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { AverageRatingsI, RatingsDistributionI, SignboardI } from '@/types';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import TrustedBadge from '@/components/badges/TrustedBadge.vue';
import ContactDetails from '@/components/signboard/Details/ContactDetails.vue';
import SocialsDetails from '@/components/signboard/Details/SocialsDetails.vue';
import LocationDetails from '@/components/signboard/Details/LocationDetails.vue';
import { Badge } from '@/components/ui/badge';
import SignboardGallery from '@/components/signboard/Details/SignboardGallery.vue';
import AdvertisedSignboardV from '@/components/signboard/AdvertisedSignboardV.vue';
import AdvertisedSignboardsH from '@/components/signboard/AdvertisedSignboardsH.vue';
import { Eye, MessageCircle, Phone } from 'lucide-vue-next';
import TextLink from '@/components/TextLink.vue';
import { computed } from 'vue';
import ReviewsDetails from '@/components/ReviewsDetails.vue';
import { Button } from '@/components/ui/button';
import { whatsappChatLink } from '@/lib/helpers';

type Props = {
    signboard: SignboardI;
    ratings: AverageRatingsI;
    distributions: RatingsDistributionI;
};
const props = defineProps<Props>();
const service = props.signboard.service;
const reviews = computed(() => props.signboard.reviews);

const wpChatLink = whatsappChatLink(
    service.whatsapp_mobile,
    `Hello, please I am inquiring about your business: ${props.signboard.name}\n\nSignboard link:\n${route('signboards.show', props.signboard.id)}`,
);
</script>

<template>
    <Head title="Signboard Details" />
    <Layout>
        <div class="grid grid-cols-1 gap-4">
            <div class="w-full lg:hidden">
                <AdvertisedSignboardsH>
                    <div class="text-fade text-xl">Trusted Signboards</div>
                </AdvertisedSignboardsH>
            </div>
            <div class="grid w-full grid-cols-1 lg:grid-cols-8">
                <div class="order-1 p-3 lg:order-2 lg:col-span-4">
                    <div class="rounded-lg border">
                        <div class="flex items-center gap-4 rounded-t-lg border-b bg-secondary p-4 text-white">
                            <Avatar class="h-[5.5rem] w-[5.5rem]">
                                <AvatarImage src="" :size="50" />
                                <AvatarFallback class="text-2xl font-bold text-black">{{ service?.initials }}</AvatarFallback>
                            </Avatar>
                            <div class="flex w-full flex-col">
                                <div class="flex items-center">
                                    <div class="text-2xl font-black">{{ service?.business_name ?? service?.title }}</div>
                                    <TextLink
                                        :href="route('my-signboards.show', signboard.slug)"
                                        class="ms-auto text-sm text-white hover:text-primary"
                                        v-if="$page.props.auth.user?.id === service?.user_id"
                                        >View on dashboard</TextLink
                                    >
                                </div>
                                <div class="font-semibold">{{ signboard.landmark }}</div>
                                <div class="flex items-center gap-2 font-bold text-primary" v-if="signboard.active_subscription">
                                    <span>Trusted</span>
                                    <TrustedBadge :size="20" />
                                </div>
                                <div class="flex">
                                    <div class="flex gap-2 text-gray-300">
                                        <Eye :size="20" />
                                        <div class="text-sm">{{ signboard.views_count }}</div>
                                    </div>
                                    <div class="ms-auto text-sm text-gray-300">{{ signboard.created_at_str }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="text-fade p-4">
                            <div class="flex flex-wrap">
                                <div class="flex w-full flex-col gap-7 lg:w-1/2">
                                    <ContactDetails :signboard="signboard" />
                                    <SocialsDetails :signboard="signboard" />
                                </div>
                                <div class="w-full lg:ms-auto lg:w-1/2 lg:ps-10">
                                    <LocationDetails :signboard="signboard" />
                                    <div class="flex gap-3 flex-wrap">
                                        <Button size="sm" variant="secondary" as-child>
                                            <a :href="`tel:${service.first_mobile}`" target="_blank" class="flex items-center gap-1">
                                                <Phone :size="17" /> Call
                                            </a>
                                        </Button>
                                        <Button v-if="wpChatLink" size="sm" variant="secondary">
                                            <a :href="wpChatLink" target="_blank" class="flex items-center gap-1">
                                                <MessageCircle :size="17" /> Chat on WhatsApp
                                            </a>
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-fade border-t p-4">
                            <div class="text-lg font-medium">Business Operation Details</div>
                            <div class="mt-3 flex w-full flex-col gap-3">
                                <div>
                                    <div class="mb-1 underline">Description</div>
                                    <div>{{ service?.description }}</div>
                                </div>
                                <div>
                                    <div class="text-fade mb-1 underline">Fields Of Operation</div>
                                    <div class="flex flex-wrap gap-3">
                                        <Badge v-for="category in signboard.categories" :key="category.id" class="cursor-pointer" variant="secondary">
                                            <Link href="" class="hover:text-primary">{{ category.name }}</Link>
                                        </Badge>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ReviewsDetails
                            ratable_type="signboard"
                            :ratable="signboard"
                            :ratings="ratings"
                            :distributions="distributions"
                            :reviews="reviews"
                        />
                    </div>
                </div>
                <div class="order-2 p-3 lg:order-1 lg:col-span-2">
                    <SignboardGallery :signboard="signboard" class="rounded-none border-2 bg-transparent shadow-none" />
                </div>
                <div class="order-3 hidden p-3 lg:order-3 lg:col-span-2 lg:block">
                    <AdvertisedSignboardV items-class="h-screen" />
                </div>
            </div>
        </div>
    </Layout>
</template>

<style scoped></style>
