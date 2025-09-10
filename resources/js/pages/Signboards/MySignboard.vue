<script setup lang="ts">
import Layout from '@/layouts/GuestLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Briefcase, Building, Edit, Loader2, MapPin, PlusIcon, Trash2, Eye } from 'lucide-vue-next';
import { toastError, toastSuccess } from '@/lib/helpers';
import { computed, ref, onMounted } from 'vue';
import ConfirmDialogue from '@/components/helpers/ConfirmDialogue.vue';
import { AverageRatingsI, PaymentStatusI, PromotionPlanI, RatingsDistributionI, SignboardI } from '@/types';
import PaymentHistory from '@/components/promotions/PaymentHistory.vue';
import PromoteSignboard from '@/pages/Signboards/blocks/PromoteSignboard.vue';
import LocationDetails from '@/pages/Signboards/blocks/LocationDetails.vue';
import SignboardActions from '@/pages/Signboards/blocks/SignboardActions.vue';
import OperationFields from '@/pages/Signboards/blocks/OperationFields.vue';
import ImagePreview from '@/components/ImagePreview.vue';
import { PromotableE } from '@/lib/enums';
import ReviewsDetails from '@/components/ReviewsDetails.vue';
import PromotionPaymentAlert from '@/components/promotions/PromotionPaymentAlert.vue';

const props = defineProps<{
    signboard: SignboardI;
    plans: PromotionPlanI[];
    ratings: AverageRatingsI;
    distributions: RatingsDistributionI;
    payment_status: PaymentStatusI
}>();

const reviews = computed(() => props.signboard.reviews);

const showDialog = ref(false);
const isDeleting = ref(false);
const deleteSignboard = () => {
    isDeleting.value = true;
    router.delete(route('my-signboards.delete', props.signboard.id), {
        onSuccess: (res) => {
            if (res.props.success) {
                toastSuccess(res.props.message);
            } else {
                toastError(res.props.message);
            }
        },
        onFinish: () => {
            isDeleting.value = false;
            showDialog.value = false;
        },
    });
};

onMounted(()=> {
    console.log(props.signboard)
})
</script>

<template>
    <Head :title="props.signboard.landmark" />
    <Layout>
        <div class="min-h-screen w-full bg-gradient-to-br from-slate-50 to-blue-50">
            <PromotionPaymentAlert :payment_status="payment_status"/>
            <div class="relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-primary via-primary to-primary">
                    <div class="absolute inset-0 bg-black/20"></div>
                    <div class="hero-pattern absolute inset-0"></div>
                </div>

                <div class="relative px-6 py-16 md:py-24">
                    <div class="mx-auto max-w-6xl">
                        <nav class="mb-8">
                            <div class="flex items-center space-x-2 text-sm text-white/80">
                                <span>Signboards</span>
                                <span>/</span>
                                <span>{{ props.signboard.region.name }}</span>
                                <span>/</span>
                                <span class="font-medium text-white">{{ props.signboard.landmark }}</span>

                                <a
                                    target="_blank"
                                    :href="route('signboards.show', props.signboard.slug)"
                                    class="flex items-center px-4 py-2 gap-x-2 bg-gray-100 text-black rounded-xl border border-white/30"
                                >
                                    <Eye class="h-5 w-5" />
                                    <span>View as guest</span>
                                </a>
                            </div>


                        </nav>

                        <div class="flex flex-col items-start justify-between gap-8 lg:flex-row lg:items-end">
                            <div class="flex-1">
                                <div class="mb-4 flex items-center gap-4">
                                    <div
                                        class="flex h-16 w-16 items-center justify-center rounded-2xl border border-white/20 bg-white/10 backdrop-blur-sm"
                                    >
                                        <Briefcase class="h-8 w-8 text-white" />
                                    </div>
                                    <div>
                                        <h1 class="mb-2 text-4xl font-bold text-white md:text-5xl">
                                            {{ props.signboard.name }}
                                        </h1>
                                        <div class="flex items-center gap-4 text-white/90">
                                            <span class="flex items-center gap-2">
                                                <MapPin class="h-4 w-4" />
                                                {{ props.signboard.town }}
                                            </span>
                                            <span class="flex items-center gap-2">
                                                <Building class="h-4 w-4" />
                                                {{ props.signboard.region.name }}
                                            </span>
                                            <span class="flex items-center gap-2">
                                                <Eye class="h-4 w-4" />
                                                {{ props.signboard.views_count}}
                                            </span>
                                        </div>


                                    </div>
                                </div>

                                <div class="max-w-md rounded-2xl border border-white/20 bg-white/10 p-6 backdrop-blur-md">
                                    <h3 class="mb-2 font-semibold text-white">Business Information</h3>
                                    <p class="text-lg text-white/90">{{ props.signboard.service.title }}</p>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <Link
                                    :href="route('my-signboards.create')"
                                    :data="{ business: props.signboard.service.id }"
                                    class="flex items-center gap-x-2 rounded-xl border border-white/30 bg-primary px-6 py-3 text-white backdrop-blur-sm transition-all duration-200 hover:scale-105 hover:bg-primary/30"
                                >
                                    <PlusIcon class="h-5 w-5" />
                                    <span>Add Signboard</span>
                                </Link>

                                <Link
                                    :href="route('my-signboards.edit', props.signboard.slug)"
                                    class="flex items-center gap-x-2 rounded-xl border border-white/30 bg-primary px-6 py-3 text-white backdrop-blur-sm transition-all duration-200 hover:scale-105 hover:bg-primary/30"
                                >
                                    <Edit class="h-5 w-5" />
                                    <span>Edit Signboard</span>
                                </Link>

                                <button
                                    @click="showDialog = true"
                                    :disabled="isDeleting"
                                    class="flex items-center gap-x-2 rounded-xl border border-white/30 bg-red-500 px-6 py-3 text-white backdrop-blur-sm transition-all duration-200 hover:scale-105 hover:bg-red-600"
                                >
                                    <Trash2 class="mr-2 h-4 w-4" />
                                    <span v-if="!isDeleting">Delete Signboard</span>
                                    <span v-else class="flex items-center gap-2">
                                        <Loader2 class="h-5 w-5 animate-spin" />
                                        Deleting...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative z-10 -mt-8 px-6">
                <div class="mx-auto max-w-full">
                    <div class="grid gap-8 lg:grid-cols-4">
                        <div class="lg:col-span-1">
                            <LocationDetails :signboard="signboard" />
                            <SignboardActions :signboard="signboard" />
                        </div>

                        <div class="lg:col-span-2">
                            <ImagePreview
                                :featured-url="props.signboard.featured as string"
                                :gallery-urls="props.signboard.gallery as []"
                                title="Signboard Gallery"
                            />
                        </div>

                        <div class="lg:col-span-1">
                            <OperationFields :signboard="signboard" />
                            <PromoteSignboard :signboard="signboard" :plans="plans" :promotable-type="PromotableE.SIGNBOARD" />
                        </div>
                    </div>
                </div>
                <div class="mt-15 flex w-full flex-wrap">
                    <div class="w-full">
                        <h2 class="mb-4 text-2xl font-bold text-gray-900 text-center">Reviews And Ratings</h2>
                    </div>
                    <div class="w-full lg:w-1/2 mx-auto">
                        <ReviewsDetails
                            :ratable="signboard"
                            :ratings="ratings"
                            :distributions="distributions"
                            :reviews="reviews"
                            ratable_type="signboard"
                        />
                    </div>
                </div>
                <PaymentHistory :promotions="signboard.promotions" />
            </div>
            <ConfirmDialogue
                v-model:open="showDialog"
                :loading="isDeleting"
                title="Delete this signboard?"
                description="This action cannot be undone. All associated data and images will be permanently removed."
                confirmText="Yes, delete signboard"
                cancelText="Keep signboard"
                @confirm="deleteSignboard"
            />
        </div>
    </Layout>
</template>

<style scoped>

</style>
