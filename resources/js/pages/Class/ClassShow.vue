<script setup lang="ts">
import AppLayout from "@/layouts/app/AppLayout.vue";
import {ClassroomI} from "@/types";
import {ref,} from 'vue';
import {Users, UserCheck, Plus, CheckCircle, XCircle, TrendingUp,} from 'lucide-vue-next';
import ClassStudents from "@/pages/Class/ClassStudents.vue";
import ClassSubjects from "@/pages/Class/ClassSubjects.vue";
import ClassTeachers from "@/pages/Class/ClassTeachers.vue";
import ClassRankings from "@/pages/Class/ClassRankings.vue";
import StatCard from "@/components/StatCard.vue";
import ClassOverview from "@/pages/Class/ClassOverview.vue";

const props = defineProps<{
    classroom: ClassroomI
}>();

const activeTab = ref('overview');

</script>

<template>
    <AppLayout>
        <!-- Header Section -->
        <div class="bg-background border-b border-muted">
            <div class="px-0 sm:px-6 py-4 sm:py-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-foreground">{{classroom?.name}}</h1>
                        <p class="text-muted-foreground mt-1 text-sm sm:text-base">Academic Year 2024-2025</p>
                    </div>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        <button class="bg-primary text-primary-foreground px-4 py-2 rounded-lg hover:bg-primary/90
                         transition-colors flex items-center justify-center space-x-2 text-sm">
                            <Plus class="w-4 h-4"/>
                            <span>Record Exam</span>
                        </button>
                        <button class="border border-muted text-foreground px-4 py-2 rounded-lg hover:bg-muted
                        transition-colors flex items-center justify-center space-x-2 text-sm">
                            <UserCheck class="w-4 h-4"/>
                            <span>Take Attendance</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="px-0 sm:px-6 py-4 sm:py-6">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
                <StatCard label="Total Students" value="10" :icon="Users"/>
                <StatCard label="Present" value="10" :icon="CheckCircle"/>
                <StatCard label="Absent" value="10" :icon="XCircle"/>
                <StatCard label="Average" value="10" :icon="TrendingUp"/>
            </div>

            <!-- Navigation Tabs -->
            <div class="bg-background rounded-xl shadow-sm border border-muted">
                <div class="border-b border-muted overflow-x-auto">
                    <nav class="flex space-x-4 sm:space-x-8 px-4 sm:px-6 min-w-max">
                        <button
                            @click="activeTab = 'overview'"
                            :class="activeTab === 'overview' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground'"
                            class="py-3 sm:py-4 px-1 border-b-2 font-medium text-sm transition-colors whitespace-nowrap"
                        >
                            Overview
                        </button>
                        <button
                            @click="activeTab = 'students'"
                            :class="activeTab === 'students' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground'"
                            class="py-3 sm:py-4 px-1 border-b-2 font-medium text-sm transition-colors whitespace-nowrap"
                        >
                            Students
                        </button>
                        <button
                            @click="activeTab = 'subjects'"
                            :class="activeTab === 'subjects' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground'"
                            class="py-3 sm:py-4 px-1 border-b-2 font-medium text-sm transition-colors whitespace-nowrap"
                        >
                            Subjects
                        </button>
                        <button
                            @click="activeTab = 'exams'"
                            :class="activeTab === 'exams' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground'"
                            class="py-3 sm:py-4 px-1 border-b-2 font-medium text-sm transition-colors whitespace-nowrap"
                        >
                            Exams
                        </button>
                        <button
                            @click="activeTab = 'rankings'"
                            :class="activeTab === 'rankings' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground'"
                            class="py-3 sm:py-4 px-1 border-b-2 font-medium text-sm transition-colors whitespace-nowrap"
                        >
                            Rankings
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-4 sm:p-6">
                    <ClassOverview v-if="activeTab === 'overview'"/>

                    <ClassStudents v-if="activeTab === 'students'" :classroom="classroom"/>

                    <ClassSubjects v-if="activeTab === 'subjects'" :classroom="classroom"/>

                    <ClassTeachers v-if="activeTab === 'exams'" :classroom="classroom"/>

                    <ClassRankings v-if="activeTab === 'rankings'" :classroom="classroom"/>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Custom scrollbar for better UX */
::-webkit-scrollbar {
    width: 4px;
    height: 4px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 2px;
}

::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

</style>
