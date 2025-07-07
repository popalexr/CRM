<script setup lang="ts">
import { SelectTrigger, type SelectTriggerProps } from 'reka-ui'
import { cn } from '@/lib/utils'
import type { HTMLAttributes } from 'vue'

interface Props extends SelectTriggerProps {
  class?: HTMLAttributes['class']
}

const props = defineProps<Props>()
</script>

<template>
  <SelectTrigger
    :class="cn(
      'border-input placeholder:text-muted-foreground focus:ring-ring flex h-9 w-full items-center justify-between gap-2 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs transition-[color,box-shadow] outline-none focus:ring-2 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm [&>span]:line-clamp-1',
      props.class
    )"
    v-bind="props"
  >
    <slot />
  </SelectTrigger>
</template>
