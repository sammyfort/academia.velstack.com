<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import Layout from '@/layouts/Layout.vue';
import { ArrowLeft, Edit, Trash2, Eye, MapPin, Star, Building2, Share2, Award, PlusIcon, VideoIcon } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { AverageRatingsI, PaymentStatusI, PromotionPlanI, RatingsDistributionI, ServiceI } from '@/types';
import { ref, onMounted, computed } from 'vue';
import ConfirmDialogue from '@/components/helpers/ConfirmDialogue.vue';
import ImagePreview from '@/components/ImagePreview.vue';
import { toastError, toastSuccess } from '@/lib/helpers';
import PaymentHistory from '@/components/promotions/PaymentHistory.vue';
import PromoteNow from '@/components/promotions/PromoteNow.vue';
import { PromotableE } from '@/lib/enums';
import PromotionPaymentAlert from '@/components/promotions/PromotionPaymentAlert.vue';
import ActivePromotionInfo from '@/pages/Products/ActivePromotionInfo.vue';
import ReviewsDetails from '@/components/ReviewsDetails.vue';

const props = defineProps<{
    service: ServiceI;
    plans: PromotionPlanI[];
    payment_status: PaymentStatusI;
    ratings: AverageRatingsI;
    distributions: RatingsDistributionI;
}>();

onMounted(()=>{
   // console.log(props.service)
})
const reviews = computed(() => props.service.reviews);

const isDeleting = ref(false);


const handleDelete = () => {
    isDeleting.value = true;
    router.delete(route('my-services.destroy', props.service.id), {
        onSuccess: (res) => {
            if (res.props.success) {
                toastSuccess(res.props.message);
            } else {
                toastError(res.props.message);
            }
        },
        onFinish: () => {
            isDeleting.value = false;
        },
    });
};

const handleShare = () => {
    if (navigator.share) {
        navigator.share({
            title: props.service.title,
            text: props.service.description,
            url: window.location.href,
        });
    } else {
        navigator.clipboard.writeText(window.location.href);
        toastSuccess('Link copied to clipboard!');
    }
};
</script>

<template>
    <Head :title="service.title" />

    <Layout>
        <div class="fixed right-8 bottom-8 z-50 hidden space-y-4 md:block">
            <Link :href="route('my-services.edit', service.slug)">
                <Button
                    class="hover:bg-primary-700 transform rounded-full bg-primary p-4 font-semibold text-white shadow-2xl transition-all duration-300 ease-out hover:scale-110"
                >
                    <Edit class="h-5 w-5" />
                </Button>
            </Link>
            <Button
                @click="handleShare"
                class="transform rounded-full bg-blue-600 p-4 font-semibold text-white shadow-2xl transition-all duration-300 ease-out hover:scale-110 hover:bg-blue-700"
            >
                <Share2 class="h-5 w-5" />
            </Button>
        </div>

        <div class="min-h-screen w-full bg-gradient-to-br from-slate-50 via-white to-blue-50">
            <PromotionPaymentAlert :payment_status="payment_status" />
            <div class="relative overflow-hidden bg-secondary">
                <div class="absolute inset-0 bg-black opacity-20"></div>
                <div class="relative px-6 py-8">
                    <div class="mx-auto max-w-6xl">
                        <Link
                            :href="route('my-services.index')"
                            class="mb-6 inline-flex items-center me-6 gap-2 text-white/80 transition-colors hover:text-white"
                        >
                            <ArrowLeft class="h-5 w-5" />
                            <span>Back to My Works</span>
                        </Link>

                        <a
                            target="_blank"
                            :href="route('my-services.index', props.service.slug)"
                            class="inline-flex items-center bg-gray-100 px-3 py-2   border rounded-md gap-2 text-black   mb-6 transition-colors"
                        >
                            <Eye class="h-5 w-5" />
                            <span>View as guest</span>
                        </a>

                        <div class="flex flex-col items-start gap-8 lg:flex-row">
                            <div class="flex-shrink-0">
                                <div class="relative h-64 w-80 overflow-hidden rounded-2xl bg-gradient-to-br from-slate-200 to-slate-300">
                                    <div class="relative h-full w-full">
                                        <img :src="service.featured as string" alt="Service image" class="h-full w-full object-cover" />
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent"></div>
                                    </div>

                                    <div class="absolute top-4 left-4 z-20">
                                        <span
                                            class="flex items-center gap-1 rounded-full bg-yellow-400/90 px-3 py-2 text-sm font-bold text-yellow-900 backdrop-blur-sm"
                                        >
                                            <Star class="h-4 w-4 fill-current" />
                                            {{ service.title }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex-grow">
                                <h1 class="mb-4 text-3xl leading-tight font-black tracking-tight text-white md:text-5xl">
                                    {{ service.title }}
                                </h1>

                                <div class="mb-6 flex items-center gap-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-12 w-12 items-center justify-center rounded-full bg-white/20 font-bold text-white backdrop-blur-sm"
                                        >
                                            {{ service.user?.fullname.charAt(0) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-white">{{ service.user?.fullname }}</p>
                                            <div class="flex items-center gap-1">
                                                <MapPin class="h-4 w-4 text-slate-300" />
                                                <p class="text-slate-300">{{ service.town }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                                    <div class="rounded-xl bg-white/10 p-4 text-center backdrop-blur-sm">
                                        <Building2 class="mx-auto mb-2 h-6 w-6 fill-current text-yellow-300" />
                                        <div class="font-meduium text-lg text-white">Category</div>
                                        <div class="text-sm text-slate-300">{{ service.category.name }}</div>
                                    </div>

                                    <div class="rounded-xl bg-white/10 p-4 text-center backdrop-blur-sm">
                                        <Eye class="mx-auto mb-2 h-6 w-6 text-purple-400" />
                                        <div class="text-lg font-bold text-white">{{ service.views_count }}</div>
                                        <div class="text-sm text-slate-300">Views</div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="service.video_link" class="flex gap-3">
                                <a
                                    target="_blank"
                                    :href="service.video_link"
                                    class="flex items-center gap-x-2 rounded-xl border border-white/30 bg-primary px-6 py-3
                                    text-white backdrop-blur-sm transition-all duration-200 hover:scale-105 "
                                >
                                    <VideoIcon class="h-5 w-5" />
                                    <span>Watch Video</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-b border-slate-200 bg-white px-6 py-6 md:hidden">
                <div class="flex gap-3">
                    <Link :href="route('my-services.edit', service.slug)" class="flex-1">
                        <Button
                            class="hover:bg-primary-700 flex w-full items-center justify-center gap-3 rounded-xl bg-primary px-6 py-4 font-semibold text-white shadow-lg"
                        >
                            <Edit class="h-5 w-5" />
                            <span>Edit Service</span>
                        </Button>
                    </Link>

                    <Button @click="handleShare" class="rounded-xl bg-blue-600 px-6 py-4 font-semibold text-white shadow-lg hover:bg-blue-700">
                        <Share2 class="h-5 w-5" />
                    </Button>
                </div>
            </div>
            <div class="px-6 py-12">
                <div class="mx-auto max-w-6xl">
                    <div class="grid gap-8 lg:grid-cols-3">
                        <div class="space-y-8 lg:col-span-2">
                            <div class="rounded-2xl border border-slate-100 bg-white p-8 shadow-sm">
                                <h2 class="mb-6 flex items-center gap-3 text-2xl font-bold text-slate-900">
                                    <Award class="h-6 w-6 text-primary" />
                                    Service Description
                                </h2>
                                <div class="prose prose-slate max-w-none">
                                    <p v-html="service.description" class="text-lg leading-relaxed text-slate-700"></p>
                                </div>
                            </div>

                            <div>
                                <h2 class="mb-6 text-2xl font-bold text-slate-900">Gallery</h2>
                                <ImagePreview :featured-url="service.featured as string" :gallery-urls="service.gallery as []" />
                            </div>
                            <PaymentHistory :promotions="service.promotions" />
                            <div class="mx-auto w-full">
                                <ReviewsDetails
                                    :ratable="service"
                                    :ratings="ratings"
                                    :distributions="distributions"
                                    :reviews="reviews"
                                    ratable_type="signboard"
                                />
                            </div>
                        </div>

                        <div class="space-y-1">
                            <div class="mt-5 border-t pt-3 lg:col-span-1">
                                    <ActivePromotionInfo :promotable="service" />
                                </div>
                                <div class="lg:col-span-1">
                                    <PromoteNow :promotable="service" :plans="plans" :promotable-type="PromotableE.SERVICE" />
                                </div>
                                
                            <div class="sticky top-6 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                                <h3 class="mb-2 text-xl font-bold text-slate-900">Service Details</h3>

                                <div class="space-y-1">
                                    <div class="flex items-center justify-between border-b border-slate-100 py-1">
                                        <span class="text-slate-600">Status</span>
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-800"> Active </span>
                                    </div>

                                    <div class="flex items-center justify-between border-b border-slate-100 py-1">
                                        <span class="text-slate-600">Created</span>
                                        <span class="font-semibold text-slate-900">
                                            {{ new Date(service.created_at).toLocaleDateString() }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between py-1">
                                        <span class="text-slate-600">Years of Expeirience</span>
                                        <span class="font-semibold text-slate-900">
                                            {{ service.years_experience ?? "N/A" }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between py-1">
                                        <span class="text-slate-600">First Mobile</span>
                                        <span class="font-semibold text-slate-900">
                                            {{ service.first_mobile }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between py-1">
                                        <span class="text-slate-600">Second Mobile</span>
                                        <span class="font-semibold text-slate-900">
                                            {{ service.second_mobile }}
                                        </span>
                                    </div>

                                    <div v-if="service.region" class="flex items-center justify-between py-1">
                                        <span class="text-slate-600">Region</span>
                                        <span class="font-semibold text-slate-900">
                                            {{ service.region.name }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between py-1">
                                        <span class="text-slate-600">Town</span>
                                        <span class="font-semibold text-slate-900">
                                            {{ service.town }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between py-1">
                                        <span class="text-slate-600">Address</span>
                                        <span class="font-semibold text-slate-900">
                                            {{ service.address }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-8 space-y-3">
                                    <Link :href="route('my-services.edit', service.slug)" class="block">
                                        <Button
                                            class="hover:bg-primary-700 flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-4 py-3 font-semibold text-white transition-all duration-200"
                                        >
                                            <Edit class="h-4 w-4" />
                                            Edit Service
                                        </Button>
                                    </Link>

                                    <Link :href="route('my-services.create')" class="block">
                                        <Button
                                            class="hover:bg-primary-700 flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-4 py-3 font-semibold text-white transition-all duration-200"
                                        >
                                            <PlusIcon class="h-4 w-4" />
                                            Add Service
                                        </Button>
                                    </Link>

                                    <ConfirmDialogue @confirm="handleDelete">
                                        <Button
                                            class="flex w-full items-center justify-center gap-2 rounded-xl bg-red-600 px-4 py-3 font-semibold text-white transition-all duration-200 hover:bg-red-700"
                                        >
                                            <Trash2 class="h-4 w-4" />
                                            {{ isDeleting ? 'Deleting...' : 'Delete Work' }}
                                        </Button>
                                    </ConfirmDialogue>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>

<style scoped></style>
