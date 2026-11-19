<script setup>
import { badgeVariants } from '.'
import { cn } from '@/lib/utils.js'

const props = defineProps({
  variant: {
    type: String,
    default: 'default',
  },
  class: {
    type: [String, Object, Array],
    default: '',
  },
})
</script>

<template>
  <div :class="cn(badgeVariants({ variant }), props.class)">
    <slot />
  </div>
</template>
