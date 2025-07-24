<template>
  <div class="bg-white rounded-lg shadow p-6 mt-4">
    <div class="font-semibold mb-2">Credit Note</div>
    <div class="flex items-center justify-between">
      <div>
        <span class="font-medium">{{ creditNote.number }}</span>
        <span class="text-xs text-gray-500 ml-2">| ${{ creditNote.amount }}</span>
      </div>
      <div class="text-xs text-gray-500">Created on: {{ creditNote.date }}</div>
    </div>
    <div class="flex justify-end mt-2">
      <button class="border px-3 py-1 rounded text-sm text-orange-600 border-orange-200 hover:bg-orange-50">+ Add</button>
    </div>
  </div>
</template>
<script setup lang="ts">
defineProps<{ creditNote: any }>();
</script>
