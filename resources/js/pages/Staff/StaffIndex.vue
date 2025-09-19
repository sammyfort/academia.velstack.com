<script setup lang="ts">
import AppLayout from "@/layouts/app/AppLayout.vue";
import { InputSelectOption, PaginatedDataI, StaffI, StudentI } from "@/types";
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
import { Checkbox } from "@/components/ui/checkbox";
import {
  PlusIcon,
  CreditCard,
  Edit,
  Trash2,
  TrendingUp,
  Search,
  X,
  Upload,
  Users,
  MenuIcon,
  CheckCircle,
  UserCheck2,
} from "lucide-vue-next";
import SelectOption from "@/components/forms/SelectOption.vue";
import { ref, onMounted } from "vue";
import ConfirmDialogue from "@/components/helpers/ConfirmDialogue.vue";
import { Link } from "@inertiajs/vue3";
import { dateAndTime } from "@/lib/helpers";
import { useDelete } from "@/composables/useDelete";
import Paginator from "@/components/helpers/Paginator.vue";
import Datepicker from "@/components/forms/Datepicker.vue";
const { isDeletingOne, isDeletingMany, deleteOne, deleteMany } = useDelete();
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { useFilter } from "@/composables/useFilter";
import { useSelectable } from "@/composables/useSelectable";
import StatCard from "@/components/StatCard.vue";
const props = defineProps<{
  staff: PaginatedDataI<StaffI>;
  classes: InputSelectOption[];
  staffStatus: InputSelectOption[];
  filters: {
    search: string;
    paginate: string | number;
    page: number;
    date: string;
    status: string[];
  };
}>();
const {
  selected,
  allSelected,
  toggleSelect,
  toggleSelectAll,
  clearSelection,
} = useSelectable(props.staff.data || []);
const search = ref(props.filters.search || "");
const pagination = ref(props.filters.paginate || 10);
const date = ref<string | null>(props.filters.date ?? null);
const status = ref<string[]>(props.filters.status || []);

const paginateOption = [
  { label: "Show 1", value: 1 },
  { label: "Show 5", value: 5 },
  { label: "Show 10", value: 10 },
  { label: "Show 25", value: 25 },
  { label: "Show 50", value: 50 },
  { label: "Show 100", value: 100 },
  { label: "Show 500", value: 500 },
  { label: "Show All", value: "all" },
];

const { goToPage, reset } = useFilter({
  sources: [search, pagination, date, status],
  mapData: ([newSearch, newPagination, newDate, newStatus]) => ({
    search: newSearch,
    paginate: newPagination,
    date: newDate,
    status: newStatus && newStatus.length ? newStatus : undefined,
  }),
  only: ["staff", "filters"],
  debounceMs: 500,
  routeName: "staff.index",
});
</script>

<template>
  <AppLayout>
   <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <StatCard
          label="Total Staff"
          :value="props.staff.total"
          :icon="Users"
          value-color="text-green-600 dark:text-green-300"
          icon-color="text-green-600 dark:text-green-300"
          icon-bg="bg-green-50 dark:bg-green-900/20"
        />

        <StatCard
          label="Suspended"
          :value="2"
          :icon="CheckCircle"
          value-color="text-blue-600 dark:text-blue-400"
          icon-color="text-blue-600 dark:text-blue-500"
          icon-bg="bg-blue-50 dark:bg-blue-900/30"
        />

        <StatCard
          label="Stopped"
          :value="1"
          :icon="UserCheck2"
          value-color="text-yellow-600 dark:text-yellow-400"
          icon-color="text-yellow-600 dark:text-yellow-500"
          icon-bg="bg-yellow-50 dark:bg-yellow-900/30"
        />
        <StatCard
          label="Average"
          value="10"
          :icon="TrendingUp"
          value-color="text-purple-600 dark:text-purple-400"
          icon-color="text-purple-600 dark:text-purple-500"
          icon-bg="bg-purple-50 dark:bg-purple-900/30"
        />
      </div>
    <div class="rounded-2xl border border-border bg-background p-6 shadow-sm mt-4">
     
      <!-- Header -->
      <div class="mb-6 flex items-center justify-between">
        <h2 class="flex items-center gap-2 text-2xl font-bold text-foreground">
          <CreditCard class="h-6 w-6 text-primary" />
          Staff ({{ props.staff.total }})
        </h2>
      </div>
      <div class="mb-6">
        <div
          class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4"
        >
          <!-- Filters block -->
          <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
            <!-- Search -->
            <div class="relative w-full sm:w-64">
              <Search
                class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground"
              />
              <Input v-model="search" placeholder="Search..." class="pl-10 w-full" />
            </div>

            <!-- Filters wrap -->
            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
              <SelectOption
                v-model="pagination"
                :options="paginateOption"
                placeholder="Showing results"
                class="w-full sm:w-auto"
              />

              <SelectOption
                v-model="status"
                :options="staffStatus"
                placeholder="Sort by status"
                multiple
                class="w-full sm:w-auto"
              />

              <Datepicker
                v-model="date"
                placeholder="Filter by Date Added"
                class="w-full sm:w-auto"
              />

              <Button
                v-if="search || status.length || props.staff.current_page > 1 || date"
                @click="reset"
                variant="outline"
                size="sm"
                class="flex items-center gap-1 whitespace-nowrap w-full sm:w-auto"
              >
                <X />
                Clear
              </Button>
            </div>
          </div>

          <!-- Add New Student -->
          <div class="w-full sm:w-auto">
            <Link
              :href="route('staff.create')"
              class="w-full sm:w-auto flex items-center justify-center gap-x-2 rounded-xl border py-2 px-2 border-white/30 bg-primary text-white backdrop-blur-sm transition-all duration-200 hover:scale-105 hover:bg-primary/70"
            >
              <PlusIcon class="h-5 w-5" />
              <span>Add New Staff</span>
            </Link>
          </div>
        </div>
      </div>

      <div class="overflow-x-auto">
        <Table class="w-full">
          <TableHeader v-if="staff.data?.length">
            <TableRow>
              <TableHead class="w-10">
                <Checkbox
                  :model-value="allSelected"
                  @update:model-value="toggleSelectAll"
                />
              </TableHead>
              <TableHead class="w-1/3">
                <div class="flex items-center gap-2">
                  <span>Select All</span>
                  <DropdownMenu v-if="selected.length">
                    <DropdownMenuTrigger as-child>
                      <Button variant="outline" size="sm" class="flex items-center gap-2">
                        <span
                          >With Select
                          <span v-if="selected.length" class="text-muted-foreground"
                            >({{ selected.length }})</span
                          ></span
                        >
                        <MenuIcon class="h-4 w-4" />
                      </Button>
                    </DropdownMenuTrigger>

                    <DropdownMenuContent align="start" class="w-40">
                      <DropdownMenuItem class="flex items-center gap-2">
                        <Upload class="h-4 w-4" />
                        <span>Import</span>
                      </DropdownMenuItem>
                      <ConfirmDialogue
                        @confirm="(done: () => void ) => deleteMany('staffs.bulk-destroy', selected, done,
                                               () => selected = [])"
                        :title="'Delete Students'"
                        :description="'Are you sure you want to delete the selected staff? This action cannot be undone.'"
                        :confirmText="'Delete'"
                        :cancelText="'Cancel'"
                        :isProcessing="isDeletingMany"
                        :loading="isDeletingMany"
                      >
                        <button
                          class="flex items-center gap-2 w-full rounded-md px-2 py-1.5 text-sm text-red-500 hover:text-red-500 hover:bg-red-200 transition"
                        >
                          <Trash2 class="h-4 w-4" />
                          <span>Delete</span>
                        </button>
                      </ConfirmDialogue>
                    </DropdownMenuContent>
                  </DropdownMenu>
                </div>
              </TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow
              v-for="employee in props.staff.data as StaffI[]"
              :key="employee.id"
              class="border-b border-border hover:bg-muted/30 transition-colors"
            >
              <TableCell class="w-10">
                <Checkbox
                  :model-value="selected.includes(employee.id)"
                  @update:model-value="(val: boolean) => toggleSelect(employee.id, val)"
                />
              </TableCell>
              <TableCell class="w-1/3 min-w-[200px] border-0">
                <div class="flex items-center gap-3">
                  <img
                    :src="employee.image"
                    alt="avatar"
                    class="h-12 w-12 rounded-full border border-border object-cover"
                  />
                  <div class="min-w-0">
                    <h3 class="truncate font-semibold text-foreground uppercase">
                      {{ employee.fullname }}
                    </h3>
                    <p class="text-sm text-muted-foreground truncate">
                      {{ employee?.title }}
                    </p>
                  </div>
                </div>
              </TableCell>
              <TableCell class="w-1/3 hidden md:table-cell border-0 text-sm align-top">
                <div class="flex flex-col gap-2">
                  <p class="text-sm text-muted-foreground">Staff ID</p>
                  <p class="font-medium text-foreground">
                    {{ employee.staff_id }}
                  </p>
                </div>
              </TableCell>
              <TableCell class="w-1/3 hidden md:table-cell border-0 text-sm align-top">
                <div class="flex flex-col gap-2">
                  <p class="text-sm text-muted-foreground">Employment Date</p>
                  <p class="font-medium text-foreground">
                    {{ dateAndTime(employee.created_at) }}
                  </p>
                </div>
              </TableCell>
              <TableCell class="w-1/3 border-0 text-right">
                <div class="flex items-center justify-end gap-2">
                  <Link
                    :href="route('staff.show', employee.id)"
                    class="px-3 py-1.5 rounded-md border text-sm font-medium hover:bg-muted transition"
                  >
                    View Profile
                  </Link>

                  <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                      <Button variant="ghost" size="icon"> <MenuIcon /> </Button>
                    </DropdownMenuTrigger>

                    <DropdownMenuContent align="end" class="w-44 p-1">
                      <Link
                        :href="route('staff.edit', employee.id)"
                        class="flex items-center gap-2 w-full rounded-md px-2 py-1.5 text-sm text-foreground hover:bg-muted focus:bg-muted transition"
                      >
                        <Edit class="h-4 w-4 text-muted-foreground" />
                        <span>Edit</span>
                      </Link>
                      <ConfirmDialogue
                        @confirm="deleteOne('staff.destroy', employee.id)"
                        :title="'Delete Staff'"
                        :description="'Are you sure you want to delete this staff? This action cannot be undone.'"
                        :confirmText="'Delete'"
                        :cancelText="'Cancel'"
                        :isProcessing="isDeletingOne"
                        :loading="isDeletingOne"
                      >
                        <button
                          class="flex items-center gap-2 w-full rounded-md px-2 py-1.5 text-sm text-red-500 hover:text-red-500 hover:bg-red-200 transition"
                        >
                          <Trash2 class="h-4 w-4" />
                          <span>Delete</span>
                        </button>
                      </ConfirmDialogue>
                    </DropdownMenuContent>
                  </DropdownMenu>
                </div>
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </div>

      <div class="mt-8 flex justify-center w-full">
        <Paginator
          v-if="props.staff.last_page > 1"
          :total="props.staff.total"
          :per-page="props.staff.per_page"
          :current-page="props.staff.current_page"
          @page-change="goToPage"
        />
      </div>

      <div v-if="staff?.data?.length === 0" class="text-center py-8">
        <CreditCard class="h-12 w-12 text-muted-foreground mx-auto mb-4" />
        <p class="text-muted-foreground">No staff found</p>
      </div>
    </div>
  </AppLayout>
</template>
