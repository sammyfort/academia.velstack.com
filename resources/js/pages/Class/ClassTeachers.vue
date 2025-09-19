<script setup lang="ts">
import {
  Calendar,
  FileText,
  Plus,
  Search,
  MoreHorizontal,
  Eye,
  Edit,
  Users,
  SunIcon,
  NotebookPen,
} from "lucide-vue-next";
import { ClassroomI, InputSelectOption } from "@/types";
import SubjectToClass from "@/pages/Class/SubjectToClass.vue";
import { Input } from "@/components/ui/input";
import StaffAdd from "@/pages/Class/StaffAdd.vue";
import { useForm, Link } from "@inertiajs/vue3";
import { Button } from "@/components/ui/button";
import { computed } from "vue";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
const props = defineProps<{
  classroom: ClassroomI;
  staffRoles: InputSelectOption[];
}>();
import { useSearch } from "@/composables/useSearch";
const { query, results } = useSearch(
  computed(() => props.classroom.staff),
  ["first_name", "last_name", "middle_name", "staff_id"]
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
    <div class="flex space-x-3 justify-end"></div>

    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
      <StaffAdd
        :classroom="props.classroom"
        :staffRoles="staffRoles"
        @updated="$inertia.reload({ only: ['classroom'] })"
      >
        <button
          class="bg-primary text-primary-foreground px-3 py-2 rounded-lg hover:bg-primary/90 transition-colors flex items-center justify-center space-x-2 text-sm"
        >
          <Plus class="w-4 h-4" />
          <span>Manage Staff</span>
        </button>
      </StaffAdd>
    </div>
  </div>
  <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
    <div
      v-for="teacher in results"
      :key="teacher.id"
      class="bg-muted/50 rounded-lg p-4 flex flex-col space-y-3 max-w-md"
    >
      <!-- Header -->
      <div class="flex items-center space-x-3">
        <!-- Icon -->
        <div class="bg-green-500/10 dark:bg-green-400/10 p-2 rounded-full">
          <img
            :src="teacher.image"
            alt="avatar"
            class="h-12 w-12 rounded-full border border-border object-cover"
          />
        </div>

        <!-- Name + Dropdown + Roles -->
        <div class="flex flex-col">
          <!-- Name + Dropdown -->
          <div class="flex items-center space-x-4">
            <h3 class="font-semibold text-foreground">{{ teacher.fullname }}</h3>

            <!-- Dropdown next to name -->
            <DropdownMenu>
              <DropdownMenuTrigger as-child>
                <button
                  class="text-muted-foreground hover:text-foreground cursor-pointer"
                >
                  <MoreHorizontal class="w-4 h-4" />
                </button>
              </DropdownMenuTrigger>

              <DropdownMenuContent align="end" class="w-44 p-1">
                <Link
                  :href="route('staff.show', teacher.id)"
                  class="flex items-center gap-2 w-full rounded-md px-2 py-1.5 text-sm text-foreground hover:bg-muted focus:bg-muted transition"
                >
                  <Eye class="h-4 w-4 text-muted-foreground" />
                  <span>View</span>
                </Link>
                <Link
                  :href="route('staff.edit', teacher.id)"
                  class="flex items-center gap-2 w-full rounded-md px-2 py-1.5 text-sm text-foreground hover:bg-muted focus:bg-muted transition"
                >
                  <Edit class="h-4 w-4 text-muted-foreground" />
                  <span>Edit</span>
                </Link>
              </DropdownMenuContent>
            </DropdownMenu>
          </div>

          <!-- Role badges -->
          <div class="flex flex-wrap gap-2 mt-1">
            <span
              v-if="teacher.pivot?.permission === 'class teacher'"
              class="px-2 py-0.5 rounded-full text-xs bg-blue-500/10 text-blue-500"
            >
              Class Teacher
            </span>
            <span
              v-if="teacher.subjects?.length"
              class="px-2 py-0.5 rounded-full text-xs bg-green-500/10 text-green-500"
            >
              Subject Teacher
            </span>
          </div>
        </div>
      </div>

      <!-- Subjects Tile -->
      <div v-if="teacher.subjects.length" class="mt-3 p-3 bg-muted/20 rounded-lg">
        <h4 class="text-sm font-semibold text-foreground mb-2">Subjects</h4>

        <div class="flex flex-col gap-1">
          <div
            v-for="(subject, index) in teacher.subjects"
            :key="subject.id"
            class="flex items-center justify-between text-xs text-muted-foreground"
          >
            <span class="flex items-center space-x-1 rounded-full bg-blue/10 text-blue-400">
              <NotebookPen class="w-3 h-3" />
              <span>{{ index + 1 }}.</span>
            </span>
            
            <span class="px-2 py-0.5 text-xs rounded-full bg-primary/10 text-primary">
             
             <span class="flex items-center space-x-1">
              
              <span>{{subject.name }}</span>
            </span>
            </span>
          </div>
        </div>
      </div>
    </div>

    <div
      v-if="results.length === 0"
      class="col-span-full flex flex-col items-center justify-center py-16"
    >
      <Users class="h-12 w-12 text-muted-foreground mb-4" />
      <p class="text-muted-foreground">No staff found</p>
    </div>
  </div>
</template>

<style scoped></style>
