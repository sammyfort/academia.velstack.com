<script setup lang="ts">
import { ref, defineEmits } from 'vue'
import {
    AlertDialog,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/components/ui/alert-dialog'
import { Button } from '@/components/ui/button'

const props = defineProps<{
    title?: string
    description?: string
    confirmText?: string
    cancelText?: string
    loading?: boolean
}>()

const isOpen = ref(false)
const emit = defineEmits<{
    (e: 'confirm', done: () => void): void
}>()

const handleConfirm = () => {
    emit('confirm', () => {
        isOpen.value = false
    })
}
</script>

<template>
    <AlertDialog v-model:open="isOpen">
        <AlertDialogTrigger as-child>
            <slot />
        </AlertDialogTrigger>

        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>{{ props.title || 'Are you sure?' }}</AlertDialogTitle>
                <AlertDialogDescription>
                    {{ props.description || 'This action cannot be undone.' }}
                </AlertDialogDescription>
            </AlertDialogHeader>

            <AlertDialogFooter>
                <AlertDialogCancel :disabled="props.loading">
                    {{ props.cancelText || 'Cancel' }}
                </AlertDialogCancel>

                <Button :disabled="props.loading" @click="handleConfirm">
                    {{ props.loading ? 'Please wait...' : props.confirmText || 'Confirm' }}
                </Button>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
