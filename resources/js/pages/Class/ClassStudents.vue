<script setup lang="ts">
import {
  CheckCircle,
  Clock,
  MoreHorizontal,
  Search,
  XCircle,
  Eye,
  Edit,
  NotebookPenIcon
} from "lucide-vue-next";
import { ClassroomI } from "@/types";
import { ref, watchEffect, computed } from "vue";
import { Input } from "@/components/ui/input";
import { useForm, Link } from "@inertiajs/vue3";
import { dateAndTime, toastError, toastSuccess } from "@/lib/helpers";
import SelectOption from "@/components/forms/SelectOption.vue";
const props = defineProps<{
  classroom: ClassroomI;
  term_id: number | null;
  date: string;
}>();
 
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { useSearch } from "@/composables/useSearch";

const getStatusColor = (status: string) => {
  switch (status) {
    case "present":
      return "text-green-600 bg-green-50";
    case "absent":
      return "text-red-600 bg-red-50";
    case "late":
      return "text-orange-600 bg-orange-50";
    default:
      return "text-gray-600 bg-gray-50";
  }
};
const getStatusIcon = (status: string) => {
  switch (status) {
    case "present":
      return CheckCircle;
    case "absent":
      return XCircle;
    case "late":
      return Clock;
    default:
      return Clock;
  }
};

const form = useForm({
  student_id: "" as string | number,
  term_id: props.term_id,
  date: props.date,
  present: false as boolean,
});

watchEffect(() => {
  form.term_id = props.term_id;
  form.date = props.date;
});

const getTodayStatus = computed(() => {
  const currentDate = props.date; // already "YYYY-MM-DD"
  const currentTerm = props.term_id;

  return props.classroom.students.reduce((map: Record<string, string>, student) => {
    const record = student.attendances.find((a) => {
      const recordDate = a.date.split("T")[0]; // normalize backend value
      return a.term_id === currentTerm && recordDate === currentDate;
    });

    map[student.id] = record ? (record.present ? "present" : "absent") : "no-record";

    return map;
  }, {});
});

// Count how many attendances were marked present in this term
function presentCount(student: any) {
  return student.attendances.filter((a: any) => a.term_id === props.term_id && a.present)
    .length;
}

// Get total days from the term object (shared across all attendances in that term)
function totalDays(student: any) {
  const record = student.attendances.find((a: any) => a.term_id === props.term_id);
  return record ? record.term.days : 0;
}

const recordAttendance = (studentId: string | number, present: boolean) => {
  form.student_id = studentId;
  form.present = present;
  console.log(form.data());
  form.post(route("class.record.attendance"), {
    onSuccess: (res) => {
      const message = res.props.message;
      if (res.props.success) toastSuccess(message);
      else toastError(message);
      form.reset();
    },
    preserveScroll: true,
  });
};
const { query, results, reset } = useSearch(props.classroom.students, [
  "first_name", "last_name",
  "middle_name",
  "index_number",
]);
</script>

<template>
  <!-- Search and Filter -->
  <div class="flex flex-col sm:flex-row gap-4 mb-6">
    <div class="relative w-full sm:w-64">
      <Search
        class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground"
      />
      <Input v-model="query" placeholder="Search..." class="pl-10 w-full" />
    </div>
    <SelectOption :options="[]" model="term" placeholder="Select Academic calender" />
  </div>

  <!-- Students Grid -->
  <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
    <div
      v-for="student in results"
      :key="student.id"
      class="bg-muted/50 rounded-lg p-4 sm:p-5 hover:shadow-md transition-shadow border border-muted" >
      <div class="flex items-center space-x-3 mb-4">
        <!-- Avatar -->
        <div class="bg-primary/10 rounded-full flex items-center justify-center">
          <img
            :src="student.image"
            alt="avatar"
            class="h-12 w-12 rounded-full border border-border object-cover"
          />
        </div>

        <!-- Name + ID + Dropdown -->
        <div class="flex flex-col">
          <div class="flex items-center space-x-2">
            <h4 class="font-medium text-foreground text-sm sm:text-base">
              {{ student.fullname }}
            </h4>

            <!-- Dropdown aligned with name -->
            <DropdownMenu>
              <DropdownMenuTrigger as-child>
                <Button class="text-muted-foreground" size="icon">
                  <MoreHorizontal />
                </Button>
              </DropdownMenuTrigger>

              <DropdownMenuContent align="end" class="w-44 p-1">
                <Link
                  :href="route('students.show', student.id)"
                  class="flex items-center gap-2 w-full rounded-md px-2 py-1.5 text-sm text-foreground hover:bg-muted focus:bg-muted transition"
                >
                  <Eye class="h-4 w-4 text-muted-foreground" />
                  <span>View</span>
                </Link>
                <Link
                  :href="route('students.edit', student.id)"
                  class="flex items-center gap-2 w-full rounded-md px-2 py-1.5 text-sm text-foreground hover:bg-muted focus:bg-muted transition"
                >
                  <Edit class="h-4 w-4 text-muted-foreground" />
                  <span>Edit</span>
                </Link>
              </DropdownMenuContent>
            </DropdownMenu>
          </div>

          <!-- ID sits below name -->
          <p class="text-xs sm:text-sm text-muted-foreground">
            ID: {{ student.index_number }}
          </p>
        </div>
      </div>

      <!-- Current Status Display -->
      <div class="mb-4">
        <div class="flex justify-between items-center mb-2">
          <span class="text-xs sm:text-sm text-muted-foreground">{{dateAndTime(props.date)}}</span>
          <span
            :class="getStatusColor(getTodayStatus[student.id])"
            class="px-2 py-1 rounded-full text-xs font-medium capitalize flex items-center space-x-1"
          >
            <component :is="getStatusIcon(getTodayStatus[student.id])" class="w-3 h-3" />
            <span>{{ getTodayStatus[student.id] }}</span>
          </span>
        </div>
      </div>

      <!-- Modern Attendance Buttons -->
      <div class="mb-4">
        <p class="text-xs sm:text-sm text-muted-foreground mb-2">Quick Mark</p>
        <div class="grid grid-cols-3 gap-2">
          <button
            @click="recordAttendance(student.id, true)"
            :class="
              student.status === 'present'
                ? 'bg-green-500 dark:bg-green-400 text-primary-foreground shadow-md'
                : 'bg-background text-green-500 dark:text-green-400 border border-green-200 dark:border-green-500/50 hover:bg-green-500/10 dark:hover:bg-green-400/10'
            "
            class="py-2 px-2 rounded-lg text-xs font-medium transition-all duration-200 flex flex-col items-center justify-center space-y-1"
          >
            <CheckCircle class="w-3 h-3" />
            <span class="hidden sm:inline">Present</span>
            <span class="sm:hidden">✓</span>
          </button>
          <button
            @click="recordAttendance(student.id, false)"
            :class="
              student.status === 'absent'
                ? 'bg-red-500 dark:bg-red-400 text-primary-foreground shadow-md'
                : 'bg-background text-red-500 dark:text-red-400 border border-red-200 dark:border-red-500/50 hover:bg-red-500/10 dark:hover:bg-red-400/10'
            "
            class="py-2 px-2 rounded-lg text-xs font-medium transition-all duration-200 flex flex-col items-center justify-center space-y-1"
          >
            <XCircle class="w-3 h-3" />
            <span class="hidden sm:inline">Absent</span>
            <span class="sm:hidden">✗</span>
          </button>
          <button
            @click="recordAttendance(student.id, true)"
            :class="
              student.status === 'late'
                ? 'bg-orange-500 dark:bg-orange-400 text-primary-foreground shadow-md'
                : 'bg-background text-orange-500 dark:text-orange-400 border border-orange-200 dark:border-orange-500/50 hover:bg-orange-500/10 dark:hover:bg-orange-400/10'
            "
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
          <span class="text-xs sm:text-sm text-muted-foreground">Subjects</span>
          <span class="font-semibold text-foreground text-sm">{{
            student.subjects.length
          }}</span>
        </div>
        <div class="flex justify-between items-center">
          <span class="text-xs sm:text-sm text-muted-foreground">Attendance</span>
          <span class="font-semibold text-foreground text-sm">
            {{ presentCount(student) }} / {{ totalDays(student) }}
          </span>
        </div>
      </div>
    </div>

     <div v-if="results.length === 0" class="text-center end py-8">
                <NotebookPenIcon class="h-12 w-12 text-muted-foreground mx-auto mb-4"/>
                <p class="text-muted-foreground">No student found</p>
            </div>


  </div>
</template>

<style scoped></style>
