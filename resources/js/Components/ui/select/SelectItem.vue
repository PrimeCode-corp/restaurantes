<script setup>
import {
  SelectItem,
  SelectItemIndicator,
  SelectItemText,
  useForwardProps,
} from 'radix-vue'
import { Check } from 'lucide-vue-next'
import { cn } from '@/lib/utils.js'

const props = defineProps({
  value: { type: String, required: true },
  disabled: { type: Boolean, default: false },
  textValue: { type: String, default: undefined },
  asChild: { type: Boolean, default: false },
  as: { type: null, default: undefined },
  class: { type: [String, Object, Array], default: '' },
})

const forwardedProps = useForwardProps(props)
</script>

<template>
  <SelectItem
    v-bind="forwardedProps"
    :class="
      cn(
        'relative flex w-full cursor-default select-none items-center rounded-sm py-1.5 pl-2 pr-8 text-sm outline-none focus:bg-accent focus:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50',
        props.class
      )
    "
  >
    <span class="absolute right-2 flex h-3.5 w-3.5 items-center justify-center">
      <SelectItemIndicator>
        <Check class="h-4 w-4" />
      </SelectItemIndicator>
    </span>
    <SelectItemText>
      <slot />
    </SelectItemText>
  </SelectItem>
</template>
