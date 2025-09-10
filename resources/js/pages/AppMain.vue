<script lang="ts">
export const description = "A sidebar that collapses to icons.";
export const iframeHeight = "800px";
export const containerClass = "w-full h-full";
</script>

<script setup lang="ts">
import AppLayout from "@/layouts/app/AppLayout.vue";

import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbPage,
  BreadcrumbSeparator,
} from "@/components/ui/breadcrumb";
</script>

<template>
  <AppLayout>
    <div class="flex flex-1 flex-col gap-4 p-4 pt-0 bg-gray-500">
      <div class="min-h-[100vh] flex-1 rounded-xl bg-muted/50 md:min-h-min" />
    </div>
  </AppLayout>
</template>
