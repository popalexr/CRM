<script setup lang="ts">
import { computed, type HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

interface Props {
  class?: HTMLAttributes['class']
  error?: string
}

const props = defineProps<Props>()

const hasError = computed(() => !!props.error)
</script>

<template>
  <div :class="cn('grid gap-2', props.class)">
    <slot />
    <p v-if="hasError" class="text-sm text-red-500">
      {{ props.error }}
    </p>
  </div>
</template>
