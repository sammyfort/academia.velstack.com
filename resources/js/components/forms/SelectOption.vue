<script setup lang="ts">
import {
  Select,
  SelectContent,
  SelectTrigger,
  SelectValue,
  SelectItem,
} from "@/components/ui/select";
import { Label } from "@/components/ui/label";
import InputError from "@/components/InputError.vue";
import { HTMLAttributes, useAttrs } from "vue";
import { cn } from "@/lib/utils";
import type { InputSelectOption } from "@/types";
import {LoaderCircle} from 'lucide-vue-next'
type Props = {
  form?: Record<string, any>;
  model?: string;
  label?: string;
  options: InputSelectOption[] | undefined;
  containerClass?: HTMLAttributes["class"];
  placeholder?: string;
    loading?: boolean
};

const props = defineProps<Props>();
const emit = defineEmits<{
  (e: "update:modelValue", value: string | number | null): void;
}>();

const id = Math.random().toString(36).substring(2, 10);
const attrs = useAttrs();
</script>

<template>
  <div :class="cn(' ', props.containerClass)" v-if="form && model">
    <Label v-if="label"  class="text-foreground mb-2" :for="id">
      {{ props.label }}
      <span
        class="text-red-500"
        v-show="label && attrs.hasOwnProperty('required')"
        >*</span
      >
    </Label>

    <Select
      :model-value="form[model]"
      @update:model-value="(val) => (form[model] = val)"
      :disabled="props.loading"
    >
      <SelectTrigger class="w-full">
        <SelectValue   :placeholder="props.loading ? 'Loading...' : props.placeholder || 'Select'"
        />
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


    <InputError :message="form.errors[model]" />
  </div>

  <div v-else :class="cn(' ', props.containerClass)">
    <Label v-if="label" class="text-foreground mb-2" :for="id">
      {{ props.label }}
      <span
        class="text-red-500"
        v-show="label && attrs.hasOwnProperty('required')"
        >*</span
      >
    </Label>

    <Select
      v-bind="attrs"
      :model-value="$attrs.modelValue"
      @update:model-value="(val) => emit('update:modelValue', val)"
      :disabled="props.loading"
    >
      <SelectTrigger class="w-full">
        <SelectValue   :placeholder="props.loading ? 'Loading...' : props.placeholder || 'Select'"
        />
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
    <InputError :message="attrs.error" />
  </div>
</template>
