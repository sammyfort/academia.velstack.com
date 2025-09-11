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
import { InputSelectOption } from '@/types';
import SelectOption from "@/components/forms/SelectOption.vue";
import Datepicker from "@/components/forms/Datepicker.vue";

const props = defineProps<{
    available_semesters?: InputSelectOption[]
}>();

const isOpen = ref(false)
const form = useForm({
    name: "",
    start_date: "",
    end_date: "",
    status: "",
    days: "",
    next_term_begins: "",

})
const createSemester = ()=>{
    form.post(route('semesters.store'), {
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
                <DialogTitle>Add New Semester</DialogTitle>
                <DialogDescription>
                    Add New academic calender to your school
                </DialogDescription>
            </DialogHeader>
            <form @submit.prevent="createSemester" id="add-semester" class="grid gap-4 py-4">
                <SelectOption label="Semester" placeholder="Select" :options="available_semesters" :form="form" model="name" required  />
                <SelectOption label="Status" placeholder="Select"
                              :options="[{label: 'Active', value: 'active'}, {label: 'Ended', value: 'ended'}]"
                              :form="form" model="status" required  />
                <Datepicker :form="form" label="Start Date" model="start_date" type="date" required  />
                <Datepicker :form="form" label="End Date" model="end_date" type="date" required   />
                <InputText :form="form" label="Number of Days" model="days"    />
                <Datepicker :form="form" label="Next Term Begin" model="next_term_begins" type="date" required  />


            </form>
            <DialogFooter class="p-3">
                <Button :disabled="form.processing" type="submit" form="add-semester">
                    <LoaderCircle v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                    {{ form.processing ? 'Please wait...' : 'Add Semester' }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>

</template>

<style scoped>

</style>
