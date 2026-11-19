<script setup>
import { AvatarFallback } from 'radix-vue'
import { cn } from '@/lib/utils.js'

const props = defineProps({
  class: {
    type: [String, Object, Array],
    default: '',
  },
})
</script>

<template>
  <AvatarFallback
    :class="
      cn(
        'flex h-full w-full items-center justify-center rounded-full bg-muted font-medium',
        props.class
      )
    "
  >
    <slot />
  </AvatarFallback>
</template>
