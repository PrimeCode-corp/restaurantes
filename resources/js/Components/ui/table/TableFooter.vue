<script setup>
import { cn } from '@/lib/utils.js'

const props = defineProps({
  class: {
    type: [String, Object, Array],
    default: '',
  },
})
</script>

<template>
  <tfoot
    :class="
      cn('border-t bg-muted/50 font-medium [&>tr]:last:border-b-0', props.class)
    "
  >
    <slot />
  </tfoot>
</template>
