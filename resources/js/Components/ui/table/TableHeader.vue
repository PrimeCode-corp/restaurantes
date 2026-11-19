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
  <thead :class="cn('[&_tr]:border-b', props.class)">
    <slot />
  </thead>
</template>
