<script setup>
import { DialogContent, DialogOverlay, DialogPortal, useForwardPropsEmits } from 'radix-vue'
import { X } from 'lucide-vue-next'
import { cn } from '@/lib/utils.js'

const props = defineProps({
  class: { type: [String, Object, Array], default: '' },
  forceMount: { type: Boolean, default: undefined },
})
const emits = defineEmits(['openAutoFocus', 'closeAutoFocus', 'escapeKeyDown', 'pointerDownOutside', 'interactOutside'])
const { class: propsClass, ...forwarded } = useForwardPropsEmits(props, emits)
</script>

<template>
  <DialogPortal>
    <DialogOverlay
      class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0"
    />
    <DialogContent
      v-bind="forwarded"
      :class="
        cn(
          'fixed left-[50%] top-[50%] z-50 grid w-full max-w-lg translate-x-[-50%] translate-y-[-50%] gap-4 border bg-background p-6 shadow-lg duration-200 data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95 data-[state=closed]:slide-out-to-left-1/2 data-[state=closed]:slide-out-to-top-[48%] data-[state=open]:slide-in-from-left-1/2 data-[state=open]:slide-in-from-top-[48%] sm:rounded-lg',
          propsClass
        )
      "
    >
      <slot />
    </DialogContent>
  </DialogPortal>
</template>
