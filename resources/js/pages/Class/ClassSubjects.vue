<script setup lang="ts">
import {BookOpen, MoreHorizontal, Search, Plus, UserCheck, Users} from "lucide-vue-next";
import {ClassroomI, SubjectI} from "@/types";
import { Input } from "@/components/ui/input";
import SubjectToClass from "./SubjectToClass.vue";
import {computed} from 'vue'
const props = defineProps<{
  classroom: ClassroomI;
}>();
import { useSearch } from "@/composables/useSearch";
import ManageClassSubjects from "./modals/ManageClassSubjects.vue";


const { query, results } = useSearch(
    computed(() => props.classroom.subjects),
    ["name", "code"]
);
</script>

<template>
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-3">
    <!-- Search -->
    <div class="relative w-full sm:w-1/2">
      <Search
        class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground"
      />
      <Input v-model="query" placeholder="Search..." class="pl-10 w-full" />
    </div>

    <!-- Action Buttons -->
    <div class="flex space-x-3 justify-end">
      

      <ManageClassSubjects :classroom="props.classroom" @updated="$inertia.reload({ only: ['classroom'] })">
        <button
          class="bg-primary text-primary-foreground px-3 py-2 rounded-lg hover:bg-primary/90 transition-colors flex items-center justify-center space-x-2 text-sm"
        >
          <Plus class="w-4 h-4" />
          <span>Manage Subjects</span>
        </button>
      </ManageClassSubjects>
    </div>
  </div>


  <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
      <div
          v-for="subject in results as SubjectI[]" :key="subject.id"
          class="bg-muted/50 rounded-lg p-4 sm:p-6 space-y-4">

          <div class="flex items-center justify-between">
              <div class="flex items-center space-x-3">
                  <div class="bg-primary/10 p-3 rounded-full">
                      <BookOpen class="w-5 h-5 sm:w-6 sm:h-6 text-primary" />
                  </div>
                  <h3 class="text-lg font-semibold text-foreground">
                      {{ subject.name }} 
                  </h3>
              </div>
              <!-- <button class="text-muted-foreground hover:text-foreground">
                  <MoreHorizontal class="w-4 h-4" />
              </button> -->
          </div>

          <div>
              <p class="text-sm font-medium text-foreground mb-1">Teachers</p>
              <div v-if="subject.staff?.length" class="flex flex-wrap gap-2">
                  <span v-for="teacher in subject.staff" :key="teacher.id" class="inline-flex items-center px-2 py-1 text-xs
                  rounded-full bg-primary/10 text-primary font-medium">{{ teacher.fullname }}</span>
              </div>
              <p v-else class="text-sm text-muted-foreground italic">
                  No teacher assigned
              </p>
          </div>

          <div class="space-y-2">
              <div class="flex justify-between items-center">
                  <span class="text-sm text-muted-foreground">Students</span>
                  <span class="font-semibold text-foreground">{{ subject.students.length }}</span>
              </div>
              <div class="w-full bg-muted rounded-full h-2">
                  <div
                      class="bg-primary h-2 rounded-full transition-all duration-300"
                       :style="{ width: `${(subject.students.length / classroom.students.length) * 100}%` }"
                  ></div>
              </div>
          </div>
      </div>

  </div>

    <div v-if="results.length === 0" class="col-span-full flex flex-col items-center justify-center py-16">
        <BookOpen class="h-12 w-12 text-muted-foreground mb-4" />
        <p class="text-muted-foreground">No subject found</p>
    </div>
</template>
<style scoped></style>
