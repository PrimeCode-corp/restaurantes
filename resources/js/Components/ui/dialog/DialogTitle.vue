<script setup>
import { DialogTitle } from 'radix-vue'
import { cn } from '@/lib/utils.js'
const props = defineProps({ class: { type: [String, Object, Array], default: '' } })
</script>
<template>
  <DialogTitle :class="cn('text-lg font-semibold leading-none tracking-tight', props.class)">
    <slot />
  </DialogTitle>
</template>
