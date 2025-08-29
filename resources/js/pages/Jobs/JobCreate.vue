<script setup lang="ts">
import {ref} from 'vue'
import { Head, useForm } from '@inertiajs/vue3';
import { toastSuccess, toastError } from '@/lib/helpers';
import { Building2 } from 'lucide-vue-next';
import Layout from '@/layouts/Layout.vue';
import PageHeader from '@/pages/Signboards/blocks/PageHeader.vue';
import TextEditor from '@/components/forms/TextEditor.vue'
import FormComponent from '@/components/FormComponent.vue';
import InputText from '@/components/InputText.vue';
import FeatureFileUpload from '@/components/FeatureFileUpload.vue';
import InputSelect from '@/components/InputSelect.vue';
import InputError from '@/components/InputError.vue';
import JobTemplate from '@/components/jobs/JobTemplate.vue';
import { InputSelectOption } from '@/types';
import FeaturedFilePond from "@/components/FeaturedFilePond.vue";

const props = defineProps<{
    types: Array<{ label: string; value: string }>
    statuses: Array<{ label: string; value: string}>
    modes: Array<{ label: string; value: string }>
    regions: Array<{ label: string; value: string}>
    categories: Array<{ label: string; value: string}>
    apply_modes: Array<{ label: string; value: string}>
    countries: InputSelectOption[]
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

const createJob = () => {
    form.post(route('my-jobs.store'), {
        onSuccess: (res) => {
            if (res.props.success) {
                toastSuccess('Job created');

            } else {
                toastError(res.props.message);
            }
            form.reset();

        }
    });
};

const jobEditor = ref<InstanceType<typeof TextEditor> | null>(null)

const focusJobEditor = () => {
    jobEditor.value?.focus()
}

</script>

<template>
    <Head title="Create Job" />
    <Layout>
        <div class="w-full bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
            <PageHeader
                title="Add New Job"
                subtitle="Create a new Job"
                :icon="Building2"
            />

            <FormComponent
                :form="form" submit-text="Create Job"
               processing-text="Creating Job..."
               @submit="createJob"
               container-width="max-w-6xl"
               containerWidth="w-full"
            >

                <template #form-sections>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Job Information</h2>
                        <div class="grid grid-cols-2 md:grid-cols-2 gap-6">
                            <InputSelect label="Select Country" :form="form" model="country_id" :options="props.countries"   required searchable />

                            <InputText :form="form" label="Company Name" model="company_name" required />
                            <InputSelect :form="form" label="Job Sector"  model="categories" :options="props.categories" taggable required searchable />
                            <InputText :form="form" label="Title" model="title" required />
                            <InputSelect :form="form" label="Job Type"  model="job_type" :options="props.types" required />
                            <InputSelect :form="form" label="Work Mode"  model="work_mode" :options="props.modes" required />
                            <InputSelect :form="form" label="Region"  model="region_id" :options="props.regions" required searchable />
                            <InputText  :form="form" label="Town" model="town" required />
                            <InputSelect label="Job Status" :form="form" model="status" :options="props.statuses" required />
                            <InputText :form="form" label="Salary" model="salary"  />
                            <InputText :form="form" label="Summary" model="summary" required textarea />
                            <InputText :form="form" label="Deadline" model="deadline" type="date" required  />
                        </div>
                    </div>


                    <div class="col-span-1 md:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Job Description</label>

                        <TextEditor v-model="form.description" ref="jobEditor"/>
                        <InputError v-if="form.errors.description" :message="form.errors.description " />

                        <div class="mt-3">
                            <JobTemplate
                                v-model="form.description"
                                @focusEditor="focusJobEditor"
                            />
                        </div>
                    </div>


                </template>

                <template #media-section>

                    <FeaturedFilePond
                        ref="featureUploadRef"
                        :form="form"
                        v-model="form.company_logo"
                        modelName="company_logo"
                        :error="form.errors.company_logo"
                    />

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">How to apply</h2>
                        <InputSelect :form="form" label="Mode of application"  model="apply_mode" :options="props.apply_modes" required />
                        <div class="grid grid-cols-2 md:grid-cols-2 gap-6 mt-4">

                            <div v-if="form.apply_mode === 'instruction' || form.apply_mode === 'both'"
                                 class="col-span-1 md:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Provide instructions</label>
                                <TextEditor v-model="form.how_to_apply" />
                                <InputError v-if="form.errors.how_to_apply" :message="form.errors.how_to_apply " />
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
