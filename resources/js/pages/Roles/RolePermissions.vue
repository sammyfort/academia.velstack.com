<script setup lang="ts">
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger
} from '@/components/ui/dialog';
import {Button} from '@/components/ui/button';
import {ref} from 'vue';
import {InputSelectOption, RoleI} from '@/types';
import Badge from "@/components/ui/badge/Badge.vue";
const isOpen = ref(false)

const props = defineProps<{
    role: RoleI
}>();
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger as-child>
            <slot />
        </DialogTrigger>
        <DialogContent class="sm:max-w-[500px] max-h-[90dvh] overflow-y-auto">
            <DialogHeader class="p-6 pb-0">
                <DialogTitle>Role Permissions ({{role.name}})</DialogTitle>
                <DialogDescription>
                    Everyone with {{role.name}} role has the following permissions
                </DialogDescription>
            </DialogHeader>
            <div class="px-4 pb-4 max-h-[60vh] overflow-y-auto custom-scrollbar -webkit-overflow-scrolling-touch">
                <div v-if="role.permissions.length" class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    <Badge
                        v-for="perm in role.permissions"
                        :key="perm.id"
                        variant="outline"
                        class="text-xs px-2 py-1"
                    >{{ perm.name }}</Badge>
                </div>

                <div v-else
                    class="flex items-center justify-center text-muted-foreground text-sm py-6">
                    No permissions assigned
                </div>
            </div>

            <DialogFooter class="p-3">
                <Button variant="outline" @click="open = false">Close</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>


</template>

<style scoped>
</style>
