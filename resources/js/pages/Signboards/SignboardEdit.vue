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

import {InputSelectOption, SignboardI} from '@/types';
import GalleryFilePond from "@/components/GalleryFilePond.vue";
import FeaturedFilePond from "@/components/FeaturedFilePond.vue";

const props = defineProps<{
    signboard: SignboardI;
    categories: InputSelectOption[];
    regions: InputSelectOption[];
    services: InputSelectOption[];
    countries: InputSelectOption[]
}>();

const form = useForm({
    country_id: props.signboard?.country_id ?? '',
    service_id: props.signboard?.service_id ?? '',
    name: props.signboard?.name ?? '',
    region_id: Number(props.signboard?.region_id) ?? '',
    categories: props.signboard?.categories?.map(cat => cat.id) ?? [],
    town: props.signboard?.town ?? '',
    street: props.signboard?.street ?? '',
    landmark: props.signboard?.landmark ?? '',
    blk_number: props.signboard?.blk_number ?? '',
    gps: props.signboard?.gps ?? '',
    featured: null,
    gallery: [] as File[],
    removed_gallery_urls: [] as string[],
});


onMounted(() => {
console.log(props.signboard)
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
                submit-text="Update Signboard"
                processing-text="Updating Signboard..."
                @submit="updateSignboard"
            >
                <template #form-sections>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Signboard Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <InputSelect label="Select Country" :form="form" model="country_id" :options="props.countries"   required searchable />
                            <InputSelect label="Region" :form="form" model="region_id" :options="regions" required searchable />
                            <InputSelect label="Select Service" :form="form" model="service_id" :disabled="true" :options="services" required searchable />
                            <InputText :form="form" label="Name/Title" model="name" required />
                            <div class="md:col-span-2">
                                <InputSelect label="Fields Of Operation" :form="form" model="categories" :options="categories" taggable required searchable />
                            </div>

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
                    <FeaturedFilePond
                        ref="featureUploadRef"
                        :form="form"
                        v-model="form.featured"
                        :preview="props.signboard.featured"
                        modelName="featured"
                        :error="form.errors.featured"
                        title="Title or Featured image"
                    />


                    <GalleryFilePond
                        v-model="form.gallery"
                        :existing="galleryItems"
                        :form="form"
                        :error="form.errors.gallery"
                    />
                </template>
            </FormComponent>
        </div>
    </Layout>
</template>
