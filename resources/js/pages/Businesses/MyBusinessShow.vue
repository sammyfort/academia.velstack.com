<script setup lang="ts">
import Layout from '@/layouts/GuestLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Briefcase, CalendarDays, MapPin, Star, Trophy, Plus, Edit3, Trash2, PlusIcon, EyeIcon } from 'lucide-vue-next';
import MyBusinessUpdate from '@/pages/Businesses/MyBusinessUpdate.vue';
import { Button } from '@/components/ui/button';
import VerifiedBadge from '@/components/icons/VerifiedBadge.vue';
import { router } from '@inertiajs/vue3';
import { toastError, toastSuccess } from '@/lib/helpers';
import { ref, onMounted } from 'vue';
import ConfirmDialogue from '@/components/helpers/ConfirmDialogue.vue';
import { BusinessI } from '@/types';

const props = defineProps<{
    business: BusinessI;
}>();

onMounted(() => {
    // console.log(props.business.signboards)
});
const showDialog = ref(false);
const isDeleting = ref(false);

const closeBusiness = () => {
    isDeleting.value = true;

    router.delete(route('my-businesses.delete', props.business.id), {
        onSuccess: (res) => {
            const message = res.props.message;
            if (res.props.success) toastSuccess(message);
            else toastError(message);
        },
        onFinish: () => {
            isDeleting.value = false;
            showDialog.value = false;
        },
        preserveScroll: true
    });
};


</script>

<template>
    <Head :title="business.name" />
    <Layout>
        <div class="bg-gradient-to-br from-slate-50 to-gray-100 w-full min-h-screen">
            <div class="relative bg-gradient-to-r from-primary via-primary to-primary h-64 sm:h-80">
                <div class="absolute inset-0 bg-black/20"></div>
                <div class="relative z-10 w-full px-4 sm:px-6 h-full flex items-end pb-8 sm:pb-16">
                    <div class="w-full max-w-7xl mx-auto">
                        <div class="flex flex-col sm:flex-row items-center sm:items-end gap-4 sm:gap-8">

                            <div class="relative flex-shrink-0">
                                <div
                                    class="w-24 h-24 sm:w-32 sm:h-32 rounded-2xl bg-white shadow-2xl flex items-center justify-center border-4 border-white/50 backdrop-blur-sm">
                                    <Briefcase class="w-12 h-12 sm:w-16 sm:h-16 text-primary" />
                                </div>
                                <div
                                    class="absolute -top-2 -right-2 w-6 h-6 sm:w-8 sm:h-8 bg-green-500 rounded-full border-4 border-white flex items-center justify-center">
                                    <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-white rounded-full"></div>
                                </div>
                            </div>

                            <div class="text-white text-center sm:text-left pb-0 sm:pb-4 flex-1 min-w-0">
                                <div class="flex flex-col sm:flex-row items-center sm:items-center gap-2 sm:gap-3 mb-2">
                                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold truncate max-w-full">
                                        {{ business.name }}</h1>
                                    <!-- <VerifiedBadge :size="32" color="#3B82F6" /> -->
                                </div>
                                <p class="text-indigo-200 text-base sm:text-lg font-medium break-words">
                                    {{ business.email }}</p>
                                <p class="text-white/80 text-sm sm:text-base break-words">{{ business.mobile }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 -mt-4 sm:-mt-8 relative z-20">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
                    <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 border border-gray-200/50">
                        <div class="flex items-center justify-between">
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-gray-600">Date Added</p>
                                <p class="text-lg sm:text-2xl font-bold text-gray-900 truncate">
                                    {{ new Date(business.created_at).toDateString() }}</p>
                            </div>
                            <div
                                class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0 ml-3">
                                <CalendarDays class="w-5 h-5 sm:w-6 sm:h-6 text-primary" />
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 border border-gray-200/50">
                        <div class="flex items-center justify-between">
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-gray-600">Signboards</p>
                                <p class="text-lg sm:text-2xl font-bold text-gray-900">
                                    {{ business.signboards.length }}</p>
                            </div>
                            <div
                                class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0 ml-3">
                                <MapPin class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" />
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-xl shadow-lg p-4 sm:p-6 border border-gray-200/50 sm:col-span-2 lg:col-span-1">
                        <div class="flex items-center justify-between">
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-gray-600">Rating</p>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="text-lg sm:text-2xl font-bold text-gray-900">
                                        {{ business.average_rating }}</p>
                                    <div class="flex text-yellow-400">
                                        <Star
                                            v-for="i in 5"
                                            :key="i"
                                            class="w-4 h-4 sm:w-5 sm:h-5"
                                            :class="{ 'fill-current': i <= Math.floor(Number(business.average_rating)), 'text-gray-300': i > Math.floor(Number(business.average_rating)) }"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div
                                class="w-10 h-10 sm:w-12 sm:h-12 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0 ml-3">
                                <Trophy class="w-5 h-5 sm:w-6 sm:h-6 text-primary" />
                            </div>
                        </div>
                    </div>
                </div>


                <div class="bg-white rounded-xl shadow-lg p-6 sm:p-8 mb-6 sm:mb-8 border border-gray-200/50">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4">About</h2>
                    <p class="text-gray-600 leading-relaxed text-base sm:text-lg break-words">
                        {{ business.description }}
                    </p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 sm:p-8 mb-6 sm:mb-8 border border-gray-200/50">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 w-full lg:w-auto">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 flex-shrink-0">Connect with
                                us:</h3>
                            <div class="flex gap-3 flex-wrap">
                                <a v-if="business.facebook" :href="business.facebook" target="_blank"
                                   class="w-10 h-10 bg-blue-100 text-blue-400 rounded-lg flex items-center justify-center hover:bg-blue-200 transition-colors">
                                    <i class="fab fa-facebook text-lg"></i>
                                </a>
                                <a v-if="business.x" :href="business.x" target="_blank"
                                   class="w-10 h-10 bg-gray-100 text-gray-900 rounded-lg flex items-center justify-center hover:bg-gray-200 transition-colors">
                                    <i class="fab fa-x-twitter text-lg"></i>
                                </a>
                                <a v-if="business.instagram" :href="business.instagram" target="_blank"
                                   class="w-10 h-10 bg-pink-100 text-pink-600 rounded-lg flex items-center justify-center hover:bg-pink-200 transition-colors">
                                    <i class="fab fa-instagram text-lg"></i>
                                </a>
                                <a v-if="business.linkedin" :href="business.linkedin" target="_blank"
                                   class="w-10 h-10 bg-blue-100 text-blue-400 rounded-lg flex items-center justify-center hover:bg-blue-200 transition-colors">
                                    <i class="fab fa-linkedin-in text-lg"></i>
                                </a>
                            </div>
                        </div>


                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 w-full sm:w-auto">
                            <MyBusinessUpdate :business="business" @updated="$inertia.reload()">
                                <Button
                                    class="bg-primary hover:bg-primary/90 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all text-sm sm:text-base">
                                    <Edit3 class="w-4 h-4 mr-2" />
                                    Edit Business
                                </Button>
                            </MyBusinessUpdate>

                            <Button variant="destructive" @click="showDialog = true"
                                    class="bg-red-500 text-white hover:bg-red-600 px-4 sm:px-6 py-2 sm:py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all text-sm sm:text-base"
                            >
                                <Trash2 class="w-4 h-4 mr-2" />
                                <span v-if="!isDeleting">Close Business</span>
                                <span v-else>Closing...</span>
                            </Button>
                        </div>
                    </div>
                </div>


                <div class="bg-white rounded-xl shadow-lg p-6 sm:p-8 mb-6 sm:mb-8 border border-gray-200/50">
                    <div
                        class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 sm:gap-6 mb-6">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Signboards</h2>
                        <Link :href="route('my-signboards.create')"
                              :data="{ business: business.id }"
                              class="flex items-center gap-x-2 bg-primary hover:bg-primary/90 backdrop-blur-sm text-white border  border-white/30 px-4 sm:px-6 py-2 sm:py-3 rounded-xl transition-all   duration-200 hover:scale-105 text-sm sm:text-base w-full sm:w-auto justify-center sm:justify-start">
                            <PlusIcon class="w-4 h-4 sm:w-5 sm:h-5" />
                            <span>Add Signboard</span>
                        </Link>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
                        <div v-if="business.signboards.length"
                             v-for="signboard in props.business.signboards" :key="signboard.id"
                             class="group relative overflow-hidden rounded-lg border  border-gray-200 hover:border-indigo-300 transition-all duration-300 hover:shadow-lg">
                            <div
                                class="aspect-video bg-gradient-to-br from-indigo-50 to-purple-50 p-4 sm:p-6 flex items-center justify-center">
                                <div class="text-center">
                                    <MapPin class="w-6 h-6 sm:w-8 sm:h-8 text-primary mx-auto mb-2" />
                                    <p class="text-xs sm:text-sm font-medium text-gray-600"> {{ signboard.region.name
                                        }}</p>
                                </div>
                            </div>
                            <div class="p-3 sm:p-4">
                                <h3 class="font-semibold text-gray-900 mb-1 text-sm sm:text-base truncate">Location
                                    {{ signboard.town }}</h3>
                                <p class="text-xs sm:text-sm text-gray-500 mb-3">Active since
                                    {{ new Date(signboard.created_at).toDateString() }}</p>
                                <div class="flex justify-between items-center"><span class="inline-flex items-center
                                 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"><div
                                    class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></div>Active</span>
                                    <div class="flex gap-1 sm:gap-2">
                                        <Link variant="ghost"
                                              :href="route('my-signboards.edit', signboard.slug)"
                                              size="sm" class="text-gray-500 hover:text-primary p-1 sm:p-2">
                                            <Edit3 class="w-3 h-3 sm:w-4 sm:h-4" />
                                        </Link>
                                        <Link :href="route('my-signboards.show', signboard.slug)"
                                              variant="ghost" size="sm"
                                              class="text-gray-500 hover:text-primary p-1 sm:p-2">
                                            <EyeIcon class="w-3 h-3 sm:w-4 sm:h-4" />
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-else
                             class="col-span-full flex flex-col items-center justify-center text-center text-gray-600 px-4 py-8">
                            <Briefcase class="w-20 h-20 mb-6 text-primary opacity-80" />
                            <h3 class="text-2xl font-semibold mb-2 text-gray-700">No Signboard yet</h3>
                            <p class="text-base text-gray-500 mb-6">
                                You haven’t added any signboard to this business yet. Click below to get started.
                            </p>
                            <Button as-child>
                                <Link :href="route('my-signboards.create')"
                                      :data="{ business: business.id }"
                                      class="flex items-center gap-x-2 bg-primary duration-200 hover:scale-105 text-sm sm:text-base w-full sm:w-auto justify-center sm:justify-start">
                                    <PlusIcon class="w-4 h-4 sm:w-5 sm:h-5" />
                                    <span>Add Signboard</span>
                                </Link>
                            </Button>
                        </div>
                    </div>
                </div>

            </div>
            <ConfirmDialogue
                v-model:open="showDialog"
                :loading="isDeleting"
                title="Close this business?"
                description="This will permanently remove the business and all its signboards. Are you sure?"
                confirmText="Yes, Close it"
                cancelText="Cancel"
                @confirm="closeBusiness"
            />


        </div>
    </Layout>
</template>
