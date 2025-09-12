<script setup lang="ts">
import AppLayout from "@/layouts/app/AppLayout.vue";
import { Classroom, PaginatedDataI } from "@/types";
import {Input} from "@/components/ui/input";
import {PlusIcon, Search, X, Building2, GraduationCap} from "lucide-vue-next";
import Datepicker from "@/components/forms/Datepicker.vue";
import {Button} from "@/components/ui/button";
import ConfirmDialogue from "@/components/helpers/ConfirmDialogue.vue";
import ClassEdit from "@/pages/Class/ClassEdit.vue";
import ClassCreate from "@/pages/Class/ClassCreate.vue";
import Paginator from "@/components/helpers/Paginator.vue";
import {ref, watch} from "vue";
import {router} from "@inertiajs/vue3";
import {useReloadOnChange} from "@/lib/helpers";

const props = defineProps<{
    classes: PaginatedDataI<Classroom>
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
    only: ["classes", "filters"],
    debounceMs: 500
});

const goToPage = (page: number) => {
    router.get(route('classes.index'), {
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
    router.get(route("classes.index"),
        {search: "", date: "", page: 1},
        {
            only: ["classes", "filters"],
            replace: true,
            preserveScroll: true,
        });
};

</script>

<template>
    <AppLayout>
        <div class="  bg-background py-8">
            <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header Section -->
                <div class="mb-12">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                            <div class="relative flex-1 sm:w-85">
                                <Search
                                    class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground"
                                />
                                <Input v-model="search" placeholder="Search..." class="pl-10 w-full"/>
                            </div>
                            <div class="flex items-center gap-2">
                                <Datepicker v-model="date"   placeholder="Filter by Date Added"/>
                                <Button  v-if="search || props.classes.current_page > 1 || date"
                                         @click="reset"  variant="outline" size="sm" class="flex items-center gap-1 whitespace-nowrap">
                                    <X/>
                                    Clear
                                </Button>
                            </div>
                        </div>

                        <div class="w-full sm:w-auto mt-4 sm:mt-0">
                            <ClassCreate
                                           @created="$inertia.reload({ only: ['classes'] })">
                                <Button
                                    class="w-full sm:w-auto flex items-center gap-x-2 rounded-xl
                                    border border-white/30 bg-primary text-white backdrop-blur-sm transition-all
                                     duration-200 hover:scale-105 hover:bg-primary/30"
                                >
                                    <PlusIcon class="h-5 w-5"/>
                                    <span>Add New Class</span>
                                </Button>
                            </ClassCreate>

                        </div>
                    </div>
                </div>

                <!-- Classes Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
                    <div
                        v-for="classItem in classes.data as Classroom[]"
                        :key="classItem.id"
                        @click="$inertia.visit(route('classes.show', classItem.slug))"
                        class="relative bg-background rounded-xl shadow-sm border border-foreground-200 hover:shadow-md transition-all duration-200 group cursor-pointer"
                    >
                        <!-- Inner clickable content -->
                        <div class="p-6 pb-4">
                            <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-primary rounded-xl shadow-lg">
                                <Building2 class="text-white" />
                            </div>

                            <div class="text-center">
                                <h3 class="text-lg font-semibold text-foreground mb-1">{{ classItem.name }}</h3>
                                <p class="text-sm text-foreground">{{ classItem.students_count }} Student(s)</p>
                            </div>
                        </div>
                        <div class="px-6 pb-6">
                            <div class="flex justify-center gap-2">
                                <ClassEdit :classroom="classItem" @updated="$inertia.reload()">
                                    <Button  @click.stop size="sm" variant="outline">Edit</Button>
                                </ClassEdit>
                                <ConfirmDialogue
                                    :title="'Delete Class'"
                                    :description="'Are you sure you want to delete this class? This action cannot be undone.'"
                                    :confirmText="'Delete'"
                                    :cancelText="'Cancel'"
                                >
                                    <Button  @click.stop size="sm" variant="destructive">Delete</Button>
                                </ConfirmDialogue>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-center w-full">
                    <Paginator
                        v-if="props.classes.last_page > 1"
                        :total="props.classes.total"
                        :per-page="props.classes.per_page"
                        :current-page="props.classes.current_page"
                        @page-change="goToPage"
                    />
                </div>

                <div v-if="classes?.data?.length === 0" class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-primary rounded-full">
                         <GraduationCap/>
                    </div>
                    <h3 class="text-lg font-medium text-foreground mb-1">No classes found</h3>
                    <p class="text-foreground mb-4">Adjust your filter or get started by creating your first class.</p>
                </div>

            </div>
        </div>
    </AppLayout>
</template>
