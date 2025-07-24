<template>
  <div class="flex flex-col gap-2 p-6 bg-white rounded-lg shadow min-w-[320px]">
    <div class="flex items-center gap-2">
      <span class="text-2xl font-bold">{{ invoice.number }}</span>
      <span v-if="invoice.status === 'Overdue'" class="ml-2 px-2 py-1 text-xs bg-red-100 text-red-600 rounded">Overdue</span>
      <span v-else-if="invoice.status === 'Paid'" class="ml-2 px-2 py-1 text-xs bg-green-100 text-green-600 rounded">Paid</span>
    </div>
    <div class="flex justify-between text-sm mt-2">
      <span>Total Amount</span>
      <span class="font-semibold text-orange-600 text-lg">${{ invoice.total.toLocaleString() }}</span>
    </div>
    <div class="flex justify-between text-sm">
      <span>Open Amount</span>
      <span>${{ invoice.openAmount.toLocaleString() }}</span>
    </div>
    <div class="flex justify-between text-sm">
      <span>VAT Amount</span>
      <span>${{ invoice.vatAmount.toLocaleString() }}</span>
    </div>
    <div class="flex justify-between text-sm">
      <span>Due Date</span>
      <span>{{ invoice.due_date }}</span>
    </div>
    <div class="flex justify-between text-sm">
      <span>Paid On</span>
      <span>{{ invoice.paid_on || '-' }}</span>
    </div>
    <div class="flex justify-between text-sm">
      <span>Customer delay</span>
      <span>{{ invoice.customer_delay ? invoice.customer_delay + ' days' : '-' }}</span>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{ invoice: any }>();
</script>
