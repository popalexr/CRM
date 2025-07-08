<template>
  <h3 class="flex">
    <button
      :class="className"
      @click="toggleAccordion"
      :aria-expanded="isItemOpen"
      :disabled="disabled"
      class="flex flex-1 items-center justify-between py-4 font-medium transition-all hover:underline [&[data-state=open]>svg]:rotate-180"
      :data-state="isItemOpen ? 'open' : 'closed'"
    >
      <slot />
      <svg
        xmlns="http://www.w3.org/2000/svg"
        width="24"
        height="24"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
        class="h-4 w-4 shrink-0 transition-transform duration-200"
        :class="{ 'rotate-180': isItemOpen }"
      >
        <path d="m6 9 6 6 6-6" />
      </svg>
    </button>
  </h3>
</template>

<script setup lang="ts">
import { computed, inject } from 'vue'

interface AccordionTriggerProps {
  class?: string
}

const props = withDefaults(defineProps<AccordionTriggerProps>(), {
  class: ''
})

const itemValue = inject<string>('accordionItemValue')!
const disabled = inject<boolean>('accordionItemDisabled', false)
const toggleItem = inject<(value: string) => void>('accordionToggleItem')!
const isOpen = inject<(value: string) => boolean>('accordionIsOpen')!

const className = computed(() => props.class)
const isItemOpen = computed(() => isOpen(itemValue))

const toggleAccordion = () => {
  if (!disabled) {
    toggleItem(itemValue)
  }
}
</script>
