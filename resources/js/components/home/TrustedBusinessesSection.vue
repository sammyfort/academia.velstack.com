<script setup lang="ts">
import AdvertisedSignboardsH from '@/components/signboard/AdvertisedSignboardsH.vue';

withDefaults(defineProps<{
    titleText?: string
}>(), {
    titleText: "Top Signboards"
})
</script>

<template>
    <div class="mt-5">
        <h1 class="text-center mb-3 text-gray-400 text-2xl font-medium">{{ titleText }}</h1>
        <AdvertisedSignboardsH />
    </div>
</template>

<style scoped>

</style>
