<script setup>
import { DialogDescription } from 'radix-vue'
import { cn } from '@/lib/utils.js'
const props = defineProps({ class: { type: [String, Object, Array], default: '' } })
</script>
<template>
  <DialogDescription :class="cn('text-sm text-muted-foreground', props.class)">
    <slot />
  </DialogDescription>
</template>
