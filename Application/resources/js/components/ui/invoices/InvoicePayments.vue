<template>
  <div class="bg-white rounded-lg shadow p-6">
    <div class="font-semibold mb-2">Payments</div>
    <div v-for="payment in payments" :key="payment.id" class="flex items-center justify-between py-2 border-b last:border-b-0">
      <div>
        <span class="font-medium">${{ payment.amount }}</span>
        <span class="text-xs text-gray-500 ml-2">| {{ payment.type }}</span>
      </div>
      <div class="text-xs text-gray-500">Paid on {{ payment.date }}</div>
    </div>
    <div class="flex justify-end mt-2">
      <button class="border px-3 py-1 rounded text-sm text-orange-600 border-orange-200 hover:bg-orange-50">+ Add</button>
    </div>
  </div>
</template>
<script setup lang="ts">
defineProps<{ payments: any[] }>();
</script>
