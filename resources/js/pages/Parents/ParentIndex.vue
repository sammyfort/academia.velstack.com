<script setup lang="ts">
import AppLayout from "@/layouts/app/AppLayout.vue";
import {Guardian, InputSelectOption, PaginatedDataI, Subject} from "@/types";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table";
import {Input} from "@/components/ui/input";
import {Button} from "@/components/ui/button";

import {Badge} from "@/components/ui/badge";
import {PlusIcon, CreditCard, Calendar, Search, X} from "lucide-vue-next";
import SelectOption from "@/components/forms/SelectOption.vue";
import ParentCreate from './ParentCreate.vue';
import {ref, watch, computed} from "vue";
import ParentEdit from "./ParentEdit.vue";
import ConfirmDialogue from "@/components/helpers/ConfirmDialogue.vue";
import {Head, Link, router,} from '@inertiajs/vue3';
import {dateAndTime, toastError, toastSuccess, useReloadOnChange} from '@/lib/helpers';

import Paginator from "@/components/helpers/Paginator.vue";
import Datepicker from "@/components/forms/Datepicker.vue";

const props = defineProps<{
    parents: PaginatedDataI<Guardian>
    filters: {
        search: string
        page: number
        date: string
    }
}>();


const search = ref(props.filters.search || "");
const date = ref<string | null>(props.filters.date ?? null);



useReloadOnChange({
    sources: [search, date],
    mapData: ([newSearch, newDate]) => ({
        search: newSearch,
        date: newDate,
    }),
    only: ["parents", "filters"],
    debounceMs: 500
});
const goToPage = (page: number) => {
    router.get(route('parents.index'), {
        page,
        search: search.value || undefined,
        date: date.value || undefined
    }, {
        preserveScroll: true,
        preserveState: true,
    });
};

const reset = () => {
    search.value = "";
    router.get(route("parents.index"),
        {search: "", date: "", page: 1},
        {
            only: ["parents", "filters"],
            replace: true,
            preserveScroll: true,
        });
};


const filterOptions = [
    {label: 'Today', value: 'today'},
    {label: 'This Week', value: 'this_week'},
    {label: 'This Month', value: 'this_month'},
    {label: 'This Year', value: 'this_year'}
]


const isDeleting = ref(false);
const handleDelete = (id: number | string) => {
    isDeleting.value = true;
    router.delete(route('parents.destroy', id), {
        onSuccess: (res) => {
            if (res.props.success)toastSuccess(res.props.message);
            else toastError(res.props.message);
        },
        onFinish: () => {
            isDeleting.value = false;
        },
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
                    <CreditCard class="h-6 w-6 text-primary"/>
                    Parents ({{ props.parents.total }})
                </h2>
            </div>
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                        <div class="relative flex-1 sm:w-85">
                            <Search
                                class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground"
                            />
                            <Input v-model="search" placeholder="Search..." class="pl-10 w-full"/>
                        </div>
                        <div class="flex items-center gap-2">
                            <Datepicker v-model="date" placeholder="Filter by Date Added"/>
                            <Button v-if="search || props.parents.current_page > 1 || date"
                                    @click="reset" variant="outline" size="sm" class="flex items-center gap-1 whitespace-nowrap">
                                <X/>
                                Clear
                            </Button>
                        </div>
                    </div>

                    <div class="w-full sm:w-auto mt-4 sm:mt-0">
                        <ParentCreate
                                       @created="$inertia.reload({ only: ['parents'] })">
                            <Button
                                class="w-full sm:w-auto flex items-center gap-x-2 rounded-xl border border-white/30 bg-primary text-white backdrop-blur-sm transition-all duration-200 hover:scale-105 hover:bg-primary/30"
                            >
                                <PlusIcon class="h-5 w-5"/>
                                <span>Add Parent</span>
                            </Button>
                        </ParentCreate>

                    </div>
                </div>
            </div>


            <div class="overflow-x-auto">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="text-left">Name</TableHead>
                            <TableHead class="text-left">Email</TableHead>
                            <TableHead class="text-left">Phone</TableHead>
                            <TableHead class="text-left">ID Number</TableHead>
                            <TableHead class="text-left">Occupation</TableHead>
                            <TableHead class="text-left">Address</TableHead>
                            <TableHead class="text-left">Created</TableHead>
                            <TableHead class="text-left">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="parent in props.parents.data as Guardian[]" :key="parent.id">

                            <TableCell>{{ parent.name }}</TableCell>
                            <TableCell>{{ parent.email }}</TableCell>
                            <TableCell>{{ parent.phone }}</TableCell>
                            <TableCell>
                                <div class="flex items-center gap-1.5 text-muted-foreground">
                                    <Calendar class="h-4 w-4"/>
                                    {{ parent.identity_number }}
                                </div>
                            </TableCell>
                            <TableCell>{{ parent.occupation }}</TableCell>
                            <TableCell>{{ parent.address }}</TableCell>
                            <TableCell>{{dateAndTime( parent.created_at) }}</TableCell>
                            <TableCell>
                                <div class="flex gap-2">
                                    <ParentEdit :parent="parent"
                                                 @updated="$inertia.reload()">
                                        <Button size="sm" variant="outline">Edit</Button>
                                    </ParentEdit>

                                    <ConfirmDialogue
                                        :title="'Delete Parent'"
                                        :description="'Are you sure you want to delete this parent? This action cannot be undone.'"
                                        :confirmText="'Delete'"
                                        :cancelText="'Cancel'"
                                        :isProcessing="isDeleting"
                                        @confirm="handleDelete(parent.id)"
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
                    v-if="props.parents.last_page > 1"
                    :total="props.parents.total"
                    :per-page="props.parents.per_page"
                    :current-page="props.parents.current_page"
                    @page-change="goToPage"
                />
            </div>

            <div v-if="parents?.data?.length === 0" class="text-center py-8">
                <CreditCard
                    class="h-12 w-12 text-muted-foreground mx-auto mb-4"
                />
                <p class="text-muted-foreground">No parent found</p>
            </div>
        </div>
    </AppLayout>
</template>
