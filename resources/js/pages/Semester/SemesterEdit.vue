<script setup lang="ts">
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter, DialogHeader,
    DialogTitle,
    DialogTrigger
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import InputText from '@/components/InputText.vue';
import { useForm} from '@inertiajs/vue3';
import { toastError, toastSuccess } from '@/lib/helpers';
import {ref } from 'vue';
import { LoaderCircle } from 'lucide-vue-next';
import {InputSelectOption, Semester} from '@/types';
import SelectOption from "@/components/forms/SelectOption.vue";

const props = defineProps<{
    semester: Semester
    available_semesters?: InputSelectOption[]
}>();

const isOpen = ref(false)
const form = useForm({
    name: props.semester.name,
    start_date: props.semester.start_date,
    end_date: props.semester.end_date,
    status: props.semester.status,
    days: props.semester.days,
    next_term_begins: props.semester.next_term_begins,

})
const editSemester = ()=>{
    form.put(route('semesters.update', props.semester.id), {
        onSuccess: (res) => {
            const message = res.props.message
            if (res.props.success) toastSuccess(message)
            else toastError(message)
            isOpen.value = false
            form.reset()
        },
        preserveScroll: true
    })
}
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger as-child>
            <slot />
        </DialogTrigger>
        <DialogContent class="sm:max-w-[500px] max-h-[90dvh] overflow-y-auto">
            <DialogHeader class="p-6 pb-0">
                <DialogTitle>Update Semester</DialogTitle>
                <DialogDescription>
                    Update academic calender to your school
                </DialogDescription>
            </DialogHeader>
            <form @submit.prevent="editSemester" id="add-semester" class="grid gap-4 py-4">
                <SelectOption label="Semester" placeholder="Select" :options="available_semesters" :form="form" model="name" required  />
                <SelectOption label="Status" placeholder="Select"
                              :options="[{label: 'Active', value: 'active'}, {label: 'Ended', value: 'ended'}]"
                              :form="form" model="status" required  />
                <InputText :form="form" label="Start Date" model="start_date" type="date" required  />
                <InputText :form="form" label="End Date" model="end_date" type="date" required   />
                <InputText :form="form" label="Number of Days" model="days"    />
                <InputText :form="form" label="Next Term Begin" model="next_term_begins" type="date" required  />


            </form>
            <DialogFooter class="p-3">
                <Button :disabled="form.processing" type="submit" form="add-semester">
                    <LoaderCircle v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                    {{ form.processing ? 'Please wait...' : 'Update Semester' }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>

</template>

<style scoped>

</style>
