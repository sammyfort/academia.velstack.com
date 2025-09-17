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
import {useForm} from '@inertiajs/vue3';
import {toastError, toastSuccess} from '@/lib/helpers';
import {ref, watch, computed, onMounted} from 'vue';
import {LoaderCircle, Lock} from 'lucide-vue-next';
import {InputSelectOption, RoleI} from '@/types';
import axios from "axios";
import {router} from "@inertiajs/vue3";
import Input from "@/components/ui/input/Input.vue";

const isOpen = ref(false)
const search = ref("")
const permissions = ref<InputSelectOption[]>([])
const loading = ref(false)


const props = defineProps<{
    role: RoleI
}>();


const form = useForm({
    role_id: props.role.id,
    permissions: props.role.permissions.map(p => p.name) || [],
})


watch(isOpen, async (open) => {
    if (open && !permissions.value.length) {
        loading.value = true
        try {
            const {data} = await axios.get(route('roles.create'))
            permissions.value = data.permissions
        } catch (e) {
            toastError("Failed to load permissions data")
        } finally {
            loading.value = false
        }
    }
})


const filteredPermissions = computed(() => {
    if (!search.value) return permissions.value
    return permissions.value.filter(p =>
        p.label.toLowerCase().includes(search.value.toLowerCase())
    )
})
const selectAll = () => {
    // Add all visible filtered permissions
    const allValues = permissions.value.map(p => p.value)
    form.permissions = Array.from(new Set([...form.permissions, ...allValues]))
}

const unselectAll = () => {
    // Remove only the visible filtered permissions
    const visibleValues = permissions.value.map(p => p.value)
    form.permissions = form.permissions.filter(p => !visibleValues.includes(p))
}


const updatePermissions = () => {
    console.log(form.permissions)
    form.post(route('manage-role.permission'), {
        onSuccess: (res) => {
            const message = res.props.message
            if (res.props.success) toastSuccess(message)
            else toastError(message)
            isOpen.value = false
            router.reload({only: ['role']})
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
        <DialogContent class="sm:max-w-[700px] max-h-[90dvh] overflow-y-auto">
            <DialogHeader class="p-6 pb-0">
                <DialogTitle>Assign Permissions</DialogTitle>
                <DialogDescription>
                    Add or revoke privileges for <b>{{ role.name }}</b>
                </DialogDescription>
            </DialogHeader>
            <!-- Loading skeleton -->
            <div v-if="loading" class="space-y-3 px-4">
                <div
                    v-for="n in 6"
                    :key="n"
                    class="flex items-center gap-3 animate-pulse"
                >
                    <div class="h-4 w-4 rounded bg-muted"></div>
                    <div class="h-4 w-40 rounded bg-muted"></div>
                    <div class="h-4 w-24 rounded bg-muted ml-auto"></div>
                </div>
            </div>

            <div v-else>
                <div class="p-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <!-- Search -->
                    <Input
                        v-model="search"
                        type="text"
                        placeholder="Search permission..."
                        class="w-full sm:w-64 px-3 py-2 rounded-md bg-muted text-foreground focus:outline-none"
                    />

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row gap-2 sm:justify-end">
                        <Button
                            size="sm"
                            variant="outline"

                            @click="selectAll"
                        >
                            Select All ({{ permissions.length }})
                        </Button>

                        <Button
                            size="sm"
                            variant="destructive"

                            @click="unselectAll"
                        >
                            Unselect All ({{ form.permissions.length }})
                        </Button>
                    </div>
                </div>


                <!-- Permissions Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-border rounded-lg text-sm">
                        <thead class="bg-muted-background text-foreground">
                        <tr>
                            <th class="px-4 py-2 text-left">Permission</th>
                            <th class="px-4 py-2 text-left">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr
                            v-for="permission in filteredPermissions"
                            :key="permission.id"
                            class="border-t border-border hover:bg-muted/30"
                        >
                            <td class="px-4 py-2 flex items-center gap-2">
                                <Lock class="h-4 w-4 text-muted-foreground"/>
                                {{ permission.label }}
                            </td>
                            <td class="px-4 py-2">
                                <Button
                                    size="sm"
                                    variant="outline"
                                    class="px-3 py-1 rounded-md text-xs font-medium bg-green-400 text-black hover:bg-green-500 transition"
                                    v-if="!form.permissions.includes(permission.value)"
                                    @click="form.permissions.push(permission.value)"
                                >
                                    Add
                                </Button>

                                <Button
                                    size="sm"
                                    variant="destructive"
                                    class="px-3 py-1 rounded-md text-xs font-medium bg-red-400 text-black hover:bg-red-500 transition"
                                    v-else
                                    @click="form.permissions = form.permissions.filter(perm => perm !== permission.value)"
                                >
                                    Remove
                                </Button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Footer -->
                <DialogFooter class="p-3">
                    <Button
                        :disabled="form.processing"
                        type="submit"
                        form="add-permissions"
                        @click="updatePermissions"
                    >
                        <LoaderCircle
                            v-if="form.processing"
                            class="mr-2 h-4 w-4 animate-spin"
                        />
                        {{ form.processing ? 'Please wait...' : 'Save Changes' }}
                    </Button>
                </DialogFooter>
            </div>
        </DialogContent>
    </Dialog>
</template>
