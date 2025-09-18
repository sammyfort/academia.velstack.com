<script setup lang="ts">

import {XCircle} from "lucide-vue-next";
import type {Component} from "vue";

const props = defineProps<{
    label: string;
    value: string | number;
    iconBg?: string;
    icon?: Component;

}>();
</script>

<template>
    <div class="bg-background p-4 sm:p-6 rounded-xl shadow-sm border border-muted">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs sm:text-sm font-medium text-muted-foreground">{{ label }}</p>
                <p class="text-xl sm:text-2xl font-bold text-red-500 dark:text-red-400">{{ value}}</p>
            </div>
            <div class="bg-red-500/10 dark:bg-red-400/10 p-2 sm:p-3 rounded-full">
                <component :is="icon" class="w-4 h-4 sm:w-6 sm:h-6 text-red-500 dark:text-red-400" />
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
