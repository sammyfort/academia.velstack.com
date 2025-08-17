<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import Layout from '@/layouts/Layout.vue'
import {
    ArrowLeft,
    Edit,
    Trash2,
    Eye,
    MapPin,
    Star,
    Building2,
    Share2,
    Award,
    PlusIcon,
    Briefcase, VideoIcon
} from 'lucide-vue-next';
import { Button } from '@/components/ui/button'
import { PaymentStatusI, PromotionPlanI, ProductI, AverageRatingsI, RatingsDistributionI } from '@/types';
import { ref, onMounted, computed } from 'vue';
import ConfirmDialogue from '@/components/helpers/ConfirmDialogue.vue';
import ImagePreview from '@/components/ImagePreview.vue';
import { toastError, toastSuccess } from '@/lib/helpers';
import PaymentHistory from '@/components/promotions/PaymentHistory.vue';
import PromoteNow from '@/components/promotions/PromoteNow.vue';
import { PromotableE } from '@/lib/enums';
import PromotionPaymentAlert from '@/components/promotions/PromotionPaymentAlert.vue';
import ActivePromotionInfo from '@/pages/Products/ActivePromotionInfo.vue';
import TextEditor from '@/components/forms/TextEditor.vue';
import ReviewsDetails from '@/components/ReviewsDetails.vue';

const props = defineProps<{
    product: ProductI
    plans: PromotionPlanI[]
    payment_status: PaymentStatusI
    ratings: AverageRatingsI;
    distributions: RatingsDistributionI;
}>()
const reviews = computed(() => props.product.reviews);
onMounted(()=> {
   // console.log(props.product)
})

const isDeleting = ref(false)

const handleDelete = () => {
    isDeleting.value = true
        router.delete(route('my-products.destroy', props.product.id), {
            onSuccess: (res) => {
                if (res.props.success) {
                    toastSuccess(res.props.message);
                } else {
                    toastError(res.props.message);
                }
            },
            onFinish: () => {
                isDeleting.value = false
            }
        })

}

const handleShare = () => {
    if (navigator.share) {
        navigator.share({
            title: props.product.name,
            text: props.product.short_description,
            url: window.location.href
        })
    } else {
        navigator.clipboard.writeText(window.location.href)
        toastSuccess('Link copied to clipboard!');
    }
}
</script>

<template>
    <Head :title="product.name" />

    <Layout>

        <div class="hidden md:block fixed bottom-8 right-8 z-50 space-y-4">
            <Link :href="route('my-products.edit', product.slug)">
                <Button class="bg-primary hover:bg-primary-700 text-white font-semibold p-4 rounded-full shadow-2xl transform hover:scale-110 transition-all duration-300 ease-out">
                    <Edit class="w-5 h-5" />
                </Button>
            </Link>
            <Button
                @click="handleShare"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold p-4 rounded-full shadow-2xl transform hover:scale-110 transition-all duration-300 ease-out">
                <Share2 class="w-5 h-5" />
            </Button>
        </div>

        <div class="min-h-screen w-full bg-gradient-to-br from-slate-50 via-white to-blue-50">
            <PromotionPaymentAlert :payment_status="payment_status"/>
            <div class="relative overflow-hidden bg-secondary">
                <div class="absolute inset-0 bg-black opacity-20"></div>
                <div class="relative px-6 py-8">
                    <div class="max-w-6xl mx-auto">
                        <Link :href="route('my-products.index')"
                              class="inline-flex items-center gap-2 me-6 text-white/80 hover:text-white mb-6 transition-colors">
                            <ArrowLeft class="w-5 h-5" />
                            <span>Back to My Products</span>
                        </Link>

                        <Link

                            :href="route('products.show', props.product.slug)"
                            class="inline-flex items-center bg-gray-100 px-3 py-2   border rounded-md gap-2 text-black   mb-6 transition-colors"
                        >
                            <Eye class="h-5 w-5" />
                            <span>View as guest</span>
                        </Link>

                        <div class="flex flex-col lg:flex-row gap-8 items-start">
                            <div class="flex-shrink-0">
                                <div class="relative overflow-hidden w-80 h-64 bg-gradient-to-br from-slate-200 to-slate-300 rounded-2xl">
                                    <div class="relative w-full h-full">
                                        <img :src="product.featured" alt="Service image" class="w-full h-full object-cover" />
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent"></div>
                                    </div>

                                    <div  class="absolute top-4 left-4 z-20">
                                        <span class="bg-yellow-400/90 backdrop-blur-sm text-yellow-900 text-sm font-bold px-3 py-2 rounded-full flex items-center gap-1">
                                            <Star class="w-4 h-4 fill-current" />
                                            {{product.name}}
                                        </span>
                                    </div>

                                </div>
                            </div>

                            <div class="flex-grow">
                                <h1 class="text-3xl md:text-5xl font-black text-white mb-4 tracking-tight leading-tight">
                                    {{ product.name }}
                                </h1>

                                <div class="flex items-center gap-4 mb-6">
                                    <div class="flex items-center gap-3">

                                        <div>

                                            <div class="flex items-center gap-1">
                                                <MapPin class="w-4 h-4 text-slate-300" />
                                                <p class="text-slate-300">{{ product.town }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-center">
                                        <Eye class="w-6 h-6 text-purple-400 mx-auto mb-2" />
                                        <div class="text-white font-bold text-lg">{{ product.views_count }}</div>
                                        <div class="text-slate-300 text-sm">Views</div>
                                    </div>

                                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-center">
                                        <Star class="w-6 h-6 text-purple-400 mx-auto mb-2" />
                                        <div class="text-white font-bold text-lg">{{ product.reviews_count }}</div>
                                        <div class="text-slate-300 text-sm">Reviews</div>
                                    </div>
                                </div>


                            </div>
                            <div v-if="product.video_link" class="flex gap-3">
                                <a
                                    target="_blank"
                                    :href="product.video_link"
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
            <div class="md:hidden px-6 py-6 bg-white border-b border-slate-200">
                <div class="flex gap-3">
                    <Link :href="route('my-products.edit', product.slug)" class="flex-1">
                        <Button class="w-full bg-primary hover:bg-primary-700 text-white font-semibold px-6 py-4 rounded-xl shadow-lg flex items-center justify-center gap-3">
                            <Edit class="w-5 h-5" />
                            <span>Edit Product</span>
                        </Button>
                    </Link>

                    <Button @click="handleShare" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-4 rounded-xl shadow-lg">
                        <Share2 class="w-5 h-5" />
                    </Button>
                </div>
            </div>
            <div class="px-6 py-12">
                <div class="max-w-6xl mx-auto">
                    <div class="grid lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-2 space-y-8">
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                                <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center gap-3">
                                    <Award class="w-6 h-6 text-primary" />
                                     Summary
                                </h2>
                                <div class="prose prose-slate max-w-none">
                                    <p class="text-slate-700 leading-relaxed text-lg">
                                        {{ product.short_description }}
                                    </p>

                                </div>
                            </div>


                            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                                <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center gap-3">
                                    <Briefcase class="w-6 h-6 text-primary" />
                                    Product Description
                                </h2>
                                <div class="prose prose-slate max-w-none">
                                    <p v-html="product.description"  class="text-slate-700 leading-relaxed text-lg"></p>
<!--                                    <TextEditor :model-value="product.description" readonly/>-->

                                </div>
                            </div>

                            <div  >
                                <h2 class="text-2xl font-bold text-slate-900 mb-6">Gallery</h2>
                                 <ImagePreview :featured-url="product.featured" :gallery-urls="product.gallery"/>
                            </div>
                            <PaymentHistory :promotions="product.promotions" />

                            <div class="w-full mx-auto">
                                <ReviewsDetails
                                    :ratable="product"
                                    :ratings="ratings"
                                    :distributions="distributions"
                                    :reviews="reviews"
                                    ratable_type="signboard"
                                />
                            </div>
                        </div>


                        <div class="space-y-6">
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sticky top-6">
                                <h3 class="text-xl font-bold text-slate-900 mb-6">Product Details</h3>

                                <div class="space-y-4">
                                    <div class="flex items-center justify-between py-3 border-b border-slate-100">
                                        <span class="text-slate-600">Status</span>
                                        <span :class="['text-sm font-semibold px-3 py-1 rounded-full capitalize',
                                        product.status === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800 ']">
                                            {{product.status}}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between py-3 border-b border-slate-100">
                                        <span class="text-slate-600">Region</span>
                                        <span class="font-semibold text-slate-900">
                                            {{ product.region.name }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between py-3 border-b border-slate-100">
                                        <span class="text-slate-600">Price</span>
                                        <span class="font-semibold text-slate-900">
                                            {{ product.price }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between py-3 border-b border-slate-100">
                                        <span class="text-slate-600">Views</span>
                                        <span class="font-semibold text-slate-900">
                                            {{ product.views_count }}
                                        </span>
                                    </div>


                                    <div class="flex items-center justify-between py-3 border-b border-slate-100">
                                        <span class="text-slate-600">Is Negotiable</span>
                                        <span class="font-semibold text-slate-900">
                                            {{ product.is_negotiable ? 'Yes' : 'No' }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between py-3 border-b border-slate-100">
                                        <span class="text-slate-600">Reviews</span>
                                        <span class="font-semibold text-slate-900">
                                            {{ product.reviews_count }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between py-3 border-b border-slate-100">
                                        <span class="text-slate-600">First Mobile</span>
                                        <span class="font-semibold text-slate-900">
                                            {{ product.first_mobile }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between py-3 border-b border-slate-100">
                                        <span class="text-slate-600">Second Mobile</span>
                                        <span class="font-semibold text-slate-900">
                                            {{ product.second_mobile ?? 'N/A' }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between py-3">
                                        <span class="text-slate-600">WhatsApp</span>
                                        <span class="font-semibold text-slate-900">
                                            {{product.whatsapp_mobile ?? "N/A" }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between py-3 border-b border-slate-100">
                                        <span class="text-slate-600 me-3">Website</span>
                                        <span class="font-semibold text-slate-900 truncate hover:text-blue-600">
                                           <a :href="product.website?? '#'" target="_blank"> {{ product.website ?? 'N/A' }}</a>
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-8 space-y-3">
                                    <Link
                                        :href="route('my-products.edit', product.slug)"
                                        class="block">
                                        <Button class="w-full bg-primary hover:bg-primary-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 flex items-center justify-center gap-2">
                                            <Edit class="w-4 h-4" />
                                            Edit Product
                                        </Button>
                                    </Link>


                                    <Link :href="route('my-products.create')" class="block">
                                        <Button class="w-full bg-primary hover:bg-primary-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 flex items-center justify-center gap-2">
                                            <PlusIcon class="w-4 h-4" />
                                            Add Product
                                        </Button>
                                    </Link>

                                    <ConfirmDialogue @confirm="handleDelete">
                                        <Button
                                            class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3
                                            px-4 rounded-xl transition-all duration-200 flex items-center justify-center gap-2">
                                            <Trash2 class="w-4 h-4" />
                                            {{ isDeleting ? 'Deleting...' : 'Delete Product' }}
                                        </Button>
                                    </ConfirmDialogue>

                                </div>
                                <div class="lg:col-span-1 mt-5 border-t pt-3">
                                    <ActivePromotionInfo :promotable="product" />
                                </div>
                                <div class="lg:col-span-1">
                                    <PromoteNow :promotable="product" :plans="plans" :promotable-type="PromotableE.SERVICE"/>
                                </div>
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
