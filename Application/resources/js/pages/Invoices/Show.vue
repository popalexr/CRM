<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { Tabs, TabsList, TabsTrigger, TabsContent } from '@/components/ui/tabs';
import { MoreVerticalIcon, SendIcon, FileTextIcon, EditIcon, XCircleIcon, CheckCircleIcon } from 'lucide-vue-next';
import InvoiceSummary from '@/components/ui/invoices/InvoiceSummary.vue';
import InvoicePayments from '@/components/ui/invoices/InvoicePayments.vue';
import InvoiceCreditNote from '@/components/ui/invoices/InvoiceCreditNote.vue';
import InvoiceDocument from '@/components/ui/invoices/InvoiceDocument.vue';

interface InvoiceDetails {
  id: number;
  number: string;
  client_name: string;
  date: string;
  due_date: string;
  status: string;
  total: number;
  currency: string;
  products: Array<any>;
  storno?: any;
  sent: boolean;
  payments?: Array<any>;
  creditNote?: any;
  document?: any;
}


const props = defineProps<{ invoice: InvoiceDetails }>();
const showPopover = ref(false);

const tabs = [
  { value: 'invoice', label: 'Invoice' },
  { value: 'history', label: 'History' },
  { value: 'notes', label: 'Notes' },
];

const handleSend = () => {
  // TODO: Implement send to client
};

const handleExportPDF = () => {
  // TODO: Implement export to PDF
};

const handleEdit = () => {
  showPopover.value = !showPopover.value;
};

const handleVoid = () => {
  // TODO: Implement void invoice
};

const handleChangeStatus = () => {
  // TODO: Implement change status
};

const handleMarkAsPaid = () => {
  // TODO: Implement mark as paid
};


const handleBack = () => {
  if (window.history.length > 1) {
    window.history.back();
  } else {
    router.visit(route('home'));
  }
};
</script>

<template>
  <Head :title="`Invoice #${invoice.number}`" />
  <AppLayout>
    <div class="container mx-auto px-4 py-6 flex flex-col gap-4">
      <div class="flex items-center gap-2 mb-2">
        <button @click="handleBack" class="p-2 rounded hover:bg-gray-100">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </button>
        <span class="text-xl font-semibold">Invoice #{{ invoice.number }}</span>
        <div class="flex gap-2 ml-auto items-center">
          <Button variant="outline" @click="handleSend">
            <SendIcon class="w-4 h-4 mr-2" />
            Send
          </Button>
          <Button variant="outline" @click="handleExportPDF">
            <FileTextIcon class="w-4 h-4 mr-2" />
            PDF
          </Button>
          <Button variant="outline" @click="handleMarkAsPaid">
            <CheckCircleIcon class="w-4 h-4 mr-2" />
            Mark as Paid
          </Button>
          <DropdownMenu>
            <DropdownMenuTrigger as-child>
              <Button variant="ghost" size="icon" :aria-label="'Acțiuni'">
                <MoreVerticalIcon class="w-5 h-5" />
              </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end">
              <DropdownMenuItem @click="handleVoid">
                <XCircleIcon class="w-4 h-4 mr-2 text-rose-500" />
                Stornează factura
              </DropdownMenuItem>
              <DropdownMenuItem @click="handleChangeStatus">
                <EditIcon class="w-4 h-4 mr-2 text-blue-500" />
                Schimbă status
              </DropdownMenuItem>
              <DropdownMenuItem v-if="!invoice.sent" @click="handleEdit">
                <EditIcon class="w-4 h-4 mr-2 text-gray-500" />
                Editează factura
              </DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>
        </div>
      </div>
      <!-- Tabs -->
      <Tabs default-value="invoice" class="mb-4 pl-3 bg-white dark:bg-black">
        <TabsList class="mb-5 gap-2 bg-gray-100 text-gray-900 dark:bg-neutral-900 dark:text-white border-none">
          <TabsTrigger
            v-for="tab in tabs"
            :key="tab.value"
            :value="tab.value"
            class="data-[state=active]:bg-white data-[state=active]:text-black dark:data-[state=active]:bg-neutral-800 dark:data-[state=active]:text-white"
          >
            {{ tab.label }}
          </TabsTrigger>
        </TabsList>
        <TabsContent value="invoice">
          <div class="flex flex-col md:flex-row gap-8">
            <div class="flex-1 min-w-[340px] max-w-[420px] flex flex-col gap-4 pl-1">
              <div class="flex items-center gap-4 mb-4">
                <span class="flex items-center bg-red-50 text-red-600 text-xs font-semibold px-3 py-1 rounded mr-2 dark:bg-red-900/30 dark:text-red-400">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                  Overdue
                </span>
                <span class="text-xs text-red-500 font-medium dark:text-red-400">18 days delay</span>
              </div>
              <div class="grid grid-cols-2 gap-4 mb-6 bg-white dark:bg-black rounded-xl border border-gray-200 dark:border-gray-700 p-8 shadow-sm">
                <div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">Total Amount</div>
                  <div class="text-lg font-semibold text-red-600 dark:text-red-400">$ 9,276.39</div>
                </div>
                <div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">Open Amount</div>
                  <div class="text-lg font-semibold dark:text-white">$ 5,276.39</div>
                </div>
                <div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">VAT. Amount</div>
                  <div class="text-lg font-semibold dark:text-white">$ 1200.00</div>
                </div>
                <div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">Due Date</div>
                  <div class="text-base font-medium dark:text-white">03/03/2023</div>
                </div>
                <div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">Paid On</div>
                  <div class="text-base font-medium dark:text-white">-</div>
                </div>
                <div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">Customer av delay</div>
                  <div class="text-base font-medium dark:text-white">10 days</div>
                </div>
              </div>
              <div class="flex items-center justify-between mt-6 mb-2">
                <div class="font-semibold text-lg">Payments</div>
                <Button variant="outline" size="sm" class="text-black border-black hover:bg-gray-100 dark:text-white dark:border-gray-500 dark:hover:bg-gray-800">+ Add</Button>
              </div>
              <div class="flex flex-col gap-2 mb-6">
                <div class="flex items-center gap-2">
                  <span class="font-semibold">$200.00</span>
                  <span class="text-xs text-gray-400">|</span>
                  <span class="text-green-600 font-medium">Matched transaction</span>
                </div>
                <div class="text-xs text-gray-500 ml-1">Paid on 10 July &nbsp; <span class="text-gray-400">Devoteam 18818</span></div>
                <div class="flex items-center gap-2 mt-2">
                  <span class="font-semibold">$200.00</span>
                  <span class="text-xs text-gray-400">|</span>
                  <span class="font-medium">Manual Mark</span>
                </div>
                <div class="text-xs text-gray-500 ml-1">Paid on 10 July</div>
              </div>
              <div class="flex items-center justify-between mt-6 mb-2">
                <div class="font-semibold text-lg">Credit Note</div>
                <Button variant="outline" size="sm" class="text-black border-black hover:bg-gray-100 dark:text-white dark:border-gray-500 dark:hover:bg-gray-800">+ Add</Button>
              </div>
              <div class="flex flex-col gap-2">
                <div class="flex items-center gap-2">
                  <span class="font-medium">D-1212012-1</span>
                  <span class="text-xs text-gray-400">|</span>
                  <span class="font-semibold">$234.44</span>
                </div>
                <div class="text-xs text-gray-500 ml-1">Created on : 30/09/2022</div>
              </div>
            </div>
            <div class="flex-1 min-w-[240px] max-w-1xl mx-auto px-20">
              <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-2 shadow-sm overflow-hidden">
                <div class="rounded-lg p-4 min-h-[600px]">
                  <div class="bg-white dark:bg-black text-gray-900 dark:text-white rounded-lg p-4 min-h-[600px]">
                    <InvoiceDocument :invoice="invoice.document || invoice" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </TabsContent>
        <TabsContent value="history">
          <div class="text-gray-500 italic">History content here...</div>
        </TabsContent>
        <TabsContent value="notes">
          <div class="text-gray-500 italic">Notes content here...</div>
        </TabsContent>
      </Tabs>
    </div>
  </AppLayout>
</template>

