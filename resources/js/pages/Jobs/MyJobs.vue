<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import Layout from '@/layouts/Layout.vue'
import Paginator from '@/components/helpers/Paginator.vue'
import {
    PlusIcon,
    Eye,
    Star,
    Clock,
    Briefcase,
    CheckCircle,
    Pause,
    AlertCircle,
} from 'lucide-vue-next'
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select'

import { ref } from 'vue'
import { Button } from '@/components/ui/button'
import { PaginatedDataI, JobI } from '@/types'

const props = defineProps<{
    jobs: PaginatedDataI<JobI>
    sort?: string
    status?: string
    search?: string
}>()

const sortOrder = ref(props.sort || 'desc')
const statusFilter = ref(props.status || 'all')
const searchTerm = ref(props.search || '')

const applyFilters = () => {
    router.get(route('my-jobs.index'), {
        search: searchTerm.value,
        status: statusFilter.value,
        sort: sortOrder.value,
    }, {
        preserveScroll: true,
        preserveState: true,
    })
}

const resetFilters = () => {
    searchTerm.value = ''
    statusFilter.value = 'all'
    sortOrder.value = 'desc'

    router.get(route('my-jobs.index'), {}, {
        preserveScroll: true,
        preserveState: true,
    })
}
const goToPage = (page) => {
    router.get(route('my-jobs.index'), {
        page,
        sort: sortOrder.value,
        status: statusFilter.value,
        search: searchTerm.value
    }, {
        preserveScroll: true,
        preserveState: true,
    });
}

const getStatusColor = (status: string) => {
    switch (status) {
        case 'active':
            return 'bg-green-100 text-green-800 border-green-200';
        case 'paused':
            return 'bg-yellow-100 text-yellow-800 border-yellow-200';
        case 'expired':
            return 'bg-red-100 text-red-800 border-red-200';
        default:
            return 'bg-gray-100 text-gray-800 border-gray-200';
    }
};

const getStatusIcon = (status: string) => {
    switch (status) {
        case 'active':
            return CheckCircle;
        case 'paused':
            return Pause;
        case 'inactive':
            return AlertCircle;
        default:
            return CheckCircle;
    }
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
    });
};

const getExpiryStatus = (dateString: string) => {
    const date = new Date(dateString);
    const now = new Date();
    const diffTime = date.getTime() - now.getTime();
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    if (diffDays < 0) return { text: 'Expired', urgent: true };
    if (diffDays <= 3) return { text: `${diffDays} days left`, urgent: true };
    if (diffDays <= 7) return { text: `${diffDays} days left`, urgent: false };
    return { text: `Expires ${formatDate(dateString)}`, urgent: false };
};


</script>

<template>
    <Head title="My Job Listings" />

    <Layout>
        <div class=" fixed bottom-8 left-8 z-50">
            <Link :href="route('my-jobs.create')">
                <Button class="bg-secondary
                text-white font-semibold px-6 py-4 rounded-full shadow-2xl transform hover:scale-110 transition-all duration-300 ease-out flex items-center gap-3 group">
                    <PlusIcon class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" />
                    <span class="hidden lg:block">Post Job</span>
                </Button>
            </Link>
        </div>

        <div class="min-h-screen w-full bg-gradient-to-br from-slate-50 via-white to-blue-50">
            <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900">
                <div class="absolute inset-0 bg-black opacity-20"></div>
                <div class="relative px-6 py-16 text-center">
                    <div class="max-w-4xl mx-auto">
                        <h1 class="text-4xl md:text-6xl font-black text-white mb-4 tracking-tight">
                            My Job <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-orange-500">Listings</span>
                        </h1>
                        <p class="text-xl text-slate-300 max-w-2xl mx-auto leading-relaxed">
                            Manage your job postings and connect with talented professionals
                        </p>
                    </div>
                </div>
            </div>



            <div class="px-6 py-8 bg-white border-b border-slate-200">
                <div class="max-w-7xl mx-auto">
                    <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">
<!--                        <div class="flex-1 max-w-md">-->
<!--                            <div class="relative">-->
<!--                                <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400 w-5 h-5" />-->
<!--                                <Input-->
<!--                                    v-model="searchTerm"-->
<!--                                    type="text"-->
<!--                                    placeholder="Search your job listings..."-->
<!--                                    class="pl-10 pr-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"-->
<!--                                />-->
<!--                            </div>-->
<!--                        </div>-->
                        <div class="flex gap-4 items-center">
                            <Select v-model="statusFilter"  >
                                <SelectTrigger class="w-40">
                                    <SelectValue placeholder="Filter by Status" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem value="all">All Status</SelectItem>
                                        <SelectItem value="active">Active</SelectItem>
                                        <SelectItem value="paused">Paused</SelectItem>
                                        <SelectItem value="inactive">Expired</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>

                            <Select v-model="sortOrder"  >
                                <SelectTrigger class="w-32">
                                    <SelectValue placeholder="Sort By" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem value="desc">Latest</SelectItem>
                                        <SelectItem value="asc">Oldest</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </div>

                        <div class="flex gap-2">
                            <Button @click="applyFilters" class="h-10">Apply Filters</Button>
                            <Button variant="secondary" @click="resetFilters" class="h-10">Reset</Button>
                        </div>

                    </div>
                </div>
            </div>

            <div v-if="jobs.data?.length" class="px-6 py-4">
                <div class="max-w-7xl mx-auto">
                    <div class="text-sm text-slate-600">
                        Showing {{ jobs.data?.length }} of {{ jobs.total }} job listings
                    </div>
                </div>
            </div>
            <div v-if="jobs.data?.length" class="px-6 py-8 w-full">
                <div class="max-w-7xl mx-auto">
                    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                        <div v-for="job in jobs.data"
                             :key="job.id"
                             class="bg-white rounded-2xl shadow-sm hover:shadow-xl border border-slate-100
                             overflow-hidden transform hover:-translate-y-1 transition-all duration-300 ease-out">

                            <div class="p-6 border-b border-slate-100">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                        <div class="w-12 h-12 rounded-xl bg-primary flex items-center justify-center text-white font-bold text-lg shadow-lg flex-shrink-0">
                                            {{ job.title?.charAt(0) || 'J' }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <h3 class="font-bold text-slate-900 text-lg truncate hover:text-blue-600 transition-colors duration-200">
                                                    {{ job.title }}
                                                </h3>
                                                <Star   class="w-4 h-4 text-yellow-500 fill-current flex-shrink-0" />
                                            </div>
                                            <p class="text-sm text-slate-600 truncate capitalize">{{ job.work_mode }}</p>
                                        </div>
                                    </div>

                                </div>

                                <div class="flex items-center gap-2 mb-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ job.job_type }}
                                    </span>
                                </div>
                            </div>

                            <div class="p-6 bg-slate-50">

                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center gap-1 text-slate-500">
                                        <span :class="`px-3 py-1 rounded-full text-xs font-semibold border flex items-center gap-1 ${getStatusColor(job.status)}`">
                                        <component :is="getStatusIcon(job.status)" class="w-3 h-3" />
                                        {{ job.status.charAt(0).toUpperCase() + job.status.slice(1) }}
                                    </span>
                                    </div>
                                    <div :class="`flex items-center gap-1 ${getExpiryStatus(job.deadline).urgent ? 'text-red-600' : 'text-slate-500'}`">
                                        <Clock class="w-4 h-4" />
                                        <span class="font-medium">{{ getExpiryStatus(job.deadline).text }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <Link :href="route('my-jobs.show', job.slug)">
                                    <Button class="w-full bg-primary hover:from-slate-800
                                     hover:to-slate-900 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200
                                     transform hover:scale-[1.02] flex items-center justify-center gap-2">
                                        <Eye class="w-4 h-4" />
                                        Manage Job
                                    </Button>
                                </Link>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 flex justify-center">
                        <Paginator v-if="jobs.total > jobs.per_page"
                                   :total="jobs.total"
                                   :per-page="jobs.per_page"
                                   :current-page="jobs.current_page"
                                   @page-change="goToPage"
                                   class="bg-white rounded-xl shadow-sm border border-slate-200 p-2"
                        />
                    </div>
                </div>
            </div>

            <div v-else class="min-h-[60vh] flex flex-col items-center justify-center text-center px-6">
                <div class="bg-primary w-24 h-24 rounded-full flex items-center justify-center mb-8 shadow-2xl">
                    <Briefcase class="w-12 h-12 text-white" />
                </div>
                <h3 class="text-3xl font-bold text-slate-800 mb-4">
                    {{ searchTerm || statusFilter !== 'all' ? 'No Jobs Found' : 'No Jobs Posted Yet' }}
                </h3>
                <p class="text-lg text-slate-600 max-w-md leading-relaxed mb-8">
                    {{ searchTerm || statusFilter !== 'all'
                    ? 'Try adjusting your search criteria or filters to find your job listings.'
                    : 'Start posting job opportunities and connect with talented professionals looking for their next role.'
                    }}
                </p>
                <Link :href="route('my-jobs.create')">
                    <Button class="bg-primary hover:to-purple-700 text-white font-semibold px-8 py-4 rounded-xl
                     shadow-lg transform hover:scale-105 transition-all duration-200 flex items-center gap-3">
                        <PlusIcon class="w-5 h-5" />
                        <span>Post Your First Job</span>
                    </Button>
                </Link>
            </div>
        </div>
    </Layout>
</template>

<style scoped>

</style>
