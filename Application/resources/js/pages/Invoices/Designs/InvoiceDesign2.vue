<template>
<div class="rounded-xl shadow-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-black text-black dark:text-white w-full max-h-155 overflow-y-auto p-0">
    <div class="bg-orange-500 h-4 w-full"></div>
    <div class="flex justify-between items-center px-10 pt-10 pb-2">
      <div class="flex flex-col gap-1">
        <img :src="($page.props.companyInfo as any)?.logo_url || '/favicon.svg'" alt="Logo" class="w-16 h-16 object-contain mb-2" />
        <span class="font-bold text-lg">{{ ($page.props.companyInfo as any)?.company_name || '-' }}</span>
      </div>
      <div class="flex flex-col items-end gap-1">
        <span class="uppercase tracking-widest text-2xl font-bold">INVOICE</span>
        <span class="text-xs text-gray-500">Date: {{ invoice.created_at ? invoice.created_at.slice(0, 10) : '-' }}</span>
      </div>
    </div>
    <div class="flex justify-between items-center bg-orange-100 px-10 py-2 mt-2 rounded-t">
      <div class="text-xs font-semibold text-orange-700">Invoice #{{ invoice.id }}</div>
      <div class="text-xs font-semibold text-orange-700">Due: {{ invoice.due_date }}</div>
    </div>
    <div class="flex flex-row justify-between px-10 py-4 gap-8">
      <div class="flex-1">
        <div class="font-bold text-sm mb-1">Invoice to:</div>
        <div class="text-xs font-semibold">{{ invoice.client_name || '-' }}</div>
        <div class="text-xs text-gray-600">{{ invoice.client_address || '-' }}</div>
        <div class="text-xs text-gray-600">CUI/CNP: {{ invoice.client_vat_code || '-' }}</div>
      </div>
      <div class="flex-1 text-xs text-right">
        <div class="font-bold mb-1">Company</div>
        <div>{{ ($page.props.companyInfo as any)?.address || '-' }}</div>
        <div>IBAN: {{ ($page.props.companyInfo as any)?.iban || '-' }}</div>
        <div>Bank: {{ ($page.props.companyInfo as any)?.bank || '-' }}</div>
      </div>
    </div>
    <div class="px-10">
      <table class="w-full text-xs border-t border-gray-200">
        <thead>
          <tr class="bg-orange-100 text-orange-700">
            <th class="py-2 px-2 text-left font-bold">#</th>
            <th class="py-2 px-2 text-left font-bold">Item Description</th>
            <th class="py-2 px-2 text-right font-bold">Price</th>
            <th class="py-2 px-2 text-right font-bold">Qty</th>
            <th class="py-2 px-2 text-right font-bold">Total</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(product, idx) in products" :key="product.id" class="border-b border-gray-100">
            <td class="py-2 px-2">{{ idx + 1 }}</td>
            <td class="py-2 px-2">{{ product.product_name }}</td>
            <td class="py-2 px-2 text-right">{{ product.converted_price.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }}</td>
            <td class="py-2 px-2 text-right">{{ product.quantity }}</td>
            <td class="py-2 px-2 text-right">{{ (product.total - (product.discount || 0)).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }}</td>
          </tr>
          <tr v-if="!products || products.length === 0">
            <td colspan="5" class="py-2 px-2 text-center text-gray-400">No items</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="flex flex-row justify-end px-10 py-4">
      <div class="w-full max-w-xs ml-auto">
        <div class="flex justify-between text-sm mb-1">
          <span class="text-gray-600">Sub Total</span>
          <span class="font-semibold">{{ (products?.reduce((sum, p) => parseFloat(sum) + parseFloat(p.total_no_vat), 0)).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }} {{ invoice.currency }}</span>
        </div>
        <div class="flex justify-between text-sm mb-1">
          <span class="text-gray-600">Discount</span>
          <span class="font-semibold">{{ (products?.reduce((sum, p) => sum + (p.discount || 0), 0)).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }} {{ invoice.currency }}</span>
        </div>
        <div class="flex justify-between text-sm mb-1">
          <span class="text-gray-600">VAT</span>
          <span class="font-semibold">{{ (products?.reduce((sum, p) => parseFloat(sum) + (p.total - p.total_no_vat), 0)).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }} {{ invoice.currency }}</span>
        </div>
        <div class="flex justify-between text-base font-bold bg-orange-100 text-orange-700 rounded px-2 py-1 mt-2">
          <span>Total</span>
          <span>{{ invoice.total?.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }} {{ invoice.currency }}</span>
        </div>
      </div>
    </div>
    <div class="flex justify-between items-center px-10 py-4 border-t border-gray-200 dark:border-gray-800 mt-2">
      <div class="text-xs text-gray-500">
        <div>Payment info: {{ ($page.props.companyInfo as any)?.iban || '-' }} / {{ ($page.props.companyInfo as any)?.bank || '-' }}</div>
        <div>Terms &amp; Conditions</div>
      </div>
      <div class="text-xs text-right font-semibold">Authorised Sign</div>
    </div>
    <div class="bg-orange-500 h-6 w-full flex items-center justify-center text-white text-xs font-semibold tracking-wider"></div>
  </div>
</template>

<script setup lang="ts">
defineProps<{ invoice: any, products: any[] }>();
</script>
