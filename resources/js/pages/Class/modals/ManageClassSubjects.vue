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

import { toastError, toastSuccess } from "@/lib/helpers";
import { ref, watch,  } from "vue";

import { ClassroomI, SubjectI } from "@/types";
import axios from "axios";

import SubjectToClass from "../SubjectToClass.vue";
import SubjectToStudent from "../SubjectToStudent.vue";
const activeTab = ref<"class" | "students">("class");

const isOpen = ref(false);

const subjects = ref<SubjectI[]>([]);
const loading = ref(false);

const props = defineProps<{
  classroom: ClassroomI;
}>();
const emit = defineEmits(["updated"]);



watch(isOpen, async (open) => {
  if (open && !subjects.value.length) {
    loading.value = true;
    try {
      const { data } = await axios.get(route("api.subjects"));
      subjects.value = data.subjects;
    } catch (e) {
      toastError("Failed to load subjects data");
    } finally {
      loading.value = false;
    }
  }
});

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
        <DialogTitle>Mange Class Subjects</DialogTitle>
        <DialogDescription>
          Assign subjects to the class and students of <b>{{ classroom.name }}</b>
        </DialogDescription>
      </DialogHeader>
      <div v-if="loading" class="space-y-3 px-4">
        <div v-for="n in 6" :key="n" class="flex items-center gap-3 animate-pulse">
          <div class="h-4 w-4 rounded bg-muted"></div>
          <div class="h-4 w-40 rounded bg-muted"></div>
          <div class="h-4 w-24 rounded bg-muted ml-auto"></div>
        </div>
      </div>

      <!-- 🔹 Tabs -->
      <div v-else>
      <div class="flex border-b border-muted mb-4 px-4">
        <button
          @click="activeTab = 'class'"
          :class="[
            'px-4 py-2 text-sm font-medium',
            activeTab === 'class'
              ? 'border-b-2 border-primary text-primary'
              : 'text-muted-foreground hover:text-foreground',
          ]"
        >
          Manage Class
        </button>
        <button
          @click="activeTab = 'students'"
          :class="[
            'px-4 py-2 text-sm font-medium',
            activeTab === 'students'
              ? 'border-b-2 border-primary text-primary'
              : 'text-muted-foreground hover:text-foreground',
          ]"
        >
          Manage Students
        </button>
      </div>

      <SubjectToClass
       @updated="isOpen = false" 
      :subjects="subjects" v-if="activeTab === 'class'" 
      :classroom="classroom"
      />

      <SubjectToStudent 
        
      :class_subjects="classroom.subjects" 
      v-if="activeTab=== 'students'" 
      :classroom="classroom"/>
      
      </div>

    </DialogContent>
  </Dialog>
</template>
