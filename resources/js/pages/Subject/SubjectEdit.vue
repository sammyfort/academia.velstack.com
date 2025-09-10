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
import { InputSelectOption, Subject } from '@/types';
import SelectOption from "@/components/forms/SelectOption.vue";
 
const props = defineProps<{
    subject: Subject
   available_subjects?: InputSelectOption[]
}>();

const isOpen = ref(false)
const form = useForm({
    name: props.subject.name,
    code: props.subject.code || "",
    

})
const createSubject = ()=>{
    form.post(route('subjects.update', props.subject.id), {
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
                    <DialogTitle>Edit Subject</DialogTitle>
                    <DialogDescription>
                        Edit Subject to your school
                    </DialogDescription>
                </DialogHeader>
                <form @submit.prevent="createSubject" id="add-subject" class="grid gap-4 py-4">

                 <SelectOption label="Subject Name" placeholder="Select Subject"
                  :options="available_subjects" :form="form" model="name" required  />
                    
                    <InputText :form="form" label="Subject code" model="code"   />
                 

                </form>
                <DialogFooter class="p-3">
                    <Button :disabled="form.processing" type="submit" form="add-subject">
                        <LoaderCircle v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                        {{ form.processing ? 'Please wait...' : 'Update Subject' }}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

</template>

<style scoped>

</style>
