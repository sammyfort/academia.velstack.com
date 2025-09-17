<script setup lang="ts">
import vueFilePond from "vue-filepond";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import InputError from "@/components/InputError.vue";

const FilePond = vueFilePond(
    FilePondPluginImagePreview,
    FilePondPluginFileValidateType
);

interface Props {
    modelValue: File | null;
    form: any;
    preview?: string | null;
    modelName?: string;
    title?: string;
    error?: string
}

const props = withDefaults(defineProps<Props>(), {
    preview: null,
    modelName: "featured",
    title: "Featured Image",
});

const emit = defineEmits(["update:modelValue"]);

const handleAddFile = (error: any, file: any) => {
    if (!error && file.file instanceof File) {
        emit("update:modelValue", file.file);
    }
};

const handleRemoveFile = () => {
    emit("update:modelValue", null);
};
</script>

<template>
    <div class="bg-background rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center space-x-3 mb-4">
            <div class="p-2 bg-primary rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7M3 7l9-4 9 4" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-foreground">{{ props.title }}</h3>
        </div>


        <div v-if="props.preview && !props.modelValue" class="mb-4 relative">
            <img
                :src="props.preview"
                class="w-full h-40 rounded-lg object-cover border"
            />
            <div class="absolute bottom-2 left-2 px-2 py-1 bg-background rounded text-foreground text-xs">
                Current
            </div>
        </div>
        <FilePond
            name="featured"
            accepted-file-types="image/*"
            allow-multiple="false"
            label-idle='Drag & Drop or <span class="filepond--label-action">Browse</span>'
            @addfile="handleAddFile"
            @removefile="handleRemoveFile"
        />
        <h3 class="font-medium text-foreground mb-2">Photo Requirements:</h3>
        <ul class="text-sm text-muted-foreground space-y-1">
            <li>• Recent passport-style photograph</li>

        </ul>
        <div v-if="props.error" class="mt-2">

            <InputError :message="props.error"/>
        </div>
    </div>
</template>
<style>
.filepond--credits{
    display: none !important;
}
</style>
