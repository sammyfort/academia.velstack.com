<script setup lang="ts">
import { ref, watch } from "vue"
import { Calendar as CalendarIcon } from "lucide-vue-next"
import { cn } from "@/lib/utils"
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from "@/components/ui/popover"
import { Button } from "@/components/ui/button"
import { Calendar } from "@/components/ui/calendar"
import { Label } from "@/components/ui/label"
import InputError from "@/components/InputError.vue"

// internationalized date helpers
import { CalendarDate, parseDate } from "@internationalized/date"
import { toDate } from "reka-ui/date"

type Props = {
    form?: Record<string, any>
    model?: string
    label?: string
    placeholder?: string
    containerClass?: string
    class?: string
}

const props = defineProps<Props>()
const id = Math.random().toString(36).substring(2, 10)

// Add this ref to control popover visibility
const popoverOpen = ref(false)

function normalizeDateString(val: string | null | undefined): string | null {
    if (!val) return null
    // take only "YYYY-MM-DD"
    return val.split("T")[0] || null
}
const value = ref<CalendarDate | undefined>(
    props.form && props.model && props.form[props.model]
        ? parseDate(normalizeDateString(props.form[props.model])!)
        : undefined
)

watch(value, (val) => {
    if (props.form && props.model) {
        props.form[props.model] = val ? val.toString() : null
    }
    // Close popover when a date is selected
    popoverOpen.value = false
})

watch(
    () => props.form?.[props.model ?? ""],
    (val) => {
        const normalized = normalizeDateString(val)
        value.value = normalized ? parseDate(normalized) : undefined
    }
)

</script>

<template>
    <div :class="cn('grid gap-2', props.containerClass)">
        <Label v-if="props.label" class="text-foreground" :for="id">
            {{ props.label }}
        </Label>

        <Popover v-model:open="popoverOpen">
            <PopoverTrigger as-child>
                <Button
                    variant="outline"
                    :class="cn(
            'w-full justify-start text-left font-normal',
            !value && 'text-muted-foreground',
            props.class
          )"
                >
                    <CalendarIcon class="mr-2 h-4 w-4" />
                    <span>
            {{ value ? toDate(value).toLocaleDateString() : (props.placeholder || 'Pick a date') }}
          </span>
                </Button>
            </PopoverTrigger>
            <PopoverContent class="w-auto p-0">
                <Calendar
                    :model-value="value"
                    @update:model-value="(v) => value = v"
                />
            </PopoverContent>
        </Popover>

        <InputError v-if="form && model" :message="form.errors[model]" />
    </div>
</template>
