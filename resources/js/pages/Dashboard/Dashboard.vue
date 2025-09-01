<script setup lang="ts">
import Layout from '@/layouts/Layout.vue';
import { Head, Link } from '@inertiajs/vue3';
import StatsCard from '@/pages/Dashboard/blocks/StatsCard.vue';
import {
    Eye,
    PlusIcon,
    Rocket,
    ChartBarIcon,
    Building2,
    Briefcase,
    ShoppingBag,
    Wrench,

    TextQuote,
    DollarSign,
    Star,
    MapPin,
    Phone,
    CreditCard,
    ArrowUpRight,
} from 'lucide-vue-next';
import type { SignboardI, PromotionI, BusinessI, ProductI, ServiceI, JobI, User } from '@/types';
import { onMounted } from 'vue';




const props = defineProps<{
    user: User
    promotions: PromotionI[];
    signboards: SignboardI[];
   // businesses: BusinessI[];
    jobs: JobI[];
    services: ServiceI[];
    products: ProductI[];
}>();

onMounted(()=> {
    console.log(props.signboards)
})

const totalViews =
    props.signboards.reduce((sum, s) => sum + (s.views_count || 0), 0) +
    props.services.reduce((sum, s) => sum + (s.views_count || 0), 0) +
    props.products.reduce((sum, s) => sum + (s.views_count || 0), 0) +
    props.jobs.reduce((sum, s) => sum + (s.views_count || 0), 0)
;

//const averageRating = props.businesses.reduce((sum, b) => sum + b.average_rating, 0) / props.businesses.length || 0;

const totalReviews = props.products.reduce((sum, product) => sum + product.reviews_count, 0)
    + props.signboards.reduce((sum, signboard) => sum + signboard.reviews_count, 0)


const quickStats = [
    { label: 'Referral Points', value: props.user.points,  icon: DollarSign, color: 'purple' },
    { label: 'Active Services', value: props.services.length, icon: Building2, color: 'green' },
   // { label: 'Avg Rating', value: averageRating.toFixed(1), icon: Star, color: 'yellow' },
    { label: 'Total Reviews', value: totalReviews.toFixed(1), icon: TextQuote, color: 'blue' }
];

</script>

<template>
    <Layout>
        <Head title="Dashboard - Overview" />
        <div class="min-h-screen w-full bg-gradient-to-br from-gray-50 via-blue-50/30 to-indigo-50/40">
            <div class="bg-white/80 backdrop-blur-sm shadow-sm border-b border-white/20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center py-6">
                        <div>
                            <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                                Dashboard Overview
                            </h1>
                            <p class="text-sm text-gray-600 mt-1">
                                Comprehensive view of your platform's performance and metrics
                            </p>
                        </div>

                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div v-for="stat in quickStats" :key="stat.label"
                         class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-sm border border-white/30 p-6 hover:shadow-lg transition-all duration-300 hover:scale-105">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">{{ stat.label }}</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1">{{ stat.value }}</p>
                                <p :class="`text-sm font-medium mt-1 text-${stat.color}-600`">{{ stat.change }}</p>
                            </div>
                            <div :class="`w-12 h-12 bg-${stat.color}-100 rounded-xl flex items-center justify-center`">
                                <component :is="stat.icon" :class="`w-6 h-6 text-${stat.color}-600`" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">

                    <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <StatsCard
                            title="Signboards"
                            :value="props.signboards.length"
                            icon-bg="bg-blue-500 text-white"
                            :icon="ChartBarIcon"
                            subtitle="Active locations"
                        />
                        <StatsCard
                            title="Jobs Posted"
                            :value="props.jobs?.length || 0"
                            icon-bg="bg-secondary text-white"
                            :icon="Briefcase"
                            subtitle="Open positions"
                        />
                        <StatsCard
                            title="Services"
                            :value="props.services?.length || 0"
                            icon-bg="bg-green-500 text-white"
                            :icon="Wrench"
                            subtitle="Available services"
                        />
                        <StatsCard
                            title="Products"
                            :value="props.products?.length || 0"
                            icon-bg="bg-orange-500 text-white"
                            :icon="ShoppingBag"
                            subtitle="In catalog"
                        />
                        <StatsCard
                            title="Active Promotions"
                            :value="props.promotions.filter(p => p.is_active).length"
                            icon-bg="bg-red-500 text-white"
                            :icon="Rocket"
                            subtitle="Running ads"
                        />
                        <StatsCard
                            title="Total Views"
                            :value="totalViews"
                            icon-bg="bg-indigo-500 text-white"
                            :icon="Eye"
                            subtitle="Platform views"
                        />
                    </div>


                    <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-sm border border-white/30">
                        <div class="p-6 border-b border-gray-200/50">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-gray-900">Recent Products</h2>
                                <Link :href="route('my-products.index')"
                                      class="text-blue-600 hover:text-blue-700 font-medium text-sm flex items-center gap-1">
                                    View All <ArrowUpRight class="w-4 h-4" />
                                </Link>
                            </div>
                        </div>
                        <div class="divide-y divide-gray-200/50">
                            <div v-if="props.products.length">
                                <div v-for="product in props.products.slice(0, 2)" :key="product.id"
                                     class="p-6 hover:bg-gray-50/50 transition-colors">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h3 class="text-base font-medium text-gray-900">{{ product.name }}</h3>
                                            <div class="flex items-center space-x-1 mt-1">
                                                <CreditCard class="w-4 h-4 text-gray-400" />
                                                <p class="text-sm text-gray-600">{{ product.price }}</p>
                                            </div>
                                            <div class="flex items-center mt-3 space-x-4">
                                                <span class="text-sm text-blue-600 font-medium">
                                                    {{ product.views_count || 0 }} Views
                                                </span>
                                                <span class="text-sm text-green-600 font-medium">
                                                    {{ product.reviews_count || 0 }} Reviews
                                                </span>
                                            </div>
                                        </div>
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="p-6 text-center">
                                <ChartBarIcon class="w-12 h-12 text-gray-300 mx-auto mb-4" />
                                <h3 class="text-lg font-medium text-gray-700 mb-2">No product yet</h3>
                                <p class="text-sm text-gray-500">Products will appear here once created</p>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                    <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-sm border border-white/30">
                        <div class="p-6 border-b border-gray-200/50">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-gray-900">Top Services</h2>
                                <Link :href="route('my-services.index')" class="text-blue-600 hover:text-blue-700 font-medium text-sm flex items-center gap-1">
                                    View All <ArrowUpRight class="w-4 h-4" />
                                </Link>
                            </div>
                        </div>
                        <div class="divide-y divide-gray-200/50">
                            <div v-if="props.services.length">
                                <div v-for="service in props.services.slice(0, 5)" :key="service.id"
                                     class="p-6 hover:bg-gray-50/50 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center text-white font-bold">
                                                {{ service.initials }}
                                            </div>
                                            <div>
                                                <h3 class="text-base font-medium text-gray-900">{{ service.title }}</h3>
                                                <div class="flex items-center space-x-3 mt-1">
                                                    <div class="flex items-center space-x-1">
                                                        <Star class="w-4 h-4 text-yellow-400 fill-current" />
                                                        <span class="text-sm text-gray-600">{{ service.town }}</span>
                                                    </div>
                                                    <div class="flex items-center space-x-1">
                                                        <Phone class="w-4 h-4 text-gray-400" />
                                                        <span class="text-sm text-gray-600">{{ service.category?.name }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div v-else class="p-6 text-center">
                                <Building2 class="w-12 h-12 text-gray-300 mx-auto mb-4" />
                                <h3 class="text-lg font-medium text-gray-700 mb-2">No Services yet</h3>
                                <p class="text-sm text-gray-500">Servicesss will appear here once registered</p>
                            </div>
                        </div>
                    </div>


                    <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-sm border border-white/30">
                        <div class="p-6 border-b border-gray-200/50">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-gray-900">Recent Signboards</h2>
                                <Link :href="route('my-signboards.index')"
                                      class="text-blue-600 hover:text-blue-700 font-medium text-sm flex items-center gap-1">
                                    View All <ArrowUpRight class="w-4 h-4" />
                                </Link>
                            </div>
                        </div>
                        <div class="divide-y divide-gray-200/50">
                            <div v-if="props.signboards.length">
                                <div v-for="signboard in props.signboards.slice(0, 5)" :key="signboard.id"
                                     class="p-6 hover:bg-gray-50/50 transition-colors">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h3 class="text-base font-medium text-gray-900">{{ signboard.service.title }}</h3>
                                            <div class="flex items-center space-x-1 mt-1">
                                                <MapPin class="w-4 h-4 text-gray-400" />
                                                <p class="text-sm text-gray-600">{{ signboard.landmark }}</p>
                                            </div>
                                            <div class="flex items-center mt-3 space-x-4">
                                                <span class="text-sm text-blue-600 font-medium">
                                                    {{ signboard.views_count || 0 }} Views
                                                </span>
                                                <span class="text-sm text-green-600 font-medium">
                                                    {{ signboard.reviews_count || 0 }} Reviews
                                                </span>
                                            </div>
                                        </div>
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="p-6 text-center">
                                <ChartBarIcon class="w-12 h-12 text-gray-300 mx-auto mb-4" />
                                <h3 class="text-lg font-medium text-gray-700 mb-2">No signboards yet</h3>
                                <p class="text-sm text-gray-500">Signboards will appear here once created</p>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="mt-8 grid grid-cols-2 md:grid-cols-4 gap-4">
                    <Link :href="route('my-signboards.create')"
                          class="flex items-center justify-center gap-x-2 bg-gradient-to-r from-blue-500
                          to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-xl
                          transition-all duration-200 hover:scale-105 shadow-lg hover:shadow-xl">
                        <PlusIcon class="w-5 h-5" />
                        <span>Add Signboard</span>
                    </Link>

                    <Link :href="route('my-services.create')"
                          class="flex items-center justify-center gap-x-2 bg-gradient-to-r from-green-500 to-green-600
                          hover:from-green-600 hover:to-green-700 text-white px-6 py-3 rounded-xl transition-all
                          duration-200 hover:scale-105 shadow-lg hover:shadow-xl">
                        <Rocket class="w-5 h-5" />
                        <span>Create Service</span>
                    </Link>

                    <Link :href="route('my-jobs.create')"
                          class="flex items-center justify-center gap-x-2 bg-secondary hover:secondary-400
                          text-white px-6 py-3 rounded-xl transition-all duration-200 hover:scale-105 shadow-lg hover:shadow-xl">
                        <Briefcase class="w-5 h-5" />
                        <span>Post Job</span>
                    </Link>

                    <Link :href="route('my-products.create')"
                          class="flex items-center justify-center gap-x-2 bg-primary hover:primary-400 text-white px-6 py-3
                           rounded-xl transition-all duration-200 hover:scale-105 shadow-lg hover:shadow-xl">
                        <ShoppingBag class="w-5 h-5" />
                        <span>Add Product</span>
                    </Link>
                </div>
            </div>
        </div>
    </Layout>
</template>

<style scoped>


</style>
