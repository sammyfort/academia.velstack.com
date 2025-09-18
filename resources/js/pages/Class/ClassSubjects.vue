<script setup lang="ts">
import { BookOpen, MoreHorizontal, Search, Plus } from "lucide-vue-next";
import { ClassroomI } from "@/types";
import { Input } from "@/components/ui/input";
import SubjectToClass from "./SubjectToClass.vue";
const props = defineProps<{
  classroom: ClassroomI;
}>();
import { useSearch } from "@/composables/useSearch";

const { query, results, reset } = useSearch(props.classroom.subjects, ["name", "code"]);
</script>

<template>
  <!-- Header: Search + Add Button -->
  <!-- Header: Search + Action Buttons -->
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
      <button
        class="border border-muted text-foreground px-4 py-2 rounded-lg hover:bg-muted transition-colors flex items-center justify-center space-x-2 text-sm"
      >
        <UserCheck class="w-4 h-4" />
        <span>Take Attendance</span>
      </button>

      <SubjectToClass :classroom="props.classroom">
        <button
          class="bg-primary text-primary-foreground px-3 py-2 rounded-lg hover:bg-primary/90 transition-colors flex items-center justify-center space-x-2 text-sm"
        >
          <Plus class="w-4 h-4" />
          <span>Add Subject</span>
        </button>
      </SubjectToClass>
    </div>
  </div>

  <!-- Subjects Grid -->
  <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
    <div
      v-for="subject in results"
      :key="subject.id"
      class="bg-muted/50 rounded-lg p-4 sm:p-6"
    >
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

  <!-- No Results -->
  <div v-if="!results.length" class="text-center py-10 text-muted-foreground">
    No subjects found
  </div>
</template>
<style scoped></style>
