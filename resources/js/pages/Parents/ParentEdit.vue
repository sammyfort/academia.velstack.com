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
import {Guardian} from '@/types';


const props = defineProps<{
    parent?: Guardian
}>();

const isOpen = ref(false)
const form = useForm({
    name: props.parent.name,
    email: props.parent.email,
    phone: props.parent.phone,
    address: props.parent.address,
    occupation: props.parent.occupation,
    identity_number: props.parent.identity_number,

})
const updateParent = ()=>{
    form.put(route('parents.update', props.parent.id), {
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
                <DialogTitle>Update Parent</DialogTitle>
                <DialogDescription>
                    Update parent information
                </DialogDescription>
            </DialogHeader>
            <form @submit.prevent="updateParent" id="add-parent" class="grid gap-4 py-4">
                <InputText :form="form" model="name"  label="Full Name" required  />
                <InputText :form="form" model="email"  label="Email" type="email"  />
                <InputText :form="form" model="phone"  label="Phone" type="tel" required />
                <InputText :form="form" model="address"  label="Address"  required />
                <InputText :form="form" model="occupation"  label="Occupation"   />
                <InputText :form="form" model="identity_number"  label="Identity Number"   />
            </form>
            <DialogFooter class="p-3">
                <Button :disabled="form.processing" type="submit" form="add-parent">
                    <LoaderCircle v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                    {{ form.processing ? 'Please wait...' : 'Add Parent' }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>

</template>

<style scoped>

</style>
