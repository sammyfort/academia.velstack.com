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
    country_id: '',
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
    gallery: [] as File[],
    removed_gallery_urls: [] as string[],
});

onMounted(() => {

    const p = props.product;
    form.country_id = p.country_id;
    form.region_id = p.region_id;
    form.name = p.name;
    form.status = p.status;
    form.categories = (p.categories || []).map((cat: any) => cat.id);
    form.description = p.description;
    form.short_description = p.short_description;
    form.price = p.price;
    form.is_negotiable = p.is_negotiable ? 'yes' : 'no';
    form.first_mobile = p.first_mobile;
    form.second_mobile = p.second_mobile;
    form.town = p.town;
    form.video_link = p.video_link;


    form.website = p.website;
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
                :featured-preview="props.product.featured"
                :gallery-items="galleryItems"
                :gallery-errors="galleryErrors"
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
                    <FeatureFileUpload
                        ref="featureUploadRef"
                        :form="form"
                        :featured-preview="props.product.featured"
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
