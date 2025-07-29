<template>
  <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-8 shadow-sm bg-white dark:bg-[rgba(0,0,0,0)] max-h-155 overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-700 scrollbar-track-transparent dark:text-white">
      <div class="flex justify-between items-start mb-6 flex-wrap gap-4">
    <div class="flex flex-col items-start justify-center gap-6 flex-1 min-w-[180px] text-left">
      <div>
        <div class="text-3xl font-bold mb-2">INVOICE</div>
        <div class="text-xs text-gray-500 dark:text-white mb-1">No: <span class="font-semibold text-gray-900 dark:text-white">#{{ invoice.id }}</span></div>
        <div class="text-xs text-gray-500 dark:text-white mb-1">Data:  <span class="font-semibold text-gray-900 dark:text-white">{{ invoice.created_at ? invoice.created_at.slice(0, 10) : '-' }}</span></div>
        <div class="text-xs text-gray-500 dark:text-white mb-1">Due Date:  <span class="font-semibold text-gray-900 dark:text-white">{{ invoice.due_date ? invoice.due_date : '-' }}</span></div>
      </div>

      <div class="flex flex-col items-center text-center max-w-xs">
        <div class="text-sm font-semibold uppercase text-gray-500 dark:text-white mb-1">Furnizor</div>
        <div class="font-semibold text-gray-900 dark:text-white">{{ ($page.props.companyInfo as any)?.company_name || '-' }}</div>
        <div class="text-xs text-gray-500 dark:text-white">Address: {{ ($page.props.companyInfo as any)?.address || '-' }}</div>
        <div class="text-xs text-gray-500 dark:text-white">
          <template v-if="($page.props.companyInfo as any)?.city || ($page.props.companyInfo as any)?.county || ($page.props.companyInfo as any)?.country">
            <template v-if="($page.props.companyInfo as any)?.city">{{ ($page.props.companyInfo as any)?.city }}</template>
            <template v-if="($page.props.companyInfo as any)?.city && ($page.props.companyInfo as any)?.county">, </template>
            <template v-if="($page.props.companyInfo as any)?.county">{{ ($page.props.companyInfo as any)?.county }}</template>
            <template v-if="((($page.props.companyInfo as any)?.city || ($page.props.companyInfo as any)?.county) && ($page.props.companyInfo as any)?.country)">, </template>
            <template v-if="($page.props.companyInfo as any)?.country">{{ ($page.props.companyInfo as any)?.country }}</template>
          </template>
          <template v-else>-</template>
        </div>
        <div class="text-xs text-gray-500 dark:text-white">Email: {{ ($page.props.companyInfo as any)?.email || '-' }}</div>
        <div class="text-xs text-gray-500 dark:text-white">Phone: {{ ($page.props.companyInfo as any)?.phone || '-' }}</div>
        <div class="text-xs text-gray-500 dark:text-white">IBAN: {{ ($page.props.companyInfo as any)?.iban || '-' }}</div>
        <div class="text-xs text-gray-500 dark:text-white">Bank: {{ ($page.props.companyInfo as any)?.bank || '-' }}</div>
        <div class="text-xs text-gray-500 dark:text-white">CUI: {{ ($page.props.companyInfo as any)?.cui || '-' }}</div>
        <div class="text-xs text-gray-500 dark:text-white">Nr. Înreg.: {{ ($page.props.companyInfo as any)?.registration_number || '-' }}</div>

      </div>
    </div>

    <div class="flex flex-col items-center w-64 min-w-[180px] gap-2 text-center">
      <div class="w-32 h-32 flex items-center justify-center bg-gray-300 text-white text-4xl font-bold uppercase rounded-full overflow-hidden">
        <template v-if="logoUrl">
          <img :src="logoUrl" alt="Company Logo" class="w-full h-full object-contain" />
        </template>
        <template v-else>
          {{ companyInitial }}
        </template>
      </div>
      <div style="margin-top: 0.9rem;" class="flex flex-col items-center text-center max-w-xs">
        <div class="text-sm font-semibold uppercase text-gray-500 dark:text-white mb-1">Cumpărător</div>
        <div class="font-semibold text-gray-900 dark:text-white">{{ invoice.client_name || '-' }}</div>
        <div class="text-xs text-gray-500 dark:text-white">Address: {{ invoice.client_address || '-' }}</div>
        <div class="text-xs text-gray-500 dark:text-white">
          <template v-if="invoice.client_city || invoice.client_county || invoice.client_country">
            <template v-if="invoice.client_city">{{ invoice.client_city }}</template>
            <template v-if="invoice.client_city && invoice.client_county">, </template>
            <template v-if="invoice.client_county">{{ invoice.client_county }}</template>
            <template v-if="(invoice.client_city || invoice.client_county) && invoice.client_country">, </template>
            <template v-if="invoice.client_country">{{ invoice.client_country }}</template>
          </template>
          <template v-else>-</template>
        </div>
        <div class="text-xs text-gray-500 dark:text-white">
          <template v-if="($page.props.companyInfo as any)?.company_type === 'individual'">
            CNP: {{ ($page.props.companyInfo as any)?.cui || '-' }}
          </template>
          <template v-else>
            CUI: {{ ($page.props.companyInfo as any)?.cui || '-' }}
          </template>
        </div>
        <div class="text-xs text-gray-500 dark:text-white">Nr. Înreg.: {{ invoice.client_registration_number || '-' }}</div>
        <div class="text-xs text-gray-500 dark:text-white">Bank: {{ invoice.client_bank || '-' }}</div>
        <div class="text-xs text-gray-500 dark:text-white">IBAN: {{ invoice.client_iban || '-' }}</div>
        <div class="text-xs text-gray-500 dark:text-white">Phone: {{ invoice.client_phone || '-' }}</div>
        
      </div>
    </div>
  </div>
    <div class="mb-4">
      <div class="text-xs font-semibold mb-2">Products/Services</div>
      <div class="max-h-64 overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-700 scrollbar-track-transparent rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#262626]">
        <table class="min-w-full text-xs">
          <thead>
            <tr class="bg-gray-100 dark:bg-neutral-800">
              <th class="px-2 py-1 text-left">No.</th>
              <th class="px-2 py-1 text-left">Description</th>
              <th class="px-2 py-1 text-right">Qty</th>
              <th class="px-2 py-1 text-right">Unit Price</th>
              <th class="px-2 py-1 text-right">VAT</th>
              <th class="px-2 py-1 text-right">Discount</th>
              <th class="px-2 py-1 text-right">Total</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(product, idx) in products" :key="product.id">
              <td class="px-2 py-1">{{ idx + 1 }}</td>
              <td class="px-2 py-1">{{ product.product_name }}</td>
              <td class="px-2 py-1 text-right">{{ product.quantity }}</td>
              <td class="px-2 py-1 text-right">{{ product.converted_price.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }}</td>
              <td class="px-2 py-1 text-right">{{ (product.total - product.total_no_vat).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }}</td>
              <td class="px-2 py-1 text-right">{{ (product.discount || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }}</td>
              <td class="px-2 py-1 text-right">{{ (product.total - (product.discount || 0)).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }}</td>
            </tr>
            <tr v-if="!products || products.length === 0">
              <td colspan="7" class="px-2 py-1 text-center text-gray-400">No items</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="flex flex-col gap-2 mt-6 text-sm w-full max-w-xs ml-auto items-end">
      <div class="grid grid-cols-2 gap-x-4 w-full">
        <div class="flex flex-col gap-2 items-start">
          <span class="text-gray-500 dark:text-white">Subtotal</span>
          <span class="text-gray-500 dark:text-white">Discount</span>
          <span class="text-gray-500 dark:text-white">VAT</span>
          <span class="text-gray-500 dark:text-white text-lg mt-2">Total</span>
        </div>
        <div class="flex flex-col gap-2 items-end">
          <span class="font-semibold text-right">{{ (products?.reduce((sum, p) => parseFloat(sum) + parseFloat(p.total_no_vat), 0)).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }} {{ invoice.currency }}</span>
          <span class="font-semibold text-right">{{ (products?.reduce((sum, p) => sum + (p.discount || 0), 0)).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }} {{ invoice.currency }}</span>
          <span class="font-semibold text-right">{{ (products?.reduce((sum, p) => parseFloat(sum) + (p.total - p.total_no_vat), 0)).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }} {{ invoice.currency }}</span>
          <span class="font-bold text-red-600 dark:text-red-400 text-right text-lg mt-2">{{ invoice.total?.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }} {{ invoice.currency }}</span>
        </div>
      </div>
    </div>
    <div class="flex justify-between items-end mt-8">
      <div class="text-xs text-gray-500 dark:text-gray-300">
        <div>Payment Method</div>
        <div>IBAN: {{ ($page.props.companyInfo as any)?.iban || '-' }}</div>
        <div>Bank: {{ ($page.props.companyInfo as any)?.bank || '-' }}</div>
      </div>
      <div class="text-xs text-gray-500 flex flex-col items-start dark:text-gray-300">
        <div>Issued by <span class="font-semibold text-gray-900 dark:text-white">{{ ($page.props.companyInfo as any)?.company_name || '-' }}</span></div>
        <div class="mt-4 flex flex-col items-start">
          <span class="font-medium">Signature:</span>
          <span class="block w-40 border-b border-gray-400 dark:border-gray-600 mt-2" style="height: 18px;"></span>
        </div>
      </div>
    </div>
  </div>
</template>


<script setup lang="ts">
import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

defineProps<{ invoice: any, products: any[] }>()

const page = usePage()

const company = page.props.companyInfo as any || {}
const settings = page.props.settings as any || {}

const logoUrl = computed(() => {
  if (company.logo_url) return company.logo_url
  if (settings.logo_path) return `/storage/${settings.logo_path}`
  return null
})

const companyInitial = computed(() => {
  return company.company_name ? company.company_name[0].toUpperCase() : '-'
})
</script>
