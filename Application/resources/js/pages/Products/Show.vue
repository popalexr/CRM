<script setup lang="ts">
import type { BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { ArrowLeftIcon, PlusCircleIcon, MinusCircleIcon, Edit2Icon } from 'lucide-vue-next';
import { computed } from 'vue';
import { ref } from 'vue';

import Dialog from '@/components/ui/dialog/Dialog.vue';
import DialogContent from '@/components/ui/dialog/DialogContent.vue';
import DialogHeader from '@/components/ui/dialog/DialogHeader.vue';
import DialogTitle from '@/components/ui/dialog/DialogTitle.vue';
import DialogFooter from '@/components/ui/dialog/DialogFooter.vue';

import ProfileHeader from '@/components/ProfileHeader.vue';
import SearchInput from '@/components/SearchInput.vue';
import type { ProductDetails, InvoiceDetails, Props } from '@/types/products.d.ts';

const props = defineProps<Props>();
const formLabels = props.formLabels || {};
const associatedInvoices = props.associated_invoices || [];

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: formLabels.headings?.products || 'Products',
        href: route('products.index'),
    },
    {
        title: formLabels.headings?.view_product || 'View Product',
        href: route('products.details', { id: props.product.id }),
    },
];

const entityData = computed(() => ({
    id: props.product.id,
    name: props.product.name,
    email: (formLabels.types && formLabels.types[props.product.type]) ? formLabels.types[props.product.type] : props.product.type,
    phone: undefined, 
    avatar: props.product.image
}));


const handleBack = () => {
    router.visit(route('products.index'));
};

const showStockMenu = ref(false);
const stockDialog = ref<'increment' | 'subtract' | 'modify' | null>(null);
const stockValue = ref('');
const stockLoading = ref(false);
const stockError = ref('');

const openStockDialog = (action: 'increment' | 'subtract' | 'modify') => {
  stockDialog.value = action;
  stockValue.value = '';
  stockError.value = '';
  showStockMenu.value = false;
};

const closeStockDialog = () => {
  stockDialog.value = null;
  stockValue.value = '';
  stockError.value = '';
};

const handleStockSubmit = async () => {
  stockLoading.value = true;
  stockError.value = '';
  try {
    const value = parseInt(stockValue.value, 10);
    if (isNaN(value) || value < 0) {
      stockError.value = formLabels.messages?.invalid_stock_value || 'Please enter a valid value.';
      stockLoading.value = false;
      return;
    }
    await router.post(route('products.stock.handle', { id: props.product.id }), {
      value,
      action: stockDialog.value,
    }, {
      preserveScroll: true,
      onFinish: () => {
        stockLoading.value = false;
        closeStockDialog();
      },
      onError: (err) => {
        stockError.value = err.value || (formLabels.messages?.stock_save_error || 'Error saving.');
        stockLoading.value = false;
      }
    });
  } catch (e) {
    stockError.value = formLabels.messages?.stock_save_error || 'Error saving.';
    stockLoading.value = false;
  }
};
</script>

<template>
    <Head :title="`${formLabels.headings?.product_details || 'Product Details'}: ${product.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto px-4 py-6 max-w-6xl">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="sm" @click="handleBack" class="p-2">
                        <ArrowLeftIcon class="w-4 h-4" />
                    </Button>
                    <h1 class="text-xl font-semibold dark:text-white">{{ formLabels.headings?.product_details || 'Product Details' }}</h1>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <ProfileHeader 
                        :entity="entityData"
                    />
                    <div class="bg-white dark:bg-black rounded-xl border border-gray-200 dark:border-gray-700 p-8 shadow-sm">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ formLabels.headings?.product_details || 'Product Details' }}</h3>
                        <div v-if="product.type === 'product'" class="relative">
                          <Button variant="outline" @click="showStockMenu = !showStockMenu">
                          {{ formLabels.buttons?.manage_stock || 'Manage Stock' }}
                          </Button>
                          <div v-if="showStockMenu" class="absolute right-0 mt-2 w-48 bg-white dark:bg-black border border-gray-200 dark:border-gray-700 rounded-2xl shadow-lg z-10">
                            <button class="w-full flex items-center gap-2 text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl " @click="openStockDialog('increment')">
                              <PlusCircleIcon class="w-5 h-5 text-green-500" />
                              <span>{{ formLabels.buttons?.stock_add || 'Add to stock' }}</span>
                            </button>
                            <button class="w-full flex items-center gap-2 text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl " @click="openStockDialog('subtract')">
                              <MinusCircleIcon class="w-5 h-5 text-red-500" />
                              <span>{{ formLabels.buttons?.stock_subtract || 'Subtract from stock' }}</span>
                            </button>
                            <button class="w-full flex items-center gap-2 text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl " @click="openStockDialog('modify')">
                              <Edit2Icon class="w-5 h-5 text-blue-500" />
                              <span>{{ formLabels.buttons?.stock_set || 'Set stock manually' }}</span>
                            </button>
                          </div>
                        </div>
                    </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-4">
                                <div class="flex items-center space-x-2 pb-3 border-b border-gray-100 dark:border-gray-800">
                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">{{ formLabels.labels?.commercial_details || 'Commercial Details' }}</h4>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex flex-col space-y-1">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ formLabels.labels?.price || 'Price' }}</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ product.price }} {{ product.currency }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="flex items-center space-x-2 pb-3 border-b border-gray-100 dark:border-gray-800">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">{{ formLabels.labels?.logistics_details || 'Logistics Details' }}</h4>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex flex-col space-y-1">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ formLabels.labels?.unit || 'Unit' }}</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ product.unit }}</span>
                                    </div>
                                    <div v-if="product.type === 'product'" class="flex flex-col space-y-1">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ formLabels.labels?.quantity || 'Quantity' }}</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ product.quantity ?? 0 }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6 mt-8">
                        <h3 class="text-lg font-semibold mb-4 dark:text-white">{{ formLabels.labels?.associated_invoices || 'Associated Invoices' }}</h3>
                        <div class="mb-4">
                            <SearchInput 
                                :placeholder="formLabels.placeholders?.search_invoices || 'Search invoices...'"
                            />
                        </div>
                        <div v-if="associatedInvoices.length > 0" class="bg-white dark:bg-black rounded-lg border border-gray-200 dark:border-gray-700 p-0">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ formLabels.labels?.client || 'Client' }}</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ formLabels.labels?.total || 'Total' }}</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ formLabels.labels?.status || 'Status' }}</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ formLabels.labels?.date || 'Date' }}</th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="invoice in associatedInvoices" :key="invoice.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                        <td class="px-4 py-2">{{ invoice.id }}</td>
                                        <td class="px-4 py-2">{{ invoice.client_id }}</td>
                                        <td class="px-4 py-2">{{ invoice.total }} {{ invoice.currency }}</td>
                                        <td class="px-4 py-2">{{ invoice.status }}</td>
                                        <td class="px-4 py-2">{{ invoice.created_at ? new Date(invoice.created_at).toLocaleDateString() : '' }}</td>
                                        <td class="px-4 py-2 text-right">
                                            <Button size="sm" variant="outline" @click="router.visit(route('invoices.details', { id: invoice.id }))">
                                                View
                                            </Button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else class="bg-white dark:bg-black rounded-lg border border-gray-200 dark:border-gray-700 p-6 text-center">
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ formLabels.messages?.no_invoices_found || 'No invoices found for this product.' }}</p>
                        </div>
                    </div>
                </div>
                <Dialog v-if="stockDialog" open @close="closeStockDialog">
                  <DialogContent>
                    <DialogHeader>
                      <DialogTitle>
                        {{
                          stockDialog === 'increment' ? (formLabels.headings?.stock_add || 'Add to stock') :
                          stockDialog === 'subtract' ? (formLabels.headings?.stock_subtract || 'Subtract from stock') :
                          (formLabels.headings?.stock_set || 'Set stock manually')
                        }}
                      </DialogTitle>
                    </DialogHeader>
                    <div class="my-4">
                      <input
                        v-model="stockValue"
                        type="number"
                        min="0"
                        class="w-full border rounded px-3 py-2"
                        :placeholder="stockDialog === 'modify' ? (formLabels.placeholders?.stock_new_value || 'New stock value') : (formLabels.placeholders?.stock_value || 'Value')"
                        @keyup.enter="handleStockSubmit"
                        autofocus
                      />
                      <div v-if="stockError" class="text-red-500 text-sm mt-2">{{ stockError }}</div>
                    </div>
                    <DialogFooter>
                      <Button variant="outline" @click="closeStockDialog" :disabled="stockLoading">{{ formLabels.buttons?.cancel || 'Cancel' }}</Button>
                      <Button @click="handleStockSubmit" :loading="stockLoading">
                        {{ formLabels.buttons?.save || 'Save' }}
                      </Button>
                    </DialogFooter>
                  </DialogContent>
                </Dialog>
            </div>
        </div>
    </AppLayout>
</template>