<script setup lang="ts">
import { computed, onMounted } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import { toastSuccess, toastError } from '@/lib/helpers';
import { Edit3 } from 'lucide-vue-next';

import Layout from '@/layouts/Layout.vue';
import PageHeader from '@/pages/Signboards/blocks/PageHeader.vue';
import FormComponent from '@/components/FormComponent.vue';
import InputSelect from '@/components/InputSelect.vue';
import InputText from '@/components/InputText.vue';
import FeatureFileUpload from '@/components/FeatureFileUpload.vue';
import GalleryFilesUpload from '@/components/GalleryFilesUpload.vue';
import { InputSelectOption } from '@/types';

const props = defineProps<{
    signboard: {
        id: number;
        business_id: number;
        name: string;
        region_id: number;
        categories?: Array<{ id: number; name: string }>;
        slug: string;
        town: string;
        street: string;
        landmark: string;
        blk_number: string;
        gps: string;
        featured?: string;
        gallery: string[];
    };
    categories: Array<{ label: string; value: string }>;
    regions: Array<{ label: string; value: string }>;
    businesses: Array<{ label: string; value: string }>;
    countries: InputSelectOption[]
}>();

const form = useForm({
    country_id: '',
    business_id: '',
    name: '',
    region_id: '',
    categories: [],
    town: '',
    street: '',
    landmark: '',
    blk_number: '',
    gps: '',
    featured: null,
    gallery: [] as File[],
    removed_gallery_urls: [] as string[],
});

onMounted(() => {
    const s = props.signboard;
     form.country_id = s.country_id;
    form.business_id = String(s.business_id);
    form.name = s.name;
    form.region_id = String(s.region_id);
    form.categories = s.categories?.map(cat => cat.id) || [];
    form.town = s.town;
    form.street = s.street;
    form.landmark = s.landmark;
    form.blk_number = s.blk_number;
    form.gps = s.gps;


});

const galleryItems = computed(() => {
    if (!props.signboard.gallery) {
        return [];
    }

    const urls = Array.from(props.signboard.gallery);

    return urls.map((url, index) => ({
        url: url,
        isOriginal: true,
        originalIndex: index
    }));

});

const galleryErrors = computed(() =>
    Object.keys(form.errors)
        .filter(key => key.startsWith('gallery_images.'))
        .map(key => form.errors[key])
);

const updateSignboard = () => {
    form.post(route('my-signboards.update', props.signboard.id), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: res => {
            if (res.props.success) {
                toastSuccess(res.props.message);
                form.removed_gallery_urls = [];
            } else {
                toastError(res.props.message);
            }
        }
    });
};
</script>

<template>
    <Head title="Edit Signboard" />
    <Layout>
        <div class="w-full bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
            <PageHeader
                title="Edit Signboard"
                subtitle="Update your signboard listing information"
                :icon="Edit3"
            />

            <FormComponent
                :form="form"
                :featured-preview="props.signboard.featured"
                :gallery-items="galleryItems"
                :gallery-errors="galleryErrors"
                submit-text="Update Signboard"
                processing-text="Updating Signboard..."
                @submit="updateSignboard"
            >
                <template #form-sections>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Business Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <InputSelect label="Select Country" :form="form" model="country_id" :options="props.countries"   required searchable />

                            <InputSelect label="Select Business" :form="form" model="business_id" :disabled="true" :options="businesses" required searchable />
                            <InputText :form="form" label="Name/Title" model="name" required />
                            <div class="md:col-span-2">
                                <InputSelect label="Fields Of Operation" :form="form" model="categories" :options="categories" taggable required searchable />
                            </div>
                            <InputSelect label="Region" :form="form" model="region_id" :options="regions" required searchable />
                            <InputText :form="form" label="Town" model="town" required />
                        </div>
                    </div>


                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Location Details</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <InputText :form="form" label="Street Address" model="street" placeholder="e.g., Main Street" />
                            <InputText :form="form" label="Landmark" model="landmark" required placeholder="e.g., Near Central Mall" />
                            <InputText :form="form" label="Block Number" model="blk_number" placeholder="e.g., Block A" />
                            <InputText :form="form" label="GPS" model="gps" placeholder="e.g., 5.6037, -0.1870" />
                        </div>
                    </div>

                </template>
                <template #media-section>
                    <FeatureFileUpload
                        :form="form"
                        :featured-preview="props.signboard.featured"
                        v-model:file="form.featured"
                    />

                    <GalleryFilesUpload
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
