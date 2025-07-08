<template>
  <div
    v-show="isItemOpen"
    class="overflow-hidden text-sm transition-all"
    :data-state="isItemOpen ? 'open' : 'closed'"
  >
    <div class="pb-4 pt-0">
      <slot />
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, inject } from 'vue'

const itemValue = inject<string>('accordionItemValue')!
const isOpen = inject<(value: string) => boolean>('accordionIsOpen')!

const isItemOpen = computed(() => isOpen(itemValue))
</script>
