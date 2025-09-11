<script setup lang="ts">
import AppLayout from "@/layouts/app/AppLayout.vue";
import {InputSelectOption, PaginatedDataI, Subject} from "@/types";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";

import { Badge } from "@/components/ui/badge";
import { PlusIcon, CreditCard, Calendar, Search, X } from "lucide-vue-next";
import SelectOption from "@/components/forms/SelectOption.vue";
import SubjectCreate from "@/pages/Subject/SubjectCreate.vue";
import { ref, watch, computed} from "vue";
import SubjectEdit from "./SubjectEdit.vue";
import ConfirmDialogue from "@/components/helpers/ConfirmDialogue.vue";
import { Head, Link, router } from '@inertiajs/vue3';
import {dateAndTime, toastError, toastSuccess} from '@/lib/helpers';
import debounce from "lodash/debounce";
import Paginator from "@/components/helpers/Paginator.vue";
const props = defineProps<{
  subjects: PaginatedDataI<Subject>
  available_subjects: InputSelectOption[]
    filters: {
        search: string
        page: number
    }
}>();


const search = ref(props.filters.search || "");


watch(
    search,
    debounce((value) => {
        router.reload({
            data: { search: value },
            only: ['subjects', 'filters'],
            preserveState: true,
            replace: true,
        });
    }, 200)
);



const filterOptions = [
    {label: 'Today', value: 'today'},
    {label: 'This Week', value: 'this_week'},
    {label: 'This Month', value: 'this_month'},
    {label: 'This Year', value: 'this_year'}
]

const filter = ref('');
const isDeleting = ref(false);

const handleDelete = (id: number|string) => {
    isDeleting.value = true;
    router.delete(route('subjects.destroy', id ), {
        onSuccess: (res) => {
            console.log(res)
            if (res.props.success) {
                toastSuccess(res.props.message);
            } else {
                toastError(res.props.message);
            }
        },
        onFinish: () => {
            isDeleting.value = false;
        },
    });
};

const goToPage = (page: number) => {
    router.get(route('subjects.index'), {
        page,
        search: search.value  || undefined,
    }, {
        preserveScroll: true,
        preserveState: true,
    });
};

const reset = () => {
    search.value = "";
    router.get(route("subjects.index"), { search: "", page: 1 }, {
        only: ["subjects", "filters"],
        replace: true,
        preserveScroll: true,
    });
};


</script>

<template>
  <AppLayout>
    <div
      class="rounded-2xl border border-border bg-background p-6 shadow-sm mt-4"
    >
      <!-- Header -->
      <div class="mb-6 flex items-center justify-between">
        <h2 class="flex items-center gap-2 text-2xl font-bold text-foreground">
          <CreditCard class="h-6 w-6 text-primary" />
          Subjects ({{props.subjects.total}})
        </h2>
      </div>
      <div class="mb-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
          <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
            <div class="relative flex-1 sm:w-85">
              <Search
                class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground"
              />
              <Input  v-model="search" placeholder="Search..." class="pl-10 w-full" />
            </div>
            <div class="flex items-center gap-2">
                <SelectOption placeholder="Filter by Date"
                              :options="filterOptions"
                              class="w-[200px]"
                              v-model="filter" />
              <Button
                  v-if="search || props.subjects.current_page > 1"
                  @click="reset"
                variant="outline"
                size="sm"
                class="flex items-center gap-1 whitespace-nowrap"
              >
                <X  />
                Clear
              </Button>
            </div>
          </div>

          <div class="w-full sm:w-auto mt-4 sm:mt-0">
          <SubjectCreate :available_subjects="available_subjects" @created="$inertia.reload({ only: ['subjects'] })">
          <Button
              class="w-full sm:w-auto flex items-center gap-x-2 rounded-xl border border-white/30 bg-primary text-white backdrop-blur-sm transition-all duration-200 hover:scale-105 hover:bg-primary/30"
            >
              <PlusIcon class="h-5 w-5" />
              <span>Add Subject</span>
            </Button>
          </SubjectCreate>

          </div>
        </div>
      </div>


      <div class="overflow-x-auto">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead class="text-left">Name</TableHead>
              <TableHead class="text-left">Classes</TableHead>
              <TableHead class="text-left">Students</TableHead>
              <TableHead class="text-left">Created</TableHead>
              <TableHead class="text-left">Actions</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="subject in props.subjects.data as Subject[]" :key="subject.id">
              <TableCell class="flex flex-col gap-2">
                <span class="font-semibold">{{ subject.name }}</span>
                <Badge variant="outline" class="text-xs">
                  <span>{{ subject.code }}</span>
                </Badge>
              </TableCell>
              <TableCell>{{ subject.classes_count }}</TableCell>
              <TableCell>{{ subject.students_count }}</TableCell>

              <TableCell>
                <div class="flex items-center gap-1.5 text-muted-foreground">
                  <Calendar class="h-4 w-4" />
                  {{ dateAndTime(subject.created_at, true )}}
                </div>
              </TableCell>

              <TableCell>
                <div class="flex gap-2">
                <SubjectEdit :subject="subject" :available_subjects="available_subjects"  @updated="$inertia.reload()">
                 <Button size="sm" variant="outline">Edit</Button>
                </SubjectEdit>

                  <ConfirmDialogue
                      :title="'Delete Subject'"
                      :description="'Are you sure you want to delete this subject? This action cannot be undone.'"
                      :confirmText="'Delete'"
                      :cancelText="'Cancel'"
                      :isProcessing="isDeleting"
                      @confirm="handleDelete(subject.id)"
                      :loading="isDeleting"
                    >
                      <Button size="sm" variant="destructive">Delete</Button>
                    </ConfirmDialogue>

                </div>
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </div>
        <div class="mt-8 flex justify-center w-full">
            <Paginator
                v-if="props.subjects.last_page > 1"
                       :total="props.subjects.total"
                       :per-page="props.subjects.per_page"
                       :current-page="props.subjects.current_page"
                       @page-change="goToPage"
            />
        </div>

      <div v-if="subjects?.data?.length === 0" class="text-center py-8">
        <CreditCard
          class="h-12 w-12 text-muted-foreground mx-auto mb-4"
        />
        <p class="text-muted-foreground">No subject found</p>
      </div>
    </div>
  </AppLayout>
</template>
