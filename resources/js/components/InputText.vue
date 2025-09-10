<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { HTMLAttributes, useAttrs } from 'vue';
import { cn } from '@/lib/utils';
import { Textarea } from '@/components/ui/textarea'

type Props = {
    form?: Record<string, any>;
    model?: string;
    label?: string;
    containerClass?: HTMLAttributes['class'];
    textarea?: boolean,
    class?: HTMLAttributes['class']
};
const props = defineProps<Props>();
const id = Math.random().toString(36).substring(2, 10);
const attrs = useAttrs()
</script>

<template>
    <div :class="cn('grid gap-2', props.containerClass)" v-if="form">
        <Label class="text-secondary" :for="id">{{ props.label }}
            <span class="text-red-500" v-show="label && attrs.hasOwnProperty('required')">*</span>
        </Label>
        <Input
            v-if="!props.textarea"
            :id="id"
            :value="props.form[props.model]"
            v-model="props.form[props.model]"
            v-bind="attrs"
            :name="model"
        />

        <Textarea
            v-else="props.textarea"
            :id="id"
            :value="props.form[props.model]"
            v-model="props.form[props.model]"
            v-bind="attrs"
            :name="model"
        />

        <InputError :message="form.errors[model]" />
    </div>
    <div v-else :class="cn('grid gap-2', props.containerClass)">
        <Label class="text-accent-foreground" :for="id">{{ props.label }}</Label>
        <Input v-show="!props.textarea" :id="id" v-bind="attrs" />
        <Textarea   v-show="props.textarea" :id="id" v-bind="attrs" />
    </div>
</template>

<style scoped></style>
