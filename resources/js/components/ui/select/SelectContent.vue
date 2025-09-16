<script setup lang="ts">
import  { computed, HTMLAttributes, ref } from 'vue'
import { reactiveOmit } from '@vueuse/core'
import {
  SelectContent,
  type SelectContentEmits,
  type SelectContentProps,
  SelectPortal,
  SelectViewport,
  useForwardPropsEmits,
} from 'reka-ui'
import { cn } from '@/lib/utils'
import { SelectItem, SelectScrollDownButton, SelectScrollUpButton } from '.'
import { InputSelectOption } from '@/types';
import { Input } from '@/components/ui/input';


defineOptions({
  inheritAttrs: false,
})

type Props = {
    class?: HTMLAttributes['class'],
    options?: InputSelectOption[],
    slice? : number|'all'
    searchable?: boolean
}

const props = withDefaults(
  defineProps<SelectContentProps & Props>(),
  {
    position: 'popper',
      searchable: false
  },
)
const emits = defineEmits<SelectContentEmits>()

const delegatedProps = reactiveOmit(props, 'class')

const forwarded = useForwardPropsEmits(delegatedProps, emits)

const searchQuery = ref('')

const filteredOptions = computed(() => {
    if (!props.options) {
        return []
    }
    if (!searchQuery.value.length){
        if (props.slice == 'all'){
            return props.options
        }
        return props.options.slice(0, props.slice as number ?? 20)
    }
    return props.options.filter(o =>
        o.label.toLowerCase().includes(searchQuery.value.toLowerCase())
    ).slice(0,20)
})

</script>

<template>
  <SelectPortal>
    <SelectContent
      data-slot="select-content"
      v-bind="{ ...forwarded, ...$attrs }"
      :class="cn(
        'bg-popover text-popover-foreground data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95 data-[side=bottom]:slide-in-from-top-2 data-[side=left]:slide-in-from-right-2 data-[side=right]:slide-in-from-left-2 data-[side=top]:slide-in-from-bottom-2 relative z-50 max-h-(--reka-select-content-available-height) min-w-[8rem] overflow-x-hidden overflow-y-auto rounded-md border shadow-md',
        position === 'popper'
          && 'data-[side=bottom]:translate-y-1 data-[side=left]:-translate-x-1 data-[side=right]:translate-x-1 data-[side=top]:-translate-y-1',
        props.class,
      )"
    >
      <SelectScrollUpButton v-if="!options"/>

      <SelectViewport :class="cn('p-1', position === 'popper' && 'h-[var(--reka-select-trigger-height)] w-full min-w-[var(--reka-select-trigger-width)] scroll-my-1')">
          <template v-if="options">
              <div v-if="searchable" class="p-2 border-b border-border">
                  <div class="relative">
                      <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                      </svg>
                      <Input
                          placeholder="Search..."
                          class="pl-10"
                          @keydown.stop
                          v-model="searchQuery"
                      />
                  </div>
              </div>
              <SelectItem  v-for="option in filteredOptions" :key="`opt-${option.value}`" :value="option.value">
                  {{ option.label }}
              </SelectItem>
          </template>
          <slot v-else/>
      </SelectViewport>
      <SelectScrollDownButton v-if="!options"/>
    </SelectContent>
  </SelectPortal>
</template>
