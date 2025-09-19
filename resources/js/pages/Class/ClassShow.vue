<script setup lang="ts">
import AppLayout from "@/layouts/app/AppLayout.vue";
import {ClassroomI, InputSelectOption} from "@/types";
import {ref, computed, onMounted} from 'vue';
import {Users, UserCheck, Plus, CheckCircle, XCircle, TrendingUp, UserCheck2,} from 'lucide-vue-next';
import ClassStudents from "@/pages/Class/ClassStudents.vue";
import ClassSubjects from "@/pages/Class/ClassSubjects.vue";
import ClassTeachers from "@/pages/Class/ClassTeachers.vue";
import ClassRankings from "@/pages/Class/ClassRankings.vue";
import StatCard from "@/components/StatCard.vue";
import ClassOverview from "@/pages/Class/ClassOverview.vue";
import Datepicker from "@/components/forms/Datepicker.vue";
import SelectOption from "@/components/forms/SelectOption.vue";
import { format } from "date-fns";
import { useForm } from "@inertiajs/vue3";
import { dateAndTime } from "@/lib/helpers";
const props = defineProps<{
    classroom: ClassroomI
    semesters: InputSelectOption[]
    staffRoles: InputSelectOption[]
}>();
import { useAppStore } from "@/stores/appStore";
import SubjectCreate from "@/pages/Subject/SubjectCreate.vue";
const tabStore = useAppStore();


const activeTab = computed({
  get: () => tabStore.getActiveTab(props.classroom.id, "overview"),
  set: (tab) => tabStore.setActiveTab(props.classroom.id, tab),
});

const form = useForm({
  date: new Date().toISOString().split('T')[0],
  term: props.semesters[0].value,
});
const term = ref<number | null>(null);


const academicYear = computed(() => {
  const selected = props.semesters.find(s => s.value === form.term);
  return selected ? selected.label : "";
});
</script>

<template>
    <AppLayout>
        <!-- Header Section -->
        <div class="bg-background border-b border-muted">
            <div class="px-0 sm:px-6 py-4 sm:py-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-foreground">{{classroom?.name}}</h1>
                        <p class="text-muted-foreground mt-1 text-sm sm:text-base">{{ academicYear }}
                         <span class="text-blue-500">({{ dateAndTime(form.date) }})</span>
                         </p>

                    </div>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                     <Datepicker :form="form" model="date" class="py-4 px-4" />
                     <SelectOption :options="semesters" :form="form"  model="term" placeholder="Select Academic calender" />
                      <button class="border border-muted text-foreground px-4 py-2 rounded-lg hover:bg-muted
                        transition-colors flex items-center justify-center space-x-2 text-sm">
                            <UserCheck class="w-4 h-4"/>
                            <span>Take Attendance</span>
                        </button>
                        <button class="bg-primary text-primary-foreground px-3 py-2 rounded-lg hover:bg-primary/90
                         transition-colors flex items-center justify-center space-x-2 text-sm">
                            <Plus class="w-4 h-4"/>
                            <span>Record Exam</span>
                        </button>

                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="px-0 sm:px-6 py-4 sm:py-6">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
                <StatCard label="Total Students" :value="classroom.students.length" :icon="Users"
                 value-color="text-green-600 dark:text-green-300"
                 icon-color="text-green-600 dark:text-green-300"
                 icon-bg="bg-green-50 dark:bg-green-900/20"
                 />

                <StatCard label="Subjects" :value="classroom.subjects.length"  :icon="CheckCircle"
                 value-color="text-blue-600 dark:text-blue-400"
                 icon-color="text-blue-600 dark:text-blue-500"
                 icon-bg="bg-blue-50 dark:bg-blue-900/30"
                />

                 <StatCard label="Staff" :value="classroom.staff.length" :icon="UserCheck2"
                  value-color="text-yellow-600 dark:text-yellow-400"
                 icon-color="text-yellow-600 dark:text-yellow-500"
                 icon-bg="bg-yellow-50 dark:bg-yellow-900/30"

                />
                <StatCard label="Average" value="10" :icon="TrendingUp"
                 value-color="text-purple-600 dark:text-purple-400"
                 icon-color="text-purple-600 dark:text-purple-500"
                 icon-bg="bg-purple-50 dark:bg-purple-900/30"
                />
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
                            @click="activeTab = 'staff'"
                            :class="activeTab === 'staff' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground'"
                            class="py-3 sm:py-4 px-1 border-b-2 font-medium text-sm transition-colors whitespace-nowrap"
                        >
                            Staff
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

                    <ClassStudents v-if="activeTab === 'students'"
                    :term_id="form.term"
                    :date="form.date"
                    :classroom="classroom"/>

                    <ClassSubjects v-if="activeTab === 'subjects'" :classroom="classroom"/>

                    <ClassTeachers v-if="activeTab === 'staff'"
                                   :classroom="classroom"
                                   :staffRoles="staffRoles"/>

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
