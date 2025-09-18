<script setup lang="ts">
import { XCircle } from "lucide-vue-next";
import type { Component } from "vue";

const props = defineProps<{
  label: string;
  value: string | number;

  icon?: Component;
    iconBg?: string;
  iconColor?: string;
  valueColor?: string;
}>();
</script>

<template>
  <div class="bg-background p-4 sm:p-6 rounded-xl shadow-sm border border-muted">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-xs sm:text-sm font-medium text-muted-foreground">{{ label }}</p>
        <p
          :class="['text-xl sm:text-2xl font-bold', valueColor]"
        >
          {{ value }}
        </p>
      </div>
      <div :class="['p-2 sm:p-3 rounded-full ',iconBg ]">
        <component :is="icon" :class="['w-4 h-4 sm:w-6 sm:h-6', iconColor]"/>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
