<script setup lang="ts">
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import { Button } from "@/components/ui/button";
import { useForm } from "@inertiajs/vue3";
import { toastError, toastSuccess } from "@/lib/helpers";
import { ref, watch, computed, watchEffect } from "vue";
import { LoaderCircle, Lock } from "lucide-vue-next";
import { ClassroomI, SubjectI } from "@/types";
import axios from "axios";
import { router } from "@inertiajs/vue3";
import Input from "@/components/ui/input/Input.vue";
 import SelectOption from "@/components/forms/SelectOption.vue";

const isOpen = ref(false);
const search = ref("");

const props = defineProps<{
  classroom: ClassroomI;
  class_subjects: SubjectI[];
}>();
const emit = defineEmits(["updated"]);

const form = useForm({
  class_id: props.classroom.id,
  student_id: '',
  subjects: [],
});

watchEffect(async () => {
  
  if (form.student_id) {
    const student = props.classroom.students.find(
      (s) => s.id === Number(form.student_id)
    );
    form.subjects = student?.subjects?.map((s) => s.id) || [];
  } else {
    form.subjects = [];
  }
});

const filtered = computed(() => {
  if (!search.value) return props.class_subjects;
  return props.class_subjects?.filter((s) =>
    s.name.toLowerCase().includes(search.value.toLowerCase())
  );
});
const selectAll = () => {
  const allValues = props.class_subjects?.map((s) => s.id);
  form.subjects = Array.from(new Set([...form.subjects, ...allValues]));
};

const unselectAll = () => {
  const visibleValues = props.class_subjects?.map((s) => s.id);
  form.subjects = form.subjects.filter((p) => !visibleValues.includes(p));
};

const submit = () => {
  form.post(route("student.assign-subject"), {
    onSuccess: (res) => {
      const message = res.props.message;
      if (res.props.success) toastSuccess(message);
      else toastError(message);
      isOpen.value = false;
      emit("updated");
    },
    preserveScroll: true,
  });
};
</script>
<template>
  <form @submit.prevent="submit" id="student-subject">
    <div  class="px-4 py-4 grid   gap-4">
      <div>
        <SelectOption
          model="student_id"
          :form="form"
          :options="
            classroom.students.map((s) => ({
              label: s.fullname,
              value: s.id,
            }))
          "
          label="Select Student"
          placeholder="Select Student"
          searchable
          required
        />

      </div>
        <DialogDescription>
          Select student and assign of the following subjects to them.
        </DialogDescription>
    </div>
  </form>
  <div
    v-if="filtered.length"
    class="px-4 pb-4 max-h-[60vh] overflow-y-auto custom-scrollbar -webkit-overflow-scrolling-touch"
  >
    <div class="p-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <Input
        v-model="search"
        type="text"
        placeholder="Search subjects..."
        class="w-full sm:w-64 px-3 py-2 rounded-md bg-muted text-foreground focus:outline-none"
      />

      <div class="flex flex-col sm:flex-row gap-2 sm:justify-end">
        <Button size="sm" variant="outline" @click="selectAll"
          >Select All ({{ class_subjects.length }})</Button
        >
        <Button size="sm" variant="destructive" @click="unselectAll"
          >Unselect All ({{ form.subjects.length }})</Button
        >
      </div>
    </div>

    <div class="overflow-x-auto">
      <Table class="min-w-full border border-border rounded-lg text-sm">
        <TableHeader class="bg-muted-background text-foreground">
          <TableRow>
            <TableHead class="px-4 py-2 text-left">Subject</TableHead>
            <TableHead class="px-4 py-2 text-left">Status</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <TableRow
            v-for="subject in filtered as SubjectI[]"
            :key="subject.id"
            class="border-t border-border hover:bg-muted/30"
          >
            <TableCell class="px-4 py-2 flex items-center gap-2">
              <Lock class="h-4 w-4 text-muted-foreground" />
              {{ subject.name }}
            </TableCell>
            <TableCell class="px-4 py-2">
              <Button
                v-if="!form.subjects.includes(subject.id)"
                size="sm"
                variant="outline"
                class="px-3 py-1 rounded-md text-xs font-medium bg-green-400 text-black hover:bg-green-500 transition"
                @click="form.subjects.push(subject.id)"
              >
                Add
              </Button>

              <Button
                v-else
                size="sm"
                variant="destructive"
                class="px-3 py-1 rounded-md text-xs font-medium bg-red-400 text-black hover:bg-red-500 transition"
                @click="form.subjects = form.subjects.filter((s) => s !== subject.id)"
                >Remove</Button
              >
            </TableCell>
          </TableRow>
        </TableBody>
      </Table>
    </div>
  </div>
  <div v-else class="text-center py-10 text-muted-foreground">No subjects found</div>
  <DialogFooter class="p-3">
    <Button
      :disabled="form.processing"
      type="submit"
      form="student-subject"
      @click="submit"
    >
      <LoaderCircle v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
      {{ form.processing ? "Please wait..." : "Save Changes" }}
    </Button>
  </DialogFooter>
</template>
