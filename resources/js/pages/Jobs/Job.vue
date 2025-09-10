<script setup lang="ts">
import Layout from '@/layouts/GuestLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { JobI } from '@/types';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Briefcase, Clock, MapPin } from 'lucide-vue-next';
import { Badge } from '@/components/ui/badge';
import { ucFirst } from '@/lib/helpers';
import { Button } from '@/components/ui/button';
import JobCardV1 from '@/components/jobs/JobCardV1.vue';
import ShareToSocials from '@/components/ShareToSocials.vue';

type Props = {
    job: JobI,
    relatedJobs: JobI[]
}

const props = defineProps<Props>()

</script>

<template>
    <Head :title="job.title"/>
    <Layout>
        <div class="w-full">
            <div class="w-full bg-secondary py-15 text-white px-3">
                <div class="flex flex-wrap items-center gap-12 max-w-[1000px] mx-auto">
                    <Avatar class="h-15 w-15">
                        <AvatarImage :src="job.company_logo"/>
                        <AvatarFallback>{{ job.company_name.charAt(0) + job.company_name.charAt(1) }}</AvatarFallback>
                    </Avatar>
                    <div class="flex gap-2.5 flex-col">
                        <div class="text-2xl font-semibold">{{ job.title }}</div>
                        <div class="italic font-light">{{ job.company_name }}</div>
                        <div class="flex gap-5 items-center">
                            <div class="flex gap-1 items-center text-primary">
                                <Briefcase :size="15" />
                                <div class="text-sm">{{ ucFirst(job.work_mode) }}</div>
                            </div>
                            <div class="flex gap-1 items-center">
                                <Clock :size="15"/>
                                <div class="text-sm">{{ job.created_at }}</div>
                            </div>
                        </div>
                        <Badge v-show="job.region_id" variant="success" class="p-2">
                            <MapPin />
                            <div>{{ job.region.name }}</div>
                        </Badge>
                    </div>
                    <div class="lg:ms-auto mx-auto md:mx-0 md:w-auto w-full" v-if="job.application_link">
                        <a :href="job.application_link" target="_blank">
                            <Button class="px-15 py-7 rounded-2xl w-full">
                                Apply Now
                            </Button>
                        </a>
                    </div>
                </div>
            </div>
            <div class="max-w-[1200px] mx-auto mt-10 flex flex-col md:flex-row gap-10 relative">
                <div class="w-full md:w-3/4 flex flex-col gap-10">
                    <div v-html="job.description"></div>
                    <div v-if="job.how_to_apply">
                        <div class="font-bold">How to apply: </div>
                        <div v-html="job.how_to_apply"></div>
                    </div>
                </div>
                <div class="w-full md:w-1/4">
                    <div class="sticky flex flex-col gap-5 top-0 bg-[#E8F0FF] rounded-3xl p-5">
                        <div class="flex flex-col gap-2">
                            <div class="border-s-primary border-s-4 px-2 font-semibold">Categories</div>
                            <div class="flex flex-wrap gap-2">
                                <Badge v-for="category in job.categories" :key="`cat-${category.id}`" class="p-1.5" variant="secondary">
                                    {{ category.name }}
                                </Badge>
                            </div>
                        </div>
                        <div class="flex flex-col gap-2">
                            <div class="border-s-primary border-s-4 px-2 font-semibold">Type</div>
                            <Badge class="p-1.5" variant="success">{{ ucFirst(job.job_type) }}</Badge>
                        </div>
                        <div class="flex flex-col gap-2">
                            <div class="border-s-primary border-s-4 px-2 font-semibold">Work mode</div>
                            <Badge class="p-1.5" variant="success">{{ ucFirst(job.work_mode) }}</Badge>
                        </div>
                        <div v-if="job.town" class="flex flex-col gap-2">
                            <div class="border-s-primary border-s-4 px-2 font-semibold">Town</div>
                            <Badge class="p-1.5" variant="success">{{ ucFirst(job.town) }}</Badge>
                        </div>
                        <div v-if="job.salary" class="flex flex-col gap-2">
                            <div class="border-s-primary border-s-4 px-2 font-semibold">Salary</div>
                            <Badge class="p-1.5" variant="success">{{ job.salary }}</Badge>
                        </div>
                        <div class="flex flex-col gap-2">
                            <div class="border-s-primary border-s-4 px-2 font-semibold animate-pulse">Deadline</div>
                            <Badge class="p-1.5" variant="destructive">{{ job.deadline }}</Badge>
                        </div>
                        <div class="flex flex-col gap-2">
                            <ShareToSocials class="my-0"/>
                        </div>
                        <div >
                            <a v-if="job.application_link" :href="job.application_link ?? ''" target="_blank">
                                <Button class="px-15 py-5 rounded-2xl w-full">
                                    Apply Now
                                </Button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-10 max-w-[1200px] mx-auto">
                <div class="text-xl mb-5 font-semibold">Related Jobs</div>
                <div class="grid items-stretch gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3">
                    <JobCardV1
                        v-for="relatedJob in relatedJobs"
                        :job="relatedJob"
                        :key="`related-job-${relatedJob.id}`"
                    />
                </div>
            </div>
        </div>
    </Layout>
</template>

<style scoped>

</style>
