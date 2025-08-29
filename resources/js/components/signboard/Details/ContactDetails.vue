<script setup lang="ts">

import { AtSign, PhoneOutgoing } from 'lucide-vue-next';
import { SignboardI } from '@/types';

type Props = {
    signboard: SignboardI
}
const props = defineProps<Props>()
const service = props.signboard.service

</script>

<template>
    <div class="flex flex-col gap-4">
        <div class="text-fade font-semibold">
            Contact Details
        </div>
        <div class="flex flex-wrap gap-2">
            <div class="flex gap-1 items-center">
                <AtSign :size="17" class="text-secondary"/>
                <span class="font-medium">Email:</span>
            </div>
            <span class="break-all">{{ service?.email }}</span>
        </div>
        <div class="flex flex-wrap gap-2">
            <div class="flex gap-1">
                <PhoneOutgoing :size="17" class="text-secondary"/>
                <span class="font-medium">Mobile:</span>
            </div>
            <div>
                <div class="break-all">{{ service?.first_mobile }}</div>
                <div class="break-all">{{ service?.second_mobile }}</div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
