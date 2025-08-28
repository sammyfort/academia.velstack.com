<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import { toastSuccess, toastError } from '@/lib/helpers';
import { Building2 } from 'lucide-vue-next';
import Layout from '@/layouts/Layout.vue';
import PageHeader from '@/pages/Signboards/blocks/PageHeader.vue';

import FormComponent from '@/components/FormComponent.vue';
import InputSelect from '@/components/InputSelect.vue';
import InputText from '@/components/InputText.vue';
import FeatureFileUpload from '@/components/FeatureFileUpload.vue';
import GalleryFilesUpload from '@/components/GalleryFilesUpload.vue';
import { InputSelectOption, ServiceI } from '@/types';
import TextEditor from '@/components/forms/TextEditor.vue';
import InputError from '@/components/InputError.vue';

const props = defineProps<{
    categories: Array<{ label: string; value: string }>;
    regions: Array<{ label: string; value: string }>;
    service: ServiceI;
    countries: InputSelectOption[];
}>();
const galleryUploadRef = ref();
const featureUploadRef = ref();
const form = useForm({
    country_id: '',
    title: '',
    description: '',
    first_mobile: '',
    business_name: '',
    second_mobile: '',
    years_experience: '',
    video_link: '',
    email: '',
    address: '',
    town: '',
    gps: '',
    region_id: '',
    category_id: '',
    featured: null,
    gallery: [] as File[],
    removed_gallery_urls: [] as string[],
});

onMounted(() => {
    const s = props.service;
    form.country_id = s.country_id;
    form.title = s.title;
    form.description = s.description;
    form.first_mobile = s.first_mobile;
    form.second_mobile = s.second_mobile;
    form.business_name = s.business_name;
    form.video_link = s.video_link;
    form.years_experience = s.years_experience;
    form.email = s.email;
    form.address = s.address;
    form.town = s.town;
    form.gps = s.gps;
    form.region_id = s.region_id;
    form.category_id = s.category_id;
});

const galleryItems = computed(() => {
    if (!props.service.gallery) {
        return [];
    }

    const urls = Array.from(props.service.gallery);

    return urls.map((url, index) => ({
        url: url,
        isOriginal: true,
        originalIndex: index,
    }));
});

const galleryErrors = computed(() =>
    Object.keys(form.errors)
        .filter((key) => key.startsWith('gallery.'))
        .map((key) => form.errors[key]),
);

const updateService = () => {
    form.put(route('my-services.update', props.service.id), {
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
    <Head title="Update Service" />
    <Layout>
        <div class="w-full bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
            <PageHeader title="Edit Service" subtitle="Update service listing for your business" :icon="Building2" />

            <FormComponent
                :form="form"
                :featured-preview="props.service.featured"
                :gallery-items="galleryItems"
                :gallery-errors="galleryErrors"
                submit-text="Update Service"
                processing-text="Updating Service..."
                @submit="updateService"
            >
                <template #form-sections>
                    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                        <h2 class="mb-6 text-lg font-semibold text-gray-900">Service Information</h2>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <InputSelect label="Select Country" :form="form" model="country_id" :options="props.countries"   required searchable />
                            <InputSelect label="Select Region" :form="form" model="region_id" :options="props.regions" required searchable />
                            <InputText :form="form" label=" Service Name/Title" model="title" required />
                            <InputText :form="form" label="Years of Experience" model="years_experience" type="number" required />
                            <InputText :form="form" label="Town" model="town" required />
                            <InputText :form="form" label="Address" model="address" required />
                            <InputText :form="form" label="First Mobile No" type="tel" model="first_mobile" required />
                            <InputText :form="form" label="Second Mobile No" type="tel" model="second_mobile" />
                            <InputText :form="form" label="Email address" type="email" model="email" required />
                            <InputText :form="form" label="Business Name" model="business_name" />
                            <InputText :form="form" label="GPS Address" model="gps" required />
                            <div>
                                <InputText :form="form" label="Video Link" model="video_link" />
                                <span class="font-small text-sm text-gray-500">
                                    Your video will only be shown to visitors if you have a running promotion</span
                                >
                            </div>
                            <InputSelect label="Field Of Service" :form="form" model="category_id" :options="props.categories" required searchable />

                            <div class="md:col-span-2">
                                <label class="mb-1 block text-sm font-medium text-gray-700">Service Description</label>
                                <TextEditor v-model="form.description" />
                                <InputError v-if="form.errors.description" :message="form.errors.description" />
                            </div>
                        </div>
                    </div>
                </template>

                <template #media-section>
                    <FeatureFileUpload
                        ref="featureUploadRef"
                        :form="form"
                        :featured-preview="props.service.featured"
                        v-model:file="form.featured"
                        model-name="featured"
                    />

                    <GalleryFilesUpload
                        ref="galleryUploadRef"
                        :form="form"
                        v-model:files="form.gallery"
                        :gallery-errors="galleryErrors"
                        :gallery-items="galleryItems"
                    />
                </template>
            </FormComponent>
        </div>
    </Layout>
</template>
