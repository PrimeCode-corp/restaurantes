<script setup>
import { AvatarRoot } from 'radix-vue'
import { cn } from '@/lib/utils.js'

const props = defineProps({
  class: {
    type: [String, Object, Array],
    default: '',
  },
})
</script>

<template>
  <AvatarRoot
    :class="
      cn(
        'relative flex h-10 w-10 shrink-0 overflow-hidden rounded-full',
        props.class
      )
    "
  >
    <slot />
  </AvatarRoot>
</template>
