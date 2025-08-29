<script setup lang="ts">
import vueFilePond from "vue-filepond";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import InputError from "@/components/InputError.vue";
import {Plus} from 'lucide-vue-next'
const FilePond = vueFilePond(
    FilePondPluginImagePreview,
    FilePondPluginFileValidateType
);

interface Props {
    modelValue: File[];
    form: any;
    existing?: Array<{ url: string }>;
    error?: string | string[];
}

const props = defineProps<Props>();
const emit = defineEmits(["update:modelValue"]);

const handleAddFile = (error: any, file: any) => {
    if (!error && file.file instanceof File) {
        emit("update:modelValue", [...props.modelValue, file.file]);
    }
};

const handleRemoveFile = (error: any, file: any) => {
    emit(
        "update:modelValue",
        props.modelValue.filter((f) => f.name !== file.file.name)
    );
};

const removeOriginalImage = (url: string) => {


    if (!props.form.removed_gallery_urls.includes(url)) {
        props.form.removed_gallery_urls.push(url);

    }
};
</script>

<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-primary rounded-lg">
                    <Plus class="h-5 w-5 text-white" />
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Gallery Images</h3>
            </div>
            <span class="inline-flex items-center whitespace-nowrap px-3 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-full">
            {{ (props.existing?.filter(item => !props.form.removed_gallery_urls.includes(item.url)).length ?? 0) + props.modelValue.length }} images
        </span>
        </div>

        <div class="space-y-4">
            <div class="relative rounded-lg border-2 border-dashed border-gray-300 p-4 transition-all duration-200 hover:border-primary hover:bg-indigo-50/50 group">
                <FilePond
                    name="gallery"
                    allow-multiple
                    accepted-file-types="image/*"
                    label-idle='Drag & Drop or <span class="filepond--label-action">Browse</span>'
                    @addfile="handleAddFile"
                    @removefile="handleRemoveFile"
                />
            </div>
            <div v-if="props.existing?.length" class="grid grid-cols-2 gap-3 max-h-96 overflow-y-auto">
                <div
                    v-for="(item, idx) in props.existing"
                    :key="`existing-${idx}`"
                    v-show="item.url && !props.form.removed_gallery_urls.includes(item.url)"
                    class="relative group"
                >
                    <img
                        :src="item.url"
                        :alt="`Gallery image ${idx + 1}`"
                        class="w-full h-24 object-cover rounded border"
                    />
                    <button
                        type="button"
                        @click="removeOriginalImage(item.url)"
                        class="absolute top-1 right-1 bg-red-500 text-white rounded p-1 text-xs hover:bg-red-600 transition-colors"
                    >
                        <X class="h-3 w-3" />
                    </button>
                </div>
            </div>
            <div v-if="props.error" class="space-y-1 mt-2">
                <InputError :message="props.error"/>
            </div>
        </div>
    </div>
</template>
