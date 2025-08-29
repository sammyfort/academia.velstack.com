<script setup lang="ts">
import InputError from '@/components/InputError.vue'
import { Label } from '@/components/ui/label'
import { Input } from '@/components/ui/input'

import { useAttrs, ref, computed, watch, onMounted, onUnmounted } from 'vue'
import { InputSelectOption } from '@/types';

type Props = {
    form?: Record<string, any>
    model?: string
    label?: string
    options: InputSelectOption[]
    searchable?: boolean
    taggable?: boolean
}

const props = defineProps<Props>()
const attrs = useAttrs()
const id = Math.random().toString(36).substring(2, 10)

const searchQuery = ref('')
const open = ref(false)
const dropdownRef = ref<HTMLElement>()
const searchInput = ref<HTMLInputElement>()

const filteredOptions = computed(() => {
    if (!props.searchable || !searchQuery.value) return props.options
    return props.options.filter(o =>
        o.label.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
})

const selectedValues = ref<string[]>([])

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

watch(open, (val) => {
    if (!val) {
        searchQuery.value = ''
    } else if (val && props.searchable && !props.taggable) {
        setTimeout(() => {
            //searchInput.value?.focus()
        }, 100)
    }
})

watch(
    () => props.form?.[props.model!],
    (newVal) => {
        if (props.taggable && Array.isArray(newVal)) {
            selectedValues.value = newVal
        }
    },
    { immediate: true }
)

function toggleTag(value: string) {
    if (!props.form || !props.model) return

    const modelValue = props.form[props.model]
    const index = modelValue.indexOf(value)

    if (index === -1) {
        props.form[props.model].push(value)
    } else {
        props.form[props.model].splice(index, 1)
    }
}

function addNewTag() {
    if (!props.form || !props.model) return

    const newValue = searchQuery.value.trim()
    if (!newValue) return

    const exists = props.options.some(opt => opt.label === newValue)
    let newOption

    if (!exists) {
        newOption = { label: newValue, value: newValue }
        props.options.push(newOption)
    } else {
        newOption = props.options.find(opt => opt.label === newValue)!
    }

    if (!Array.isArray(props.form[props.model])) {
        props.form[props.model] = []
    }

    if (!props.form[props.model].includes(newOption.value)) {
        props.form[props.model].push(newOption.value)
    }

    searchQuery.value = ''
}

function removeTag(value: string) {
    if (!props.form || !props.model) return
    if (!Array.isArray(props.form[props.model])) return

    props.form[props.model] = props.form[props.model].filter((v: string) => v !== value)
}

function selectOption(value: string) {
    if (!props.form || !props.model) return
    props.form[props.model] = value
    open.value = false
}
</script>

<template>
    <div class="grid gap-2">
        <Label :for="id">
            {{ props.label }}
            <span class="text-destructive" v-show="props.label && attrs.hasOwnProperty('required')">*</span>
        </Label>

        <div v-if="props.form && props.model">
            <template v-if="props.taggable">
                <div class="relative" ref="dropdownRef">
                    <div
                        class="flex min-h-10 w-full items-center justify-between rounded-md border border-input bg-gray-50 px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 cursor-pointer gap-2 flex-wrap"
                        @click="open = !open"
                    >
                        <div class="flex flex-wrap items-center gap-2">
                            <span
                                v-for="tag in props.form[props.model]"
                                :key="tag"
                                class="inline-flex items-center gap-1 bg-primary text-primary-foreground px-2 py-1 rounded-md text-sm font-medium shadow-sm hover:bg-primary/90 transition-colors"
                            >
                                {{ props.options.find(opt => opt.value === tag)?.label || tag }}
                                <button
                                    @click.stop="removeTag(tag)"
                                    class="ml-1 hover:bg-primary-foreground/20 rounded-full p-0.5 transition-colors"
                                    aria-label="Remove tag"
                                >
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </span>
                        </div>

                        <span
                            v-if="!props.form[props.model]?.length"
                            class="text-muted-foreground select-none">Select or add options...
                        </span>

                        <div class="ml-auto">
                            <svg
                                class="w-4 h-4 text-muted-foreground transition-transform duration-200"
                                :class="{ 'rotate-180': open }"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>

                    <div
                        v-if="open"
                        class="absolute z-50 mt-1 w-full max-h-60 bg-popover border border-border rounded-md shadow-md overflow-hidden"
                    >
                        <div class="p-2 border-b border-border">
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <Input
                                    v-model="searchQuery"
                                    @keyup.enter="addNewTag"
                                    placeholder="Search or type to add..."
                                    class="pl-10"
                                />
                            </div>
                        </div>

                        <div class="max-h-48 overflow-y-auto">
                            <div
                                v-for="option in filteredOptions"
                                :key="option.value"
                                @click="toggleTag(option.value)"
                                class="px-3 py-2 hover:bg-accent cursor-pointer flex items-center justify-between group transition-colors"
                            >
                                <span class="text-foreground">{{ option.label }}</span>
                                <div class="flex items-center">
                                    <div
                                        v-if="Array.isArray(props.form[props.model]) && props.form[props.model].includes(option.value)"
                                        class="w-4 h-4 bg-primary rounded-full flex items-center justify-center"
                                    >
                                        <svg class="w-2.5 h-2.5 text-primary-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div
                                        v-else
                                        class="w-4 h-4 border-2 border-border rounded-full group-hover:border-primary transition-colors"
                                    >
                                    </div>
                                </div>
                            </div>

                            <div v-if="props.searchable && filteredOptions.length === 0 && searchQuery.trim()" class="p-3 text-center">
                                <div class="text-muted-foreground mb-2">No results found</div>
                                <button
                                    @click="addNewTag"
                                    class="inline-flex items-center gap-2 px-3 py-1.5 bg-primary/10 text-primary rounded-md hover:bg-primary/20 transition-colors text-sm font-medium"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Add "{{ searchQuery.trim() }}"
                                </button>
                            </div>

                            <div v-else-if="props.searchable && filteredOptions.length === 0 && !searchQuery.trim()" class="p-3 text-center text-muted-foreground">
                                Start typing to search or add new options
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <template v-else>
                <div class="relative" ref="dropdownRef">
                    <div
                        class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-gray-50 px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 cursor-pointer"
                         @click="!attrs.disabled && (open = !open)"
                        :class="{ 'opacity-100 cursor-not-allowed': attrs.disabled }"
                    >
                        <span v-if="props.form[props.model] && props.options.length" class="text-foreground">
                            {{ props.options.find(opt => opt.value === props.form[props.model])?.label || props.form[props.model] }}
                        </span>
                        <span v-else class="text-muted-foreground">
                            {{ attrs.placeholder || 'Select an option' }}
                        </span>

                        <svg
                            class="w-4 h-4 text-muted-foreground transition-transform duration-200 ml-2"
                            :class="{ 'rotate-180': open }"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>

                    <div
                        v-if="open"
                        class="absolute z-50 mt-1 w-full max-h-60 bg-popover border border-border rounded-md shadow-md overflow-hidden">
                        <div v-if="props.searchable" class="p-2 border-b border-border">
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <Input
                                    v-model="searchQuery"
                                    ref="searchInput"
                                    @click.stop
                                    placeholder="Search..."
                                    class="pl-10"
                                />
                            </div>
                        </div>

                        <div class="max-h-48 overflow-y-auto">
                            <div
                                v-for="option in filteredOptions"
                                :key="option.value"
                                @click="selectOption(option.value)"
                                class="px-3 py-2 hover:bg-accent cursor-pointer flex items-center justify-between group transition-colors"
                            >
                                <span class="text-foreground">{{ option.label }}</span>
                                <div class="flex items-center">
                                    <div
                                        v-if="props.form[props.model] === option.value"
                                        class="w-4 h-4 bg-primary rounded-full flex items-center justify-center"
                                    >
                                        <svg class="w-2.5 h-2.5 text-primary-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div
                                        v-else
                                        class="w-4 h-4 border-2 border-border rounded-full group-hover:border-primary transition-colors"
                                    >
                                    </div>
                                </div>
                            </div>

                            <div v-if="props.searchable && filteredOptions.length === 0" class="p-3 text-center text-muted-foreground">
                                No results found
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <InputError :message="props.form.errors[props.model]" />
        </div>
    </div>
</template>
