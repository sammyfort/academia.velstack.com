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
import { InputSelectOption, ProductI } from '@/types';
import TextEditor from '@/components/forms/TextEditor.vue';
import InputError from '@/components/InputError.vue';
import GalleryFilePond from "@/components/GalleryFilePond.vue";
import FeaturedFilePond from "@/components/FeaturedFilePond.vue";

const props = defineProps<{
    categories: Array<{ label: string; value: string }>;
    regions: Array<{ label: string; value: string }>;
    choices: Array<{ label: string; value: string }>;
    product: ProductI;
    statuses: Array<{ label: string; value: string }>;
    countries: InputSelectOption[];
}>();
const galleryUploadRef = ref();
const featureUploadRef = ref();
const form = useForm({
    country_id: props.product?.country_id || '',
    region_id: props.product?.region_id || '',
    name: props.product?.name || '',
    status: props.product?.status || '',
    description: props.product?.description || '',
    short_description: props.product?.short_description || '',
    price: props.product?.price ?? '',
    categories: (props.product?.categories || []).map((cat: any) => cat.id),
    is_negotiable: props.product?.is_negotiable ? 'yes' : 'no',
    first_mobile: props.product?.first_mobile || '',
    second_mobile: props.product?.second_mobile || '',
    whatsapp_mobile: props.product?.whatsapp_mobile || '',
    town: props.product?.town || '',
    video_link: props.product?.video_link || '',
    website: props.product?.website || '',
    featured: null,
    gallery: [] as File[],
    removed_gallery_urls: [] as string[],
});


onMounted(() => {


});

const galleryItems = computed(() => {
    if (!props.product.gallery) {
        return [];
    }

    const urls = Array.from(props.product.gallery);

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
    form.put(route('my-products.update', props.product.id), {
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
    <Head title="Update Product" />
    <Layout>
        <div class="w-full bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
            <PageHeader title="Edit Product" subtitle="Update Product" :icon="Building2" />

            <FormComponent
                :form="form"
                submit-text="Update Product"
                processing-text="Updating Product..."
                @submit="updateService"
            >
                <template #form-sections>
                    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                        <h2 class="mb-6 text-lg font-semibold text-gray-900">Product Information</h2>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <InputSelect label="Select Country" :form="form" model="country_id" :options="props.countries" required searchable />

                            <InputSelect label="Select Region" :form="form" model="region_id" :options="props.regions" required searchable />
                            <InputText :form="form" label="Product Name" model="name" required />
                            <InputSelect :form="form" label="Status" model="status" :options="props.statuses" required />
                            <InputSelect :form="form" label="Category" model="categories" :options="props.categories" taggable required searchable />
                            <InputText :form="form" label="Price" model="price" type="number" required />
                            <InputSelect label="Is Negotiable?" :form="form" model="is_negotiable" :options="props.choices" required />
                            <InputText :form="form" label="First Mobile No" type="tel" model="first_mobile" required />
                            <InputText :form="form" label="Second Mobile No" type="tel" model="second_mobile" />
                            <InputText :form="form" label="WhatsApp No" type="tel" model="whatsapp_mobile" />
                            <InputText :form="form" label="Website" model="website" />
                            <InputText :form="form" label="Town" model="town" required />
                            <InputText :form="form" label="Video Link" model="video_link" />
                            <InputText :form="form" label="Short Description" model="short_description" textarea />
                        </div>
                    </div>

                    <div class="col-span-1 rounded-xl border border-gray-200 bg-white p-6 shadow-sm md:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-gray-700">Product Description</label>
                        <TextEditor v-model="form.description" />
                        <InputError v-if="form.errors.description" :message="form.errors.description" />
                    </div>
                </template>

                <template #media-section>

                    <FeaturedFilePond
                        ref="featureUploadRef"
                        :form="form"
                        v-model="form.featured"
                        :preview="props.product.featured"
                        modelName="featured"
                        :error="form.errors.featured"
                    />


                    <GalleryFilePond
                        v-model="form.gallery"
                        :form="form"
                        :error="form.errors.gallery"
                        :existing="galleryItems"
                    />
                </template>
            </FormComponent>
        </div>
    </Layout>
</template>
