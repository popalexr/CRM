<script setup lang="ts">
import { SelectValue, type SelectValueProps } from 'reka-ui'
import { cn } from '@/lib/utils'
import type { HTMLAttributes } from 'vue'

interface Props extends SelectValueProps {
  class?: HTMLAttributes['class']
}

const props = defineProps<Props>()
</script>

<template>
  <SelectValue
    :class="cn('text-sm', props.class)"
    v-bind="props"
  >
    <slot />
  </SelectValue>
</template>
