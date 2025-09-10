<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import { toastSuccess, toastError } from '@/lib/helpers';
import { Building2 } from 'lucide-vue-next';
import Layout from '@/layouts/GuestLayout.vue';
import PageHeader from '@/pages/Signboards/blocks/PageHeader.vue';
import TextEditor from '@/components/forms/TextEditor.vue';
import FormComponent from '@/components/FormComponent.vue';
import InputText from '@/components/InputText.vue';
import InputSelect from '@/components/InputSelect.vue';
import InputError from '@/components/InputError.vue';
import { InputSelectOption, JobI } from '@/types';
import FeaturedFilePond from "@/components/FeaturedFilePond.vue";

const props = defineProps<{
    job: JobI;
    types: Array<{ label: string; value: string }>;
    statuses: Array<{ label: string; value: string }>;
    modes: Array<{ label: string; value: string }>;
    regions: Array<{ label: string; value: string }>;
    categories: Array<{ label: string; value: string }>;
    apply_modes: Array<{ label: string; value: string }>;
    countries: InputSelectOption[];
}>();

const featureUploadRef = ref();

const form = useForm({
    country_id: '',
    company_name: '',
    title: '',
    categories: [],
    job_type: '',
    work_mode: '',
    status: '',
    deadline: '',
    region_id: '',
    town: '',
    salary: '',
    summary: '',
    description: '',
    apply_mode: 'instruction',
    how_to_apply: '',
    application_link: '',
    company_logo: null,
});

onMounted(() => {
    const job = props.job;
    form.country_id = Number(job.country_id);
    form.company_name = job.company_name || '';
    form.title = job.title || '';
    form.categories = (job.categories || []).map((cat: any) => cat.id);
    form.job_type = job.job_type || '';
    form.work_mode = job.work_mode || '';
    form.status = job.status || '';
    form.deadline = job.deadline ? new Date(job.deadline).toISOString().split('T')[0] : '';
    form.region_id = Number(job.region_id) || '';
    form.town = job.town || '';
    form.salary = job.salary || '';
    form.summary = job.summary || '';

    form.how_to_apply = job.how_to_apply || '';
    form.application_link = job.application_link || '';
    form.description = job.description || '';
});

const updateJob = () => {
    form.put(route('my-jobs.update', props.job.id), {
        preserveScroll: true,
        onSuccess: (res) => {
            if (res.props.success) {
                toastSuccess(res.props.message);
            } else {
                toastError(res.props.message);
            }
        },
    });
};
</script>

<template>
    <Head title="Update Job" />
    <Layout>
        <div class="w-full bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
            <PageHeader title="Edit Job" subtitle="Update job details" :icon="Building2" />

            <FormComponent
                :form="form"
                submit-text="Update Job"
                processing-text="Creating Job..."
                @submit="updateJob"
                container-width="max-w-6xl"
                containerWidth="w-full"
            >
                <template #form-sections>
                    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                        <h2 class="mb-6 text-lg font-semibold text-gray-900">Job Information</h2>
                        <div class="grid grid-cols-2 gap-6 md:grid-cols-2">
                            <InputSelect label="Select Country" :form="form" model="country_id" :options="props.countries" required searchable />
                            <InputText :form="form" label="Company Name" model="company_name" required />
                            <InputSelect
                                :form="form"
                                label="Job Sector"
                                model="categories"
                                :options="props.categories"
                                taggable
                                required
                                searchable
                            />
                            <InputText :form="form" label="Title" model="title" required />
                            <InputSelect :form="form" label="Job Type" model="job_type" :options="props.types" required />
                            <InputSelect :form="form" label="Work Mode" model="work_mode" :options="props.modes" required />
                            <InputSelect :form="form" label="Region" model="region_id" :options="props.regions" required searchable />
                            <InputText :form="form" label="Town" model="town" required />
                            <InputSelect label="Job Status" :form="form" model="status" :options="props.statuses" required />
                            <InputText :form="form" label="Salary" model="salary" />
                            <InputText :form="form" label="Summary" model="summary" required textarea />
                            <InputText :form="form" label="Deadline" model="deadline" type="date" required />
                        </div>
                    </div>

                    <div class="col-span-1 rounded-xl border border-gray-200 bg-white p-6 shadow-sm md:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-gray-700">Job Description</label>
                        <TextEditor v-model="form.description" />
                        <InputError v-if="form.errors.description" :message="form.errors.description" />
                    </div>
                </template>

                <template #media-section>

                    <FeaturedFilePond
                        ref="featureUploadRef"
                        :form="form"
                        v-model="form.company_logo"
                        :preview="props.job.company_logo"
                        modelName="featured"
                        :error="form.errors.company_logo"
                    />

                    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                        <h2 class="mb-6 text-lg font-semibold text-gray-900">How to apply</h2>
                        <InputSelect :form="form" label="Mode of application" model="apply_mode" :options="props.apply_modes" required />
                        <div class="mt-4 grid grid-cols-2 gap-6 md:grid-cols-2">
                            <div
                                v-if="form.apply_mode === 'instruction' || form.apply_mode === 'both'"
                                class="col-span-1 rounded-xl border border-gray-200 bg-white p-6 shadow-sm md:col-span-2"
                            >
                                <label class="mb-1 block text-sm font-medium text-gray-700">Provide instructions</label>
                                <TextEditor v-model="form.how_to_apply" />
                                <InputError v-if="form.errors.how_to_apply" :message="form.errors.how_to_apply" />
                            </div>
                        </div>
                        <InputText
                            container-class="w-full mt-5"
                            v-if="form.apply_mode === 'external_link' || form.apply_mode === 'both'"
                            :form="form"
                            label="Link to apply"
                            model="application_link"
                            textarea
                            required
                        />
                    </div>
                </template>
            </FormComponent>
        </div>
    </Layout>
</template>
