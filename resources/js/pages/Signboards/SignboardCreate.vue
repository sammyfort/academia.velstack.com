<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import {Head, Link, useForm} from '@inertiajs/vue3';
import { toastSuccess, toastError } from '@/lib/helpers';
import {Building2, Check, LoaderCircle, PlusIcon} from 'lucide-vue-next';
import Layout from '@/layouts/Layout.vue';
import PageHeader from '@/pages/Signboards/blocks/PageHeader.vue';

import FormComponent from '@/components/FormComponent.vue';
import InputSelect from '@/components/InputSelect.vue';
import InputText from '@/components/InputText.vue';

import { InputSelectOption } from '@/types';
import {Button} from "@/components/ui/button";
import GalleryFilePond from "@/components/GalleryFilePond.vue";
import FeaturedFilePond from "@/components/FeaturedFilePond.vue";

const props = defineProps<{
    service?: number;
    categories: Array<{ label: string; value: string }>;
    regions: Array<{ label: string; value: string }>;
    services: Array<{ label: string; value: string }>;
    countries: InputSelectOption[]
}>();

const form = useForm({
    country_id: '',
    service_id: '',
    region_id: '',
    categories: [],
    name: '',
    town: '',
    street: '',
    landmark: '',
    blk_number: '',
    gps: '',
    featured: null,
    gallery: []
});

const businessFieldDisabled = ref(false);



onMounted(() => {
    if (props.service) {
        form.service_id = String(props.service);
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
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Signboard Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <InputSelect label="Select Country" :form="form" model="country_id" :options="props.countries"   required searchable />

                            <div>
                                <InputSelect
                                    label="Select Service" :form="form" model="service_id"
                                    :disabled="businessFieldDisabled" :options="services" required searchable
                                />
                                <Link v-if="!services.length" :href="route('my-services.create')">
                                    <Button class="bg-primary hover:from-primary-700 hover:to-primary-700
                text-white font-semibold px-6 py-4 rounded-full shadow-2xl transform hover:scale-110 transition-all duration-300 ease-out flex items-center gap-3 group">
                                        <PlusIcon class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" />
                                        <span class=" lg:block">Add Service</span>
                                    </Button>
                                </Link>
                            </div>
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
                    <FeaturedFilePond
                        ref="featureUploadRef"
                        :form="form"
                        v-model="form.featured"
                        :preview="form.featured"
                        modelName="featured"
                        :error="form.errors.featured"
                    />


                    <GalleryFilePond
                        v-model="form.gallery"
                        :form="form"
                        :error="form.errors.gallery"
                    />
                </template>
            </FormComponent>
        </div>
    </Layout>
</template>
