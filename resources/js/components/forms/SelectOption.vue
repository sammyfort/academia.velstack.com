<script setup lang="ts">
import {
    Select,
    SelectContent,
    SelectTrigger,
    SelectValue,
    SelectItem
} from "@/components/ui/select";
import { InputSelectOption } from "@/types";
import {Label} from "@/components/ui/label";
import {useAttrs} from "vue";
import InputError from "@/components/InputError.vue";

const props = defineProps<{
    modelValue: string | number | null;
    options: InputSelectOption[];
    label: string
    error?: string | null;

}>();

const emit = defineEmits<{
    (e: "update:modelValue", value: string | number | null): void;
}>();
const id = Math.random().toString(36).substring(2, 10);
const attrs = useAttrs()
</script>

<template>
    <Label class="text-secondary" :for="id">{{ props.label }} <span class="text-red-500" v-show="label && attrs.hasOwnProperty('required')">*</span></Label>
    <Select
        :model-value="props.modelValue"
        @update:model-value="(val) => emit('update:modelValue', val)"
    >
        <SelectTrigger class="w-full col-span-2 md:col-span-1 lg:col-span-2">
            <SelectValue placeholder="Select" />
        </SelectTrigger>
        <SelectContent>
            <SelectItem
                v-for="option in props.options"
                :key="option.value"
                :value="option.value"
            >
                {{ option.label }}
            </SelectItem>
        </SelectContent>
    </Select>
    <InputError :message="props.error" />

</template>
