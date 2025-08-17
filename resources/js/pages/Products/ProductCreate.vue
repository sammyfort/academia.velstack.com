

<script setup lang="ts">
import {ref} from 'vue'
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
import TextEditor from '@/components/forms/TextEditor.vue';
import InputError from '@/components/InputError.vue';

const props = defineProps<{
    regions: Array<{ label: string, value: string }>
    choices: Array<{ label: string, value: string }>
    categories: Array<{ label: string; value: string}>
    statuses: Array<{ label: string; value: string}>
}>();
const galleryUploadRef = ref();
const featureUploadRef = ref();
const form = useForm({
    name: '',
    status: '',
    description: '',
    short_description: '',
    price: '',
    categories: [],
    is_negotiable: '',
    first_mobile: '',
    second_mobile: '',
    whatsapp_mobile: '',
    video_link: '',
    website: '',
    town: '',
    region_id: '',
    featured: null,
    gallery: []
});

const createService = () => {
    form.post(route('my-products.store'), {
        onSuccess: (res) => {
            if (res.props.success) {
                toastSuccess('Product created');

            } else {
                toastError(res.props.message);
            }
            form.reset();
            if (featureUploadRef.value) {
                featureUploadRef.value.resetFeature();
            }
            if (galleryUploadRef.value) {
                galleryUploadRef.value.resetGallery();
            }
        }
    });
};


</script>

<template>
    <Head title="Create Product" />
    <Layout>
        <div class="w-full bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
            <PageHeader
                title="Add New Product"
                subtitle="Create a new product"
                :icon="Building2"
            />

            <FormComponent :form="form" submit-text="Create Product"
                           processing-text="Creating Product..." @submit="createService">

                <template #form-sections>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Product Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <InputSelect label="Select Region" :form="form" model="region_id" :options="props.regions"   required searchable />
                            <InputText :form="form" label="Product Name" model="name" required />
                            <InputSelect :form="form" label="Status"  model="status" :options="props.statuses"  required  />
                            <InputSelect :form="form" label="Category"  model="categories" :options="props.categories" taggable required searchable />
                            <InputText :form="form" label="Price" model="price" type="number" required />
                            <InputSelect label="Is Negotiable?" :form="form" model="is_negotiable" :options="props.choices"  required  />
                            <InputText :form="form" label="First Mobile No" type=tel model="first_mobile" required />
                            <InputText :form="form" label="Second Mobile No" type="tel" model="second_mobile"  />
                            <InputText :form="form" label="WhatsApp No" type="tel" model="whatsapp_mobile"  />
                            <InputText :form="form" label="Website" model="website"  />

                            <InputText :form="form" label="Town" model="town" required />
                            <div>
                                <InputText :form="form" label="Video Link" model="video_link" />
                                <span class="text-sm text-gray-500 font-small">
                                   Your video will  be shown to visitors if you have a running promotion</span>
                            </div>
                            <InputText :form="form" label="Short Description" model="short_description"  textarea />
                        </div>
                    </div>

                    <div class="col-span-1 md:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product Description</label>
                        <TextEditor v-model="form.description" />
                        <InputError v-if="form.errors.description" :message="form.errors.description " />
                    </div>
                </template>

                <template #media-section>
                    <FeatureFileUpload
                        ref="featureUploadRef"
                        :form="form"
                        v-model:file="form.featured"
                        model-name="featured"
                    />

                    <GalleryFilesUpload
                        ref="galleryUploadRef"
                        :form="form"
                        v-model:files="form.gallery"
                    />
                </template>
            </FormComponent>
        </div>
    </Layout>
</template>
