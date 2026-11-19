<script setup>
import { SelectTrigger, useForwardProps } from 'radix-vue'
import { ChevronDown } from 'lucide-vue-next'
import { cn } from '@/lib/utils.js'

const props = defineProps({
  disabled: { type: Boolean, default: false },
  asChild: { type: Boolean, default: false },
  as: { type: null, default: undefined },
  class: { type: [String, Object, Array], default: '' },
})

const forwardedProps = useForwardProps(props)
</script>

<template>
  <SelectTrigger
    v-bind="forwardedProps"
    :class="
      cn(
        'flex h-9 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50 [&>span]:line-clamp-1',
        props.class
      )
    "
  >
    <slot />
    <ChevronDown class="h-4 w-4 opacity-50 shrink-0" />
  </SelectTrigger>
</template>
