<template>
  <div class="flex justify-center">
    <div class="flex items-center justify-between mb-4">
      <div class="flex gap-2"></div>
    </div>
    <div class="bg-white dark:bg-black text-gray-900 dark:text-white rounded-lg shadow p-6 max-w-md w-full flex flex-col border border-gray-300 dark:border-gray-700">
      <div class="flex items-center justify-between mb-4">
        <div>
          <div class="font-bold text-lg">Invoice</div>
          <div class="text-xs text-gray-500">Invoice No: {{ invoice.number }}</div>
          <div class="text-xs text-gray-500">Date Issued: {{ invoice.date }}</div>
          <div class="text-xs text-gray-500">Ref: {{ invoice.ref || '-' }}</div>
        </div>
        <div class="text-right">
          <div class="font-bold">{{ invoice.company }}</div>
          <div class="text-xs text-gray-500">{{ invoice.company_address }}</div>
        </div>
      </div>
      <div class="mb-2">
        <div class="font-semibold">Customer</div>
        <div class="text-xs text-gray-500">{{ invoice.customer_name }}</div>
        <div class="text-xs text-gray-500">{{ invoice.customer_address }}</div>
      </div>
      <div class="mb-2">
        <div class="font-semibold">Deliver To</div>
        <div class="text-xs text-gray-500">{{ invoice.deliver_to }}</div>
      </div>
      <div class="mb-2">
        <div class="font-semibold">Order Details</div>
        <table class="w-full text-xs mt-2">
          <thead>
            <tr class="bg-gray-100 dark:bg-neutral-800">
              <th class="text-left px-2 py-1">Item</th>
              <th class="text-right px-2 py-1">Qty</th>
              <th class="text-right px-2 py-1">Unit Price</th>
              <th class="text-right px-2 py-1">Tax</th>
              <th class="text-right px-2 py-1">Discount</th>
              <th class="text-right px-2 py-1">Amount</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in invoice.items" :key="item.id">
              <td class="px-2 py-1">{{ item.name }}</td>
              <td class="text-right px-2 py-1">{{ item.qty }}</td>
              <td class="text-right px-2 py-1">{{ item.unit_price }}</td>
              <td class="text-right px-2 py-1">{{ item.tax }}%</td>
              <td class="text-right px-2 py-1">{{ item.discount }}%</td>
              <td class="text-right px-2 py-1">{{ item.amount }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="flex flex-col items-end mt-4 text-xs">
        <div>Total HT: ${{ invoice.total_ht }}</div>
        <div>Discount: ${{ invoice.discount }}</div>
        <div>Total VAT: ${{ invoice.total_vat }}</div>
        <div class="font-bold text-base">Total Due: ${{ invoice.total_due }}</div>
      </div>
      <div class="mt-4 text-xs text-gray-400">
        {{ invoice.footer }}
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
defineProps<{ invoice: any }>();
</script>
