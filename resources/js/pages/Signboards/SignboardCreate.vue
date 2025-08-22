<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
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
import { InputSelectOption } from '@/types';

const props = defineProps<{
    business?: number;
    categories: Array<{ label: string; value: string }>;
    regions: Array<{ label: string; value: string }>;
    businesses: Array<{ label: string; value: string }>;
    countries: InputSelectOption[]
}>();

const form = useForm({
    country_id: '',
    business_id: '',
    region_id: '',
    categories: [],
    name: '',
    town: '',
    street: '',
    landmark: '',
    blk_number: '',
    gps: '',
    featured_image: null,
    gallery_images: []
});

const businessFieldDisabled = ref(false);

const galleryErrors = computed(() =>
    Object.keys(form.errors)
        .filter((key) => key.startsWith('gallery_images.'))
        .map((key) => form.errors[key])
);

onMounted(() => {
    if (props.business) {
        form.business_id = String(props.business);
        businessFieldDisabled.value = true;
    }
});

const createSignboard = () => {
    form.post(route('my-signboards.store'), {
        onSuccess: (res) => {
            if (res.props.success) {
                toastSuccess(res.props.message);
            } else {
                toastError(res.props.message);
            }
            form.reset();
        }
    });
};
</script>

<template>
    <Head title="Create Signboard" />
    <Layout>
        <div class="w-full bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
            <PageHeader
                title="Add New Signboard"
                subtitle="Create a new signboard listing for your business"
                :icon="Building2"
            />

            <FormComponent :form="form" submit-text="Create Signboard"
                           processing-text="Creating Signboard..." @submit="createSignboard">

                <template #form-sections>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Business Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <InputSelect label="Select Country" :form="form" model="country_id" :options="props.countries"   required searchable />

                            <InputSelect
                                label="Select Business" :form="form" model="business_id" :disabled="businessFieldDisabled" :options="businesses" required searchable
                            />
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
                        v-model:file="form.featured_image"
                    />

                    <GalleryFilesUpload
                        :form="form"
                        v-model:files="form.gallery_images"
                        :gallery-errors="galleryErrors"
                    />
                </template>
            </FormComponent>
        </div>
    </Layout>
</template>
