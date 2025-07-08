<template>
  <div class="accordion" :class="className">
    <slot />
  </div>
</template>

<script setup lang="ts">
import { computed, provide, ref } from 'vue'

interface AccordionProps {
  type?: 'single' | 'multiple'
  collapsible?: boolean
  defaultValue?: string
  class?: string
}

const props = withDefaults(defineProps<AccordionProps>(), {
  type: 'single',
  collapsible: false,
  class: ''
})

const className = computed(() => props.class)

const openItems = ref<Set<string>>(new Set())

if (props.defaultValue) {
  openItems.value.add(props.defaultValue)
}

const toggleItem = (value: string) => {
  if (props.type === 'single') {
    if (openItems.value.has(value)) {
      if (props.collapsible) {
        openItems.value.clear()
      }
    } else {
      openItems.value.clear()
      openItems.value.add(value)
    }
  } else {
    if (openItems.value.has(value)) {
      openItems.value.delete(value)
    } else {
      openItems.value.add(value)
    }
  }
}

const isOpen = (value: string) => openItems.value.has(value)

provide('accordionToggleItem', toggleItem)
provide('accordionIsOpen', isOpen)
</script>
