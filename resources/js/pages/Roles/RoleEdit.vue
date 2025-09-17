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
import {InputSelectOption, RoleI} from '@/types';
import SelectOption from "@/components/forms/SelectOption.vue";

const props = defineProps<{
    role: RoleI
}>();

const isOpen = ref(false)
const form = useForm({
    name: props.role.name,
})
const createSubject = ()=>{
    form.put(route('roles.update'), {
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
                <DialogTitle>Update Role</DialogTitle>
                <DialogDescription>
                    Update Role in your school
                </DialogDescription>
            </DialogHeader>
            <form @submit.prevent="createSubject" id="add-role" class="grid gap-4 py-4">
                <InputText :form="form" label="Role Name" model="name" required   />

            </form>
            <DialogFooter class="p-3">
                <Button :disabled="form.processing" type="submit" form="add-role">
                    <LoaderCircle v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                    {{ form.processing ? 'Please wait...' : 'Save Changes' }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>

</template>

<style scoped>

</style>
