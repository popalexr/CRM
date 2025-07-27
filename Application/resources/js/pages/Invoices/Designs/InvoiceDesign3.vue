<template>
  <div class="rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-black text-black dark:text-white max-h-155 overflow-y-auto p-0">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center p-10 gap-8 border-b border-gray-200 dark:border-gray-800">
      <div class="flex flex-col gap-2">
        <div class="text-3xl font-mono font-bold uppercase tracking-tight">INVOICE</div>
        <div class="flex flex-row gap-6 mt-2">
        <div class="text-xs">No: <span class="font-bold">{{ invoice.number }} / {{ invoice.id }}</span></div>
        <div class="text-xs">Date: <span class="font-bold">{{ invoice.created_at ? invoice.created_at.slice(0, 10) : '-' }}</span></div>
        <div class="text-xs">Due: <span class="font-bold">{{ invoice.due_date }}</span></div>
        </div>
      </div>
      <img :src="($page.props.companyInfo as any)?.logo_url || '/favicon.svg'" alt="Logo" class="w-20 h-20 object-contain rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-black" />
    </div>
    <div class="flex flex-col md:flex-row gap-10 p-10 border-b border-gray-200 dark:border-gray-800">
      <div class="flex-1">
        <div class="font-bold">Client</div>
        <div class="text-xs">{{ invoice.client_name || '-' }}</div>
        <div class="text-xs">{{ invoice.client_address || '-' }}</div>
        <div class="text-xs">VAT/CIF: {{ invoice.client_vat_code || '-' }}</div>
        <div class="text-xs">Phone: {{ invoice.client_phone || '-' }}</div>
      </div>
      <div class="flex-1 flex flex-col items-end pr-2">
        <div class="font-bold">Company</div>
        <div class="text-xs">{{ ($page.props.companyInfo as any)?.company_name || '-' }}</div>
        <div class="text-xs">{{ ($page.props.companyInfo as any)?.address || '-' }}</div>
        <div class="text-xs">Email: {{ ($page.props.companyInfo as any)?.email || '-' }}</div>
        <div class="text-xs">IBAN: {{ ($page.props.companyInfo as any)?.iban || '-' }}</div>
        <div class="text-xs">Bank: {{ ($page.props.companyInfo as any)?.bank || '-' }}</div>
      </div>
    </div>
    <div class="p-10">
      <div class="text-xs font-bold mb-2 uppercase tracking-widest">Products / Services</div>
      <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-black">
        <table class="min-w-full text-xs">
          <thead>
            <tr class="bg-gray-100 dark:bg-gray-800">
              <th class="px-3 py-2 text-left">#</th>
              <th class="px-3 py-2 text-left">Description</th>
              <th class="px-3 py-2 text-right">Qty</th>
              <th class="px-3 py-2 text-right">Unit Price</th>
              <th class="px-3 py-2 text-right">VAT</th>
              <th class="px-3 py-2 text-right">Discount</th>
              <th class="px-3 py-2 text-right">Total</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(product, idx) in products" :key="product.id">
              <td class="px-3 py-2">{{ idx + 1 }}</td>
              <td class="px-3 py-2">{{ product.product_name }}</td>
              <td class="px-3 py-2 text-right">{{ product.quantity }}</td>
              <td class="px-3 py-2 text-right">{{ product.converted_price.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }}</td>
              <td class="px-3 py-2 text-right">{{ (product.total - product.total_no_vat).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }}</td>
              <td class="px-3 py-2 text-right">{{ (product.discount || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }}</td>
              <td class="px-3 py-2 text-right">{{ (product.total - (product.discount || 0)).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }}</td>
            </tr>
            <tr v-if="!products || products.length === 0">
              <td colspan="7" class="px-3 py-2 text-center text-gray-400 dark:text-gray-600">No items</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="flex flex-col md:flex-row gap-8 p-10">
      <div class="flex-1 flex flex-col gap-2">
        <span>Subtotal: <span class="font-bold">{{ (products?.reduce((sum, p) => parseFloat(sum) + parseFloat(p.total_no_vat), 0)).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }} {{ invoice.currency }}</span></span>
        <span>Discount: <span class="font-bold">{{ (products?.reduce((sum, p) => sum + (p.discount || 0), 0)).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }} {{ invoice.currency }}</span></span>
        <span>VAT: <span class="font-bold">{{ (products?.reduce((sum, p) => parseFloat(sum) + (p.total - p.total_no_vat), 0)).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }} {{ invoice.currency }}</span></span>
        <span class="text-xl mt-2">Total: <span class="font-black">{{ invoice.total?.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }} {{ invoice.currency }}</span></span>
      </div>
      <div class="flex-1 flex flex-col gap-2 items-end">
        <div class="text-xs">Issued by <span class="font-bold">{{ ($page.props.companyInfo as any)?.company_name || '-' }}</span></div>
        <div class="text-xs">Bank: {{ ($page.props.companyInfo as any)?.bank || '-' }}</div>
        <div class="text-xs">IBAN: {{ ($page.props.companyInfo as any)?.iban || '-' }}</div>
        <div class="text-xs">Signature</div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{ invoice: any, products: any[] }>();
</script>
