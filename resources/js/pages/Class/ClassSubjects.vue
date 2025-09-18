<script setup lang="ts">

import {BookOpen, MoreHorizontal} from "lucide-vue-next";
import {ClassroomI} from "@/types";
const props = defineProps<{
    classroom: ClassroomI
}>()
</script>

<template>
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
        <div v-for="subject in classroom.subjects" :key="subject.id" class="bg-muted/50 rounded-lg p-4 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-primary/10 p-3 rounded-full">
                    <BookOpen class="w-5 h-5 sm:w-6 sm:h-6 text-primary" />
                </div>
                <button class="text-muted-foreground hover:text-foreground">
                    <MoreHorizontal class="w-4 h-4" />
                </button>
            </div>

            <h3 class="text-lg font-semibold text-foreground mb-2">{{ subject.name }}</h3>
            <p class="text-sm text-muted-foreground mb-1">Teacher: Sam</p>
            <p class="text-sm text-muted-foreground mb-4">Today</p>

            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-muted-foreground">Progress</span>
                    <span class="font-semibold text-foreground">10%</span>
                </div>
                <div class="w-full bg-muted rounded-full h-2">
                    <div
                        class="bg-primary h-2 rounded-full transition-all duration-300"
                        :style="{ width: `${100}%` }"
                    ></div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
