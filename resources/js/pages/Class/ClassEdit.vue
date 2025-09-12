<script setup lang="ts">
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter, DialogHeader,
    DialogTitle,
    DialogTrigger
} from '@/components/ui/dialog';
import {Button} from '@/components/ui/button';
import InputText from '@/components/InputText.vue';
import {useForm} from '@inertiajs/vue3';
import {toastError, toastSuccess} from '@/lib/helpers';
import {ref, watch} from 'vue';
import {LoaderCircle} from 'lucide-vue-next';
import {Classroom, InputSelectOption} from '@/types';
import SelectOption from "@/components/forms/SelectOption.vue";
import axios from 'axios'

const props = defineProps<{
    classroom: Classroom
}>();

const isOpen = ref(false)
const levels = ref<InputSelectOption[]>([])
const groups = ref<InputSelectOption[]>([])
const loading = ref(false)

const form = useForm({
    name: props.classroom.name,
    level: props.classroom.level,
    group: props.classroom.group,

})

watch(isOpen, async (open) => {
    if (open && (!levels.value.length || !groups.value.length)) {
        loading.value = true
        try {
            const {data} = await axios.get(route('classes.create'))
            levels.value = data.levels
            groups.value = data.groups
        } catch (e) {
            toastError("Failed to load class data")
        } finally {
            loading.value = false
        }
    }
})
const updateClass = () => {
    form.put(route('classes.update', props.classroom.id), {
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
            <slot/>
        </DialogTrigger>
        <DialogContent class="sm:max-w-[500px] max-h-[90dvh] overflow-y-auto">
            <DialogHeader class="p-6 pb-0">
                <DialogTitle>Update  Class</DialogTitle>
                <DialogDescription>
                    Update Class to your school
                </DialogDescription>
            </DialogHeader>
            <form @submit.prevent="updateClass" id="add-subject" class="grid gap-4 py-4">
                <InputText :form="form" label="Class Name" model="name"/>
                <SelectOption label="Class Level"
                              placeholder="Class Level"
                              :options="levels"
                              :form="form"
                              model="level"
                              :loading="loading"
                              required/>

                <SelectOption label="Class Group"
                              placeholder="Class Group"
                              :options="groups"
                              :form="form"
                              model="group"
                              :loading="loading"
                              required/>
            </form>
            <DialogFooter class="p-3">
                <Button :disabled="form.processing" type="submit" form="add-subject">
                    <LoaderCircle v-if="form.processing" class="mr-2 h-4 w-4 animate-spin"/>
                    {{ form.processing ? 'Please wait...' : 'Update Class' }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>

</template>

<style scoped>

</style>
