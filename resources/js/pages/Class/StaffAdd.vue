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
import InputText from "@/components/InputText.vue";
import { useForm } from "@inertiajs/vue3";
import { toastError, toastSuccess } from "@/lib/helpers";
 import { Input } from "@/components/ui/input";
import { ref, watch, computed, watchEffect} from "vue";
import { LoaderCircle, CircleAlert, Lock } from "lucide-vue-next";
import { ClassroomI, InputSelectOption, SubjectI } from "@/types";
import SelectOption from "@/components/forms/SelectOption.vue";
import axios from "axios";
import { Alert, AlertDescription } from "@/components/ui/alert";
const props = defineProps<{
  classroom: ClassroomI;
  staffRoles: InputSelectOption[];

}>();

const isOpen = ref(false);
const staffData = ref<InputSelectOption[]>([]);
const search = ref("");
const loading = ref(false);
const form = useForm({
  class_id: props.classroom.id,
  staff_id: "",
  role: "",
  subjects: [],
});

 
watchEffect(async () => {
  // Load staff data when dialog opens
  if (isOpen.value && !staffData.value.length) {
    loading.value = true;
    try {
      const { data } = await axios.get(route("api.staff"));
      staffData.value = data.staff.map((s) => ({
        label: s.fullname,
        value: s.id,
      }));
    } catch (e) {
      toastError("Failed to load class data");
    } finally {
      loading.value = false;
    }
  }

  // Update form.subjects whenever a staff is selected
  if (form.staff_id) {
    const staff = props.classroom.staff.find(
      (s) => s.id === Number(form.staff_id)
    );
    form.subjects = staff?.subjects?.map((s) => s.id) || [];
  } else {
    form.subjects = [];
  }
});


const submit = () => {
  form.post(route("class.assign-staff"), {
    onSuccess: (res) => {
      const message = res.props.message;
      if (res.props.success) toastSuccess(message);
      else toastError(message);
      isOpen.value = false;
      form.reset();
    },
    preserveScroll: true,
  });
};

const filtered = computed(() => {
  if (!search.value) return props.classroom.subjects;
  return props.classroom.subjects.filter((s) =>
    s.name.toLowerCase().includes(search.value.toLowerCase())
  );
});

const selectAll = () => {
  const allValues = props.classroom.subjects.map((s) => s.id);
  form.subjects = Array.from(new Set([...form.subjects, ...allValues]));
};

const unselectAll = () => {
  const visibleValues = props.classroom.subjects.map((s) => s.id);
  form.subjects = form.subjects.filter((p) => !visibleValues.includes(p));
};
</script>

<template>
  <Dialog v-model:open="isOpen">
    <DialogTrigger as-child>
      <slot />
    </DialogTrigger>
    <DialogContent
      class="sm:max-w-[700px] max-h-[90dvh] overflow-y-auto custom-scrollbar -webkit-overflow-scrolling-touch"
    >
      <DialogHeader class="p-6 pb-0">
        <DialogTitle>Add Staff</DialogTitle>
        <DialogDescription>
          Add teaching or subject staff to <b>{{ classroom.name }}</b>
        </DialogDescription>
      </DialogHeader>
      <div v-if="loading" class="space-y-3 px-4">
        <div v-for="n in 6" :key="n" class="flex items-center gap-3 animate-pulse">
          <div class="h-4 w-4 rounded bg-muted"></div>
          <div class="h-4 w-40 rounded bg-muted"></div>
          <div class="h-4 w-24 rounded bg-muted ml-auto"></div>
        </div>
      </div>

      <div v-if="form.errors">
        <template v-for="(msg, key) in form.errors" :key="key">
          <Alert v-show="key.startsWith('subjects')" class="border-green-200 bg-red-50">
            <CircleAlert class="h-4 w-4 text-red-600" />
            <AlertDescription class="text-red-800">
              <span v-if="Array.isArray(msg)" v-for="(m, i) in msg" :key="i">{{m}}</span>
              <span v-else>{{ msg }}</span>
            </AlertDescription>
          </Alert>
        </template>
      </div>

      <!-- Staff + Role selectors -->
      <form @submit.prevent="submit" id="assign-staff">
        <div v-if="!loading" class="px-4 py-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <SelectOption
              model="staff_id"
              :form="form"
              :options="staffData"
              label="Select Staff"
              placeholder="Select staff member"
              searchable
              required
            />
          </div>
          <div>
            <SelectOption
              model="role"
              :form="form"
              :options="staffRoles"
              label="Select Role"
              placeholder="Select staff role"
              required
            />
          </div>
        </div>
      </form>

      <div
        v-show="form.role === 'subject teacher'"
        v-if="filtered.length"
        class="px-4 pb-4 max-h-[60vh] overflow-y-auto custom-scrollbar -webkit-overflow-scrolling-touch"
      >
        <div
          class="p-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
        >
          <Input
            v-model="search"
            type="text"
            placeholder="Search subjects..."
            class="w-full sm:w-64 px-3 py-2 rounded-md bg-muted text-foreground focus:outline-none"
          />

          <div class="flex flex-col sm:flex-row gap-2 sm:justify-end">
            <Button size="sm" variant="outline" @click="selectAll"
              >Select All ({{ classroom.subjects.length }})</Button
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
        <Button :disabled="form.processing" type="submit" form="assign-staff">
          <LoaderCircle v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
          {{ form.processing ? "Please wait..." : "Save Changes" }}
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
