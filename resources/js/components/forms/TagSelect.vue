<script setup lang="ts">
import { ref, computed, watch, onMounted, onUnmounted } from 'vue'
import { Label } from '@/components/ui/label'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { X, ChevronDown, Search, Plus } from 'lucide-vue-next'
import InputError from "@/components/InputError.vue";
import {InputSelectOption} from "@/types";



interface Props {
    form: Record<string, any>
    model: string
    label?: string
    placeholder?: string
    options: InputSelectOption[]
    size?: number | 'unlimited'
    searchable?: boolean
    addable?: boolean
    disabled?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    options: () => [],
    size: 'unlimited',
    searchable: true,
    addable: false,
    disabled: false,
    placeholder: 'Select options...'
})

const searchQuery = ref('')
const open = ref(false)
const dropdownRef = ref<HTMLElement>()
const internalOptions = ref<InputSelectOption[]>([...props.options])

const isMultiple = computed(() => props.size !== 1)
const maxReached = computed(() => {
    if (props.size === 'unlimited') return false
    if (!isMultiple.value) return false
    return Array.isArray(props.form[props.model]) && props.form[props.model].length >= props.size
})

const selectedValues = computed(() => {
    if (isMultiple.value) {
        return Array.isArray(props.form[props.model]) ? props.form[props.model] : []
    }
    return props.form[props.model] ? [props.form[props.model]] : []
})

const filteredOptions = computed(() => {
    if (!props.searchable || !searchQuery.value) return internalOptions.value
    return internalOptions.value.filter(option =>
        option.label.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
})

const availableOptions = computed(() => {
    return filteredOptions.value.filter(option =>
        !selectedValues.value.includes(option.value)
    )
})

function handleClickOutside(event: MouseEvent) {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target as Node)) {
        open.value = false
    }
}

onMounted(() => {
    document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside)
})

watch(() => props.options, (newOptions) => {
    internalOptions.value = [...newOptions]
}, { immediate: true })

watch(open, (val) => {
    if (!val) {
        searchQuery.value = ''
    }
})

function selectOption(value: string) {
    if (isMultiple.value) {
        const current = Array.isArray(props.form[props.model]) ? [...props.form[props.model]] : []
        if (!current.includes(value) && !maxReached.value) {
            current.push(value)
            props.form[props.model] = current
        }
    } else {
        props.form[props.model] = value
        open.value = false
    }
}

function removeTag(value: string) {
    if (isMultiple.value) {
        props.form[props.model] = props.form[props.model].filter((v: string) => v !== value)
    } else {
        props.form[props.model] = ''
    }
}

function addNewTag() {
    if (!props.addable) return

    const newValue = searchQuery.value.trim()
    if (!newValue) return

    const exists = internalOptions.value.some(opt =>
        opt.label.toLowerCase() === newValue.toLowerCase()
    )

    if (!exists) {
        internalOptions.value.push({ label: newValue, value: newValue })
    }

    selectOption(newValue)
    searchQuery.value = ''
}

function getOptionLabel(value: string) {
    return internalOptions.value.find(opt => opt.value === value)?.label || value
}
</script>

<template>
    <div class="space-y-2">
        <Label v-if="label">{{ label }}</Label>

        <div class="relative" ref="dropdownRef">
            <div
                @click="!disabled && (open = !open)"
                class="flex min-h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm cursor-pointer"
                :class="{ 'cursor-not-allowed opacity-50': disabled }">
                <div class="flex flex-1 flex-wrap items-center gap-1">
                    <template v-if="isMultiple && selectedValues.length > 0">
                        <Badge
                            v-for="value in selectedValues"
                            :key="value"
                            variant="secondary"
                            class="gap-1"
                        >
                            {{ getOptionLabel(value) }}
                            <Button
                                @click.stop="removeTag(value)"
                                variant="ghost"
                                size="sm"
                                class="h-auto p-0.5 hover:bg-destructive hover:text-destructive-foreground"
                            >
                                <X class="h-3 w-3" />
                            </Button>
                        </Badge>
                    </template>
                    <template v-else-if="!isMultiple && selectedValues.length > 0">
                        <span>{{ getOptionLabel(selectedValues[0]) }}</span>
                    </template>
                    <template v-else>
                        <span class="text-muted-foreground">{{ placeholder }}</span>
                    </template>
                </div>

                <ChevronDown
                    class="h-4 w-4 shrink-0 opacity-50 transition-transform duration-200"
                    :class="{ 'rotate-180': open }"
                />
            </div>
            <div
                v-if="open && !disabled"
                class="absolute z-50 mt-1 w-full rounded-md border bg-white shadow-lg overflow-hidden">
                <div v-if="searchable" class="p-2 border-b">
                    <div class="relative">
                        <Search class="absolute left-2 top-2.5 h-4 w-4 text-muted-foreground" />
                        <input
                            v-model="searchQuery"
                            @keyup.enter="addNewTag"
                            @click.stop
                            placeholder="Search or add new..."
                            class="w-full pl-8 pr-2 py-2 text-sm border rounded focus:outline-none focus:ring-2 focus:ring-ring"
                        />
                    </div>
                </div>
                <div class="max-h-60 overflow-y-auto">
                    <div
                        v-for="option in availableOptions"
                        :key="option.value"
                        @click="selectOption(option.value)"
                        class="px-3 py-2 text-sm cursor-pointer hover:bg-gray-100 flex items-center justify-between"
                        :class="{ 'pointer-events-none opacity-50': maxReached }"
                    >
                        <span>{{ option.label }}</span>
                        <div
                            v-if="selectedValues.includes(option.value)"
                            class="w-4 h-4 bg-primary rounded-full flex items-center justify-center"
                        >
                            <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <div
                        v-if="addable && searchQuery.trim() && !internalOptions.some(opt => opt.label.toLowerCase() === searchQuery.toLowerCase())"
                        @click="addNewTag"
                        class="px-3 py-2 text-sm cursor-pointer hover:bg-gray-100 border-t flex items-center text-primary"
                    >
                        <Plus class="mr-2 h-4 w-4" />
                        Add "{{ searchQuery.trim() }}"
                    </div>
                    <div
                        v-else-if="availableOptions.length === 0 && (!addable || !searchQuery.trim())"
                        class="px-3 py-2 text-sm text-gray-500 text-center"
                    >
                        {{ searchQuery.trim() ? 'No results found' : 'Type to search...' }}
                    </div>
                </div>
            </div>
        </div>
        <InputError v-if="form.errors?.[model]" :message="form.errors[model]" />

    </div>
</template>
