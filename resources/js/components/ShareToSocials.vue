<script setup lang="ts">
import Facebook from '@/components/icons/Facebook.vue';
import Whatsapp from '@/components/icons/Whatsapp.vue';
import X from '@/components/icons/X.vue';
import { generateShareLinks } from '@/lib/helpers';
import { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';

const props = defineProps<{
    url?: string
    class?: HTMLAttributes['class']
}>()

const { facebook, whatsapp, x } = generateShareLinks(props.url ?? null);

</script>

<template>
    <div :class="cn('flex items-center space-x-4 my-4', props.class)">
        <span class="me-5 font-semibold">Share: </span>
        <a :href="whatsapp" target="_blank" class="w-4 h-4 flex items-center justify-center">
            <Whatsapp />
        </a>
        <a :href="facebook" target="_blank" class="w-4 h-4 flex items-center justify-center">
            <Facebook />
        </a>
        <a :href="x" target="_blank" class="w-4 h-4 flex items-center justify-center">
            <X />
        </a>
    </div>
</template>

<style scoped>

</style>
