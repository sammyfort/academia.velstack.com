<script setup lang="ts">

import {CheckCircle, Clock, MoreHorizontal, Search, XCircle} from "lucide-vue-next";
import {ClassroomI} from "@/types";
import {ref} from "vue";
const props = defineProps<{
    classroom: ClassroomI
}>()
const searchQuery = ref('');
const selectedFilter = ref('all');

const getStatusColor = (status: string) => {
    switch (status) {
        case 'present': return 'text-green-600 bg-green-50';
        case 'absent': return 'text-red-600 bg-red-50';
        case 'late': return 'text-orange-600 bg-orange-50';
        default: return 'text-gray-600 bg-gray-50';
    }
};
const getStatusIcon = (status: string) => {
    switch (status) {
        case 'present': return CheckCircle;
        case 'absent': return XCircle;
        case 'late': return Clock;
        default: return Clock;
    }
};
</script>

<template>
    <!-- Search and Filter -->
    <div class="flex flex-col sm:flex-row gap-4 mb-6">
        <div class="relative flex-1">
            <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 text-muted-foreground w-4 h-4" />
            <input
                v-model="searchQuery"
                type="text"
                placeholder="Search students..."
                class="w-full pl-10 pr-4 py-2 border border-muted rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm text-foreground bg-background"
            />
        </div>
        <select v-model="selectedFilter" class="px-4 py-2 border border-muted rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm text-foreground bg-background">
            <option value="all">All Students</option>
            <option value="present">Present</option>
            <option value="absent">Absent</option>
            <option value="late">Late</option>
        </select>
    </div>

    <!-- Students Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
        <div v-for="student in classroom.students" :key="student.id" class="bg-muted/50 rounded-lg p-4 sm:p-5 hover:shadow-md transition-shadow border border-muted">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center">
                        <span class="text-primary font-semibold text-sm">{{ student.index_number }}</span>
                    </div>
                    <div>
                        <h4 class="font-medium text-foreground text-sm sm:text-base">{{ student.fullname }}</h4>
                        <p class="text-xs sm:text-sm text-muted-foreground">ID: {{ student.id.toString().padStart(4, '0') }}</p>
                    </div>
                </div>
                <button class="text-muted-foreground hover:text-foreground">
                    <MoreHorizontal class="w-4 h-4" />
                </button>
            </div>

            <!-- Current Status Display -->
            <div class="mb-4">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-xs sm:text-sm text-muted-foreground">Current Status</span>
                    <span :class="'text-green-600 bg-green-50'" class="px-2 py-1 rounded-full text-xs font-medium capitalize flex items-center space-x-1">
                      <component :is="CheckCircle" class="w-3 h-3" />
                      <span>{{ student.status }}</span>
                    </span>
                </div>
            </div>

            <!-- Modern Attendance Buttons -->
            <div class="mb-4">
                <p class="text-xs sm:text-sm text-muted-foreground mb-2">Quick Mark</p>
                <div class="grid grid-cols-3 gap-2">
                    <button

                        :class="student.status === 'present' ? 'bg-green-500 dark:bg-green-400 text-primary-foreground shadow-md' : 'bg-background text-green-500 dark:text-green-400 border border-green-200 dark:border-green-500/50 hover:bg-green-500/10 dark:hover:bg-green-400/10'"
                        class="py-2 px-2 rounded-lg text-xs font-medium transition-all duration-200 flex flex-col items-center justify-center space-y-1"
                    >
                        <CheckCircle class="w-3 h-3" />
                        <span class="hidden sm:inline">Present</span>
                        <span class="sm:hidden">✓</span>
                    </button>
                    <button

                        :class="student.status === 'absent' ? 'bg-red-500 dark:bg-red-400 text-primary-foreground shadow-md' : 'bg-background text-red-500 dark:text-red-400 border border-red-200 dark:border-red-500/50 hover:bg-red-500/10 dark:hover:bg-red-400/10'"
                        class="py-2 px-2 rounded-lg text-xs font-medium transition-all duration-200 flex flex-col items-center justify-center space-y-1"
                    >
                        <XCircle class="w-3 h-3" />
                        <span class="hidden sm:inline">Absent</span>
                        <span class="sm:hidden">✗</span>
                    </button>
                    <button

                        :class="student.status === 'late' ? 'bg-orange-500 dark:bg-orange-400 text-primary-foreground shadow-md' : 'bg-background text-orange-500 dark:text-orange-400 border border-orange-200 dark:border-orange-500/50 hover:bg-orange-500/10 dark:hover:bg-orange-400/10'"
                        class="py-2 px-2 rounded-lg text-xs font-medium transition-all duration-200 flex flex-col items-center justify-center space-y-1"
                    >
                        <Clock class="w-3 h-3" />
                        <span class="hidden sm:inline">Late</span>
                        <span class="sm:hidden">⏰</span>
                    </button>
                </div>
            </div>

            <div class="space-y-2 pt-2 border-t border-muted">
                <div class="flex justify-between items-center">
                    <span class="text-xs sm:text-sm text-muted-foreground">Grade</span>
                    <span class="font-semibold text-foreground text-sm">A1</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs sm:text-sm text-muted-foreground">Attendance</span>
                    <span class="font-semibold text-foreground text-sm">70%</span>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
