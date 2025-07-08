<template>
  <div class="border-b">
    <slot />
  </div>
</template>

<script setup lang="ts">
import { provide } from 'vue'

interface AccordionItemProps {
  value: string
  disabled?: boolean
}

const props = withDefaults(defineProps<AccordionItemProps>(), {
  disabled: false
})

provide('accordionItemValue', props.value)
provide('accordionItemDisabled', props.disabled)
</script>
