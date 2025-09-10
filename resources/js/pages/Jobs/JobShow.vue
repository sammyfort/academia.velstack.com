<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import Layout from '@/layouts/GuestLayout.vue'

import {
    ArrowLeft,
    Edit,
    Trash2,
    Eye,
    MapPin,
    Building2,
    Share2,
    Award,
    PlusIcon,
    Calendar,
    DollarSign,
    Briefcase,
    Globe,
    ExternalLink,
    Mail
} from 'lucide-vue-next'
import { Button } from '@/components/ui/button'
import { JobI, PromotionPlanI } from '@/types';
import { ref, onMounted } from 'vue'
import ConfirmDialogue from '@/components/helpers/ConfirmDialogue.vue';
import { toastError, toastSuccess } from '@/lib/helpers';
import TextEditor from '@/components/forms/TextEditor.vue';
import PaymentHistory from '@/components/promotions/PaymentHistory.vue';
import { PromotableE } from '@/lib/enums';
import PromoteNow from '@/components/promotions/PromoteNow.vue';

const props = defineProps<{
    job: JobI;
    plans?: PromotionPlanI;
}>()

onMounted(()=> {
   // console.log(props.job)

})

const isDeleting = ref(false)

const handleDelete = () => {


    isDeleting.value = true
    router.delete(route('my-jobs.destroy', props.job.id), {
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
            title: props.job.title,
            text: props.job.summary,
            url: window.location.href
        })
    } else {
        navigator.clipboard.writeText(window.location.href)
        toastSuccess('Link copied to clipboard!');
    }
}

const getStatusColor = (status: string) => {
    switch (status.toLowerCase()) {
        case 'active':
            return 'bg-green-100 text-green-800'
        case 'expired':
            return 'bg-red-100 text-red-800'
        case 'draft':
            return 'bg-gray-100 text-gray-800'
        case 'paused':
            return 'bg-yellow-100 text-yellow-800'
        default:
            return 'bg-gray-100 text-gray-800'
    }
}

const getJobTypeColor = (jobType: string) => {
    switch (jobType.toLowerCase()) {
        case 'full-time':
            return 'bg-blue-100 text-blue-800'
        case 'part-time':
            return 'bg-purple-100 text-purple-800'
        case 'contract':
            return 'bg-orange-100 text-orange-800'
        case 'internship':
            return 'bg-pink-100 text-pink-800'
        default:
            return 'bg-gray-100 text-gray-800'
    }
}

const getWorkModeColor = (workMode: string) => {
    switch (workMode.toLowerCase()) {
        case 'remote':
            return 'bg-green-100 text-green-800'
        case 'hybrid':
            return 'bg-indigo-100 text-indigo-800'
        case 'on-site':
            return 'bg-slate-100 text-slate-800'
        default:
            return 'bg-gray-100 text-gray-800'
    }
}

const formatDeadline = (deadline: string) => {
    if (!deadline) return 'No deadline';

    const date = new Date(deadline)
    const now = new Date()
    const daysLeft = Math.ceil((date.getTime() - now.getTime()) / (1000 * 60 * 60 * 24))

    if (daysLeft < 0) {
        return 'Expired'
    } else if (daysLeft === 0) {
        return 'Today'
    } else if (daysLeft === 1) {
        return '1 day left'
    } else {
        return `${daysLeft} days left`
    }
}
</script>

<template>
    <Head :title="job.title" />

    <Layout>

        <div v-if="job?.id" class="hidden md:block fixed bottom-8 right-8 z-50 space-y-4">
            <Link :href="route('my-jobs.edit', job.slug)">
                <Button class="bg-primary hover:bg-primary-700 text-white font-semibold p-4 rounded-full
                shadow-2xl transform hover:scale-110 transition-all duration-300 ease-out">
                    <Edit class="w-5 h-5" />
                </Button>
            </Link>
            <Button
                @click="handleShare"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold p-4 rounded-full shadow-2xl
                transform hover:scale-110 transition-all duration-300 ease-out">
                <Share2 class="w-5 h-5" />
            </Button>
        </div>



        <div class="min-h-screen w-full bg-gradient-to-br from-slate-50 via-white to-blue-50">
            <div class="relative overflow-hidden bg-secondary">
                <div class="absolute inset-0 bg-black opacity-30"></div>
                <div class="relative px-6 py-8">
                    <div class="max-w-6xl mx-auto">
                        <Link :href="route('my-jobs.index')"
                              class="inline-flex items-center me-6 gap-2 text-white/80 hover:text-white mb-6 transition-colors">
                            <ArrowLeft class="w-5 h-5" />
                            <span>Back to My Jobs</span>
                        </Link>

                        <a
                            target="_blank"
                            :href="route('jobs.show', props.job.slug)"
                            class="inline-flex items-center bg-gray-100 px-3 py-2   border rounded-md gap-2 text-black   mb-6 transition-colors"
                        >
                            <Eye class="h-5 w-5" />
                            <span>View as guest</span>
                        </a>

                        <div class="flex flex-col lg:flex-row gap-8 items-start">
                            <div class="flex-shrink-0">
                                <div class="relative overflow-hidden w-80 h-64 bg-gradient-to-br from-white to-gray-100 rounded-2xl shadow-2xl">
                                    <div class="relative w-full h-full flex items-center justify-center">
                                        <img
                                            v-if="job.company_logo"
                                            :src="job.company_logo"
                                            :alt="job.company_name + ' logo'"
                                            class="max-w-full max-h-full object-contain p-6"
                                        />
                                        <div
                                            v-else
                                            class="w-32 h-32 bg-primary/10 rounded-full flex items-center justify-center"
                                        >
                                            <Building2 class="w-16 h-16 text-primary" />
                                        </div>
                                    </div>

                                    <div class="absolute top-4 left-4 z-20">
                                        <span :class="getJobTypeColor(job.job_type)"
                                              class="backdrop-blur-sm text-sm font-bold px-3 py-2 rounded-full flex items-center gap-1">
                                            <Briefcase class="w-4 h-4" />
                                            {{ job.job_type }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex-grow">
                                <h1 class="text-3xl md:text-5xl font-black text-white mb-4 tracking-tight leading-tight">
                                    {{ job.title }}
                                </h1>

                                <div class="flex items-center gap-4 mb-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white font-bold">
                                            <Building2 class="w-6 h-6" />
                                        </div>
                                        <div>
                                            <p class="font-semibold text-white text-lg">{{ job.company_name }}</p>
                                            <div class="flex items-center gap-1">
                                                <MapPin class="w-4 h-4 text-slate-300" />
                                                <p class="text-slate-300">{{ job.town }}, {{job.region.name }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-center">
                                        <DollarSign class="w-6 h-6 text-green-400 mx-auto mb-2" />
                                        <div class="text-white font-medium text-lg">Salary</div>
                                        <div class="text-slate-300 text-sm">{{ job.salary }}</div>
                                    </div>

                                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-center">
                                        <Globe class="w-6 h-6 text-blue-400 mx-auto mb-2" />
                                        <div class="text-white font-medium text-lg">Work Mode</div>
                                        <div class="text-slate-300 text-sm">{{ job.work_mode  }}</div>
                                    </div>

                                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-center">
                                        <Calendar class="w-6 h-6 text-red-400 mx-auto mb-2" />
                                        <div class="text-white font-medium text-lg">Deadline</div>
                                        <div class="text-slate-300 text-sm">{{ formatDeadline(job.deadline) }}</div>
                                    </div>

                                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-center">
                                        <Eye class="w-6 h-6 text-purple-400 mx-auto mb-2" />
                                        <div class="text-white font-bold text-lg">{{ job.views_count }}</div>
                                        <div class="text-slate-300 text-sm">Views</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="job?.id" class="md:hidden px-6 py-6 bg-white border-b border-slate-200">
                <div class="flex gap-3">
                    <Link :href="route('my-jobs.edit', job.slug)" class="flex-1">
                        <Button class="w-full bg-primary hover:bg-primary-700 text-white font-semibold px-6 py-4 rounded-xl shadow-lg flex items-center justify-center gap-3">
                            <Edit class="w-5 h-5" />
                            <span>Edit Job</span>
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

                            <div v-if="job.summary" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                                <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center gap-3">
                                    <Award class="w-6 h-6 text-primary" />
                                    Job Summary
                                </h2>
                                <div class="prose prose-slate max-w-none">
                                    <p class="text-slate-700 leading-relaxed text-lg">
                                        {{ job.summary }}
                                    </p>
                                </div>
                            </div>

                            <div v-if="job.description" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                                <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center gap-3">
                                    <Briefcase class="w-6 h-6 text-primary" />
                                    Job Description
                                </h2>
                                <div class="prose prose-slate max-w-none">

                                    <TextEditor :model-value="job.description" readonly/>
<!--                                    <div-->
<!--                                        v-html="job.description"-->
<!--                                        class="text-slate-700 leading-relaxed job-description"-->
<!--                                    ></div>-->
                                </div>
                            </div>


                            <div v-if="job.how_to_apply" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                                <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center gap-3">
                                    <Mail class="w-6 h-6 text-primary" />
                                    How to Apply
                                </h2>
                                <div class="prose prose-slate max-w-none">
                                    <div
                                        v-html="job.how_to_apply"
                                        class="text-slate-700 leading-relaxed job-description"
                                    ></div>
                                </div>

                                <div v-if="job.application_link" class="mt-6">
                                    <a
                                        :href="job.application_link"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="inline-flex items-center gap-2 bg-primary hover:bg-primary-700 text-white font-semibold px-6 py-3 rounded-xl transition-all duration-200"
                                    >
                                        <ExternalLink class="w-5 h-5" />
                                        Apply Now
                                    </a>
                                </div>
                            </div>


                            <div v-if="job.categories && job.categories.length > 0" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                                <h2 class="text-2xl font-bold text-slate-900 mb-6">Job Sector</h2>
                                <div class="flex flex-wrap gap-3">
                                    <span
                                        v-for="category in job.categories"
                                        :key="category.id"
                                        class="bg-primary/10 text-primary font-semibold px-4 py-2 rounded-full text-sm"
                                    >
                                        {{ category.name }}
                                    </span>
                                </div>
                            </div>


                        </div>


                        <div class="space-y-6">
                            <div class="lg:col-span-1">



                            </div>
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sticky top-6">
                                <h3 class="text-xl font-bold text-slate-900 mb-6">Job Details</h3>

                                <div class="space-y-4">
                                    <div class="flex items-center justify-between py-3 border-b border-slate-100">
                                        <span class="text-slate-600">Status</span>
                                        <span :class="getStatusColor(job.status)" class="text-sm font-semibold px-3 py-1 rounded-full capitalize">
                                            {{ job.status }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between py-3 border-b border-slate-100">
                                        <span class="text-slate-600">Job Type</span>
                                        <span :class="getJobTypeColor(job.job_type || 'not-specified')" class="text-sm font-semibold px-3 py-1 rounded-full capitalize">
                                            {{ job.job_type}}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between py-3 border-b border-slate-100">
                                        <span class="text-slate-600">Work Mode</span>
                                        <span :class="getWorkModeColor(job.work_mode || 'not-specified')" class="text-sm font-semibold px-3 py-1 rounded-full capitalize">
                                            {{ job.work_mode   }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between py-3 border-b border-slate-100">
                                        <span class="text-slate-600">Salary</span>
                                        <span class="font-semibold text-slate-900">
                                            {{ job.salary || 'Negotiable' }}
                                        </span>
                                    </div>

                                    <div v-if="job.deadline" class="flex items-center justify-between py-3 border-b border-slate-100">
                                        <span class="text-slate-600">Deadline</span>
                                        <span class="font-semibold text-slate-900">
                                            {{ new Date(job.deadline).toLocaleDateString() }}
                                        </span>
                                    </div>

                                    <div v-if="job.created_at" class="flex items-center justify-between py-3 border-b border-slate-100">
                                        <span class="text-slate-600">Posted</span>
                                        <span class="font-semibold text-slate-900">
                                            {{ new Date(job.created_at).toLocaleDateString() }}
                                        </span>
                                    </div>

                                    <div v-if="job.updated_at" class="flex items-center justify-between py-3">
                                        <span class="text-slate-600">Last Updated</span>
                                        <span class="font-semibold text-slate-900">
                                            {{ new Date(job.updated_at).toLocaleDateString() }}
                                        </span>
                                    </div>
                                </div>


                                <div v-if="job?.id" class="mt-8 space-y-3">
                                    <Link :href="route('my-jobs.edit', job.slug)" class="block">
                                        <Button class="w-full bg-primary hover:bg-primary-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 flex items-center justify-center gap-2">
                                            <Edit class="w-4 h-4" />
                                            Edit Job
                                        </Button>
                                    </Link>

                                    <Link :href="route('my-jobs.create')" class="block">
                                        <Button class="w-full bg-primary hover:bg-primary-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 flex items-center justify-center gap-2">
                                            <PlusIcon class="w-4 h-4" />
                                            Post New Job
                                        </Button>
                                    </Link>

                                    <ConfirmDialogue @confirm="handleDelete">
                                        <Button
                                            class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3
                                            px-4 rounded-xl transition-all duration-200 flex items-center justify-center gap-2"
                                            :disabled="isDeleting">
                                            <Trash2 class="w-4 h-4" />
                                            {{ isDeleting ? 'Deleting...' : 'Delete Job' }}
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

<style scoped>


</style>
