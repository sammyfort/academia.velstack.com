<script setup lang="ts">
import Layout from '@/layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';
import { chunkArray } from '@/lib/helpers';
import { JobCategoryI, JobI, PaginatedDataI, RegionI } from '@/types';
import JobCardV1 from '@/components/jobs/JobCardV1.vue';
import { useScrollPagination } from '@/lib/useScrollPagination';
import { onMounted } from 'vue';
import JobCardV1Skeleton from '@/components/skeletons/JobCardV1Skeleton.vue';
import JobFilter from '@/components/jobs/JobFilter.vue';
import BackToTop from '@/components/BackToTop.vue';

type Props = {
    jobsData: PaginatedDataI<JobI>,
    categories: JobCategoryI[],
    regions: RegionI[],
}

const props = defineProps<Props>()

const {
    items: jobs,
    loading: loadingJobs,
    nextPage,
} = useScrollPagination<JobI>({
    initialData: props.jobsData.data as JobI[],
    nextPageUrl: props.jobsData.next_page_url,
    extractResponseData: (page) => page.props.jobsData,
    preserveKeys: ['jobsData'],
});

onMounted(() => {
    nextPage.value = props.jobsData.next_page_url;
    jobs.value = props.jobsData.data as JobI[];
});

</script>

<template>
    <Head title="Jobs"/>
    <Layout>
        <div class="w-full">
            <div class="w-full my-10 text-center">
                <div class="text-fade text-2xl font-semibold">Browse Jobs</div>
            </div>
            <div class="relative grid grid-cols-4 max-w-[1400px] mx-auto gap-15">
                <div class="lg:block col-span-4 lg:col-span-1">
                    <div class="sticky top-40">
                        <JobFilter
                            :categories="categories"
                            :regions="regions"
                        />
                    </div>
                </div>
                <div class="col-span-4 lg:col-span-3 text-gray-900 antialiased">
                    <div class="grid items-stretch gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3">
                        <template v-for="(jobChunk, chunkIndex) in chunkArray<JobI>(jobs, 15)" :key="`chunk-${chunkIndex}`">
                            <JobCardV1
                                v-for="job in jobChunk"
                                :key="`job-card-${job.id}`"
                                :job="job"
                            />
                        </template>
                        <JobCardV1Skeleton v-show="loadingJobs" v-for="sk in [1, 2, 3, 4]" :key="sk" class="shadow-2xl" />
                    </div>
                    <div v-if="!jobs.length" class="text-center mt-10 text-xl font-bold text-fade max-w-xl">
                        No results found, broaden your search...
                    </div>
                </div>
<!--                <div class="hidden lg:block lg:col-span-1">-->
<!--                    <div class="sticky top-0">-->
<!--                        <div class="mb-4 text-lg font-semibold text-fade flex gap-2 items-center">-->
<!--                            Top Jobs-->
<!--                            <Briefcase :size="22" class="text-primary"/>-->
<!--                        </div>-->
<!--                        dadasdasdsa-->
<!--                    </div>-->
<!--                </div>-->
            </div>
        </div>
        <BackToTop />
    </Layout>
</template>

<style scoped>

</style>
