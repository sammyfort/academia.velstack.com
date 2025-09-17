<script setup lang="ts">
import AppLayout from "@/layouts/app/AppLayout.vue";
import {InputSelectOption, PaginatedDataI, RoleI, StudentI, Subject} from "@/types";
import {Table, TableBody, TableCell, TableHead, TableHeader, TableRow,} from "@/components/ui/table";
import {Input} from "@/components/ui/input";
import {Button} from "@/components/ui/button";
import {Checkbox} from "@/components/ui/checkbox"
import {
    PlusIcon,
    CreditCard,
    Edit,
    Trash2,
    Search,
    X,
    Upload,
    MoreVertical,
    MenuIcon,
    Calendar,
    Command,
    MonitorCheck,
    EyeIcon
} from "lucide-vue-next";
import SelectOption from "@/components/forms/SelectOption.vue";
import {ref, onMounted} from "vue";
import ConfirmDialogue from "@/components/helpers/ConfirmDialogue.vue";
import {Link,} from "@inertiajs/vue3";
import {dateAndTime} from "@/lib/helpers";
import {useDelete} from "@/composables/useDelete";
import Paginator from "@/components/helpers/Paginator.vue";
import Datepicker from "@/components/forms/Datepicker.vue";

const {isDeletingOne, isDeletingMany, deleteOne, deleteMany} = useDelete();
import {DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger,} from "@/components/ui/dropdown-menu";
import {useFilter} from "@/composables/useFilter";
import {useSelectable} from "@/composables/useSelectable";
import RoleCreate from "@/pages/Roles/RoleCreate.vue";

import RoleEdit from "@/pages/Roles/RoleEdit.vue";
import RolePermissions from "@/pages/Roles/RolePermissions.vue";
import {Badge} from "@/components/ui/badge";
import ManageRolePermissions from "@/pages/Roles/ManageRolePermissions.vue";

const props = defineProps<{
    roles: PaginatedDataI<RoleI>;
    filters: {
        search: string;
        paginate: string | number
        page: number;
        date: string;

    };
}>();
const {selected, allSelected, toggleSelect, toggleSelectAll, clearSelection} = useSelectable(props.roles.data || []);
const search = ref(props.filters.search || "");
const pagination = ref(props.filters.paginate || 10);
const date = ref<string | null>(props.filters.date ?? null);


const paginateOption = [
    {label: 'Show 1', value: 1},
    {label: 'Show 5', value: 5},
    {label: 'Show 10', value: 10},
    {label: 'Show 25', value: 25},
    {label: 'Show 50', value: 50},
    {label: 'Show 100', value: 100},
    {label: 'Show 500', value: 500},
    {label: 'Show All', value: 'all'}
]

const {goToPage, reset} = useFilter({
    sources: [search, pagination, date,],
    mapData: ([newSearch, newPagination, newDate,]) => ({
        search: newSearch,
        paginate: newPagination,
        date: newDate,
    }),
    only: ["roles", "filters"],
    debounceMs: 500,
    routeName: "roles.index",

});

</script>

<template>
    <AppLayout>
        <div class="rounded-2xl border border-border bg-background p-6 shadow-sm mt-4">
            <div class="mb-6 flex items-center justify-between">
                <h2 class="flex items-center gap-2 text-2xl font-bold text-foreground">
                    <CreditCard class="h-6 w-6 text-primary"/>
                    Roles ({{ props.roles.total }})
                </h2>
            </div>
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">

                    <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                        <div class="relative w-full sm:w-64">
                            <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground"/>
                            <Input v-model="search" placeholder="Search..." class="pl-10 w-full"/>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                            <SelectOption v-model="pagination" :options="paginateOption" placeholder="Showing results"
                                          class="w-full sm:w-auto"/>
                            <Datepicker v-model="date" placeholder="Filter by Date Added" class="w-full sm:w-auto"/>
                            <Button
                                v-if="search || props.roles.current_page > 1 || date"
                                @click="reset"
                                variant="outline"
                                size="sm"
                                class="flex items-center gap-1 whitespace-nowrap w-full sm:w-auto">
                                <X/>
                                Clear
                            </Button>

                        </div>
                        <!-- Bulk actions dropdown BELOW table header -->
                        <div v-if="selected.length" class="">
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <Button variant="outline" size="sm" class="flex items-center gap-2"><span>With Selected<span
                                        v-if="selected.length" class="text-muted-foreground">({{
                                            selected.length
                                        }})</span></span>
                                        <MenuIcon class="h-4 w-4"/>
                                    </Button>
                                </DropdownMenuTrigger>

                                <DropdownMenuContent align="start" class="w-40">
                                    <ConfirmDialogue
                                        @confirm="(done: () => void) => deleteMany('role.bulk-destroy', selected, done,() => selected = [])"
                                        :title="'Delete Roles'"
                                        :description="'Are you sure you want to delete the selected roles? This action cannot be undone.'"
                                        :confirmText="'Delete'"
                                        :cancelText="'Cancel'"
                                        :isProcessing="isDeletingMany"
                                        :loading="isDeletingMany"
                                    >
                                        <button
                                            class="flex items-center gap-2 w-full rounded-md px-2 py-1.5 text-sm text-red-50 hover:text-red-500 hover:bg-red-200 transition">
                                            <Trash2 class="h-4 w-4"/>
                                            <span>Delete</span>
                                        </button>
                                    </ConfirmDialogue>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </div>
                    </div>
                    <div class="w-full sm:w-auto">
                        <RoleCreate @created="$inertia.reload({ only: ['roles'] })">
                            <Button class="w-full sm:w-auto flex items-center gap-x-2 rounded-xl border
                                 border-white/30 bg-primary text-white backdrop-blur-sm transition-all duration-200 hover:scale-105 hover:bg-primary/50">
                                <PlusIcon class="h-5 w-5"/>
                                <span>Add New Role</span>
                            </Button>
                        </RoleCreate>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">

                <Table>
                    <TableHeader>
                        <TableRow>
                            <!-- Header checkbox -->
                            <TableHead class="w-10">
                                <Checkbox
                                    :model-value="allSelected"
                                    @update:model-value="toggleSelectAll"
                                />
                            </TableHead>

                            <TableHead class="text-left">Role Name</TableHead>
                            <TableHead class="text-left">Permissions</TableHead>
                            <TableHead class="text-left">Date Added</TableHead>
                            <TableHead class="text-left">Actions</TableHead>
                        </TableRow>
                    </TableHeader>

                    <TableBody>
                        <TableRow v-for="role in props.roles.data as RoleI[]" :key="role.id">
                            <TableCell class="w-10">
                                <Checkbox
                                    :model-value="selected.includes(role.id)"
                                    @update:model-value="(val: boolean) => toggleSelect(role.id, val)"
                                />
                            </TableCell>

                            <TableCell>
                                <span class="font-semibold">{{ role.name }}</span>
                            </TableCell>

                            <TableCell>
                                <Badge variant="outline" class="text-xs text-muted-foreground">
                                    {{ role.permissions.length }}
                                </Badge>
                            </TableCell>

                            <TableCell>
                                <div class="flex items-center gap-1.5 text-muted-foreground">
                                    <Calendar class="h-4 w-4"/>
                                    {{ dateAndTime(role.created_at, true) }}
                                </div>
                            </TableCell>

                            <TableCell class="w-1/3 border-0">
                                <div class="flex items-center gap-2">
                                    <!-- Manage Permissions -->
                                    <ManageRolePermissions :role="role">
                                        <Button
                                            class="w-full sm:w-auto flex items-center gap-x-2 rounded-xl border
                                            border-white/30 bg-primary text-white backdrop-blur-sm transition-all duration-200 hover:scale-105 hover:bg-primary/50"
                                        >
                                            <MonitorCheck class="h-5 w-5"/>
                                            <span>Manage Permissions</span>
                                        </Button>
                                    </ManageRolePermissions>

                                    <!-- View Permissions -->
                                    <RolePermissions :role="role">
                                        <Button class="w-full sm:w-auto flex items-center gap-x-2 rounded-xl border border-white/30
                                        bg-secondary text-white backdrop-blur-sm transition-all duration-200 hover:scale-105 hover:bg-primary/50"
                                        >
                                            <EyeIcon class="h-5 w-5"/>
                                            <span>View Permissions</span>
                                        </Button>
                                    </RolePermissions>

                                    <!-- Row Dropdown -->
                                    <DropdownMenu>
                                        <DropdownMenuTrigger as-child>
                                            <Button variant="ghost" size="icon">
                                                <MenuIcon/>
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent align="end" class="w-44 p-1">
                                            <!-- Edit -->
                                            <RoleEdit :role="role">
                                                <button
                                                    class="flex items-center gap-2 w-full rounded-md px-2 py-1.5 text-sm text-muted-foreground
                         hover:text-muted-foreground hover:bg-muted-background transition"
                                                >
                                                    <Edit class="h-4 w-4"/>
                                                    <span>Edit</span>
                                                </button>
                                            </RoleEdit>

                                            <!-- Delete -->
                                            <ConfirmDialogue
                                                @confirm="deleteOne('roles.destroy', role.id)"
                                                :title="'Delete Role'"
                                                :description="'Are you sure you want to delete this role? This action cannot be undone.'"
                                                :confirmText="'Delete'"
                                                :cancelText="'Cancel'"
                                                :isProcessing="isDeletingOne"
                                                :loading="isDeletingOne"
                                            >
                                                <button
                                                    class="flex items-center gap-2 w-full rounded-md px-2 py-1.5 text-sm text-red-500
                         hover:text-red-500 hover:bg-red-200 transition"
                                                >
                                                    <Trash2 class="h-4 w-4"/>
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
                    v-if="props.roles.last_page > 1"
                    :total="props.roles.total"
                    :per-page="props.roles.per_page"
                    :current-page="props.roles.current_page"
                    @page-change="goToPage"
                />
            </div>

            <div v-if="roles?.data?.length === 0" class="text-center py-8">
                <CreditCard class="h-12 w-12 text-muted-foreground mx-auto mb-4"/>
                <p class="text-muted-foreground">No role found</p>
            </div>
        </div>
    </AppLayout>
</template>
