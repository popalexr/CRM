<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { router as inertiaRouter } from '@inertiajs/vue3';
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
  client_address?: string;
  client_city?: string;
  client_country?: string;
  client_vat_code?: string;
}



const props = defineProps<{ invoice: InvoiceDetails, products: Array<any> }>();
const showPopover = ref(false);
const showPaymentModal = ref(false);
const payments = ref(props.invoice.payments ? [...props.invoice.payments] : []);

const deletePayment = (idx: number) => {
  if (!confirm('Are you sure you want to delete this payment?')) return;
  inertiaRouter.post(
    `/invoices/${props.invoice.id}/payments`,
    {
      _method: 'delete',
      index: idx
    },
    {
      preserveScroll: true,
      onSuccess: (page) => {
        const props: any = page.props;
        if (props?.invoice?.payments) {
          payments.value = [...props.invoice.payments];
        } else {
          window.location.reload();
        }
      },
      onError: (errors) => {
        alert('Failed to delete payment: ' + (errors?.message || ''));
      }
    }
  );
};
const newPayment = ref({ amount: '', paid_on: '' });

const openPaymentModal = () => {
  newPayment.value = { amount: '', paid_on: '' };
  showPaymentModal.value = true;
};


const addPayment = () => {
  if (!newPayment.value.amount || !newPayment.value.paid_on) return;
  inertiaRouter.post(
    `/invoices/${props.invoice.id}/payments`,
    {
      amount: newPayment.value.amount,
      paid_on: newPayment.value.paid_on
    },
    {
      preserveScroll: true,
      onSuccess: (page) => {
        // Try to update payments from response if available
        const props: any = page.props;
        if (props?.invoice?.payments) {
          payments.value = [...props.invoice.payments];
        } else {
          // fallback: reload page
          window.location.reload();
        }
        showPaymentModal.value = false;
      },
      onError: (errors) => {
        alert('Failed to save payment: ' + (errors?.message || '')); 
      }
    }
  );
};

const openAmount = computed(() => {
  return props.invoice.total - (payments.value.reduce((sum, p) => sum + (p.amount || 0), 0) || 0);
});

const tabs = [
  { value: 'invoice', label: 'Invoice' },
  { value: 'history', label: 'History' },
  { value: 'notes', label: 'Notes' },
];

const handleSend = () => {
  inertiaRouter.post(`/invoices/${props.invoice.id}/send`, {}, {
    onSuccess: () => {
      alert('Invoice sent to client!');
    },
    onError: (errors) => {
      alert('Failed to send invoice: ' + (errors?.message || ''));
    }
  });
};

const handleExportPDF = () => {
  window.open(`/invoices/${props.invoice.id}/pdf`, '_blank');
};


const handleMarkAsPaid = () => {
  const today = new Date().toISOString().slice(0, 10);
  const open = openAmount.value;
  if (open <= 0) {
    alert('Invoice is already fully paid.');
    return;
  }
  payments.value.push({ amount: open, paid_on: today });
  props.invoice.status = 'paid';
  props.invoice.sent = true;
};

const handleEdit = () => {
  router.visit(`/invoices/${props.invoice.id}/edit`);
};

const handleVoid = () => {
};

const handleChangeStatus = () => {
};



const handleBack = () => {
  if (window.history.length > 1) {
    window.history.back();
  } else {
    router.visit(route('home'));
  }
};

const goToInvoicesIndex = () => {
  router.visit(route('invoices.index'));
};
</script>

<template>
  <Head :title="`Invoice #${invoice.id}`" />
  <AppLayout>
    <div class="container mx-auto px-4 py-6 flex flex-col gap-4">
      <div class="flex items-center gap-2 mb-2">
        <button data-slot="button" @click="goToInvoicesIndex" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive hover:bg-accent hover:text-accent-foreground dark:hover:bg-accent/50 h-8 rounded-md px-3 has-[>svg]:px-2.5 gap-2 cursor-pointer">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left-icon h-4 w-4"><path d="m12 19-7-7 7-7"></path><path d="M19 12H5"></path></svg>
        </button>
        <span class="text-xl font-semibold">Invoice #{{ invoice.id }}</span>
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
              <DropdownMenuItem @click="handleEdit">
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
                <template v-if="invoice.due_date">
                  <template v-if="invoice.payments && invoice.payments.length > 0 && invoice.payments[0].paid_on">
                    <!-- Paid: check if paid on time or late -->
                    <template v-if="new Date(invoice.payments[0].paid_on) <= new Date(invoice.due_date)">
                      <span class="flex items-center bg-green-50 text-green-600 text-xs font-semibold px-3 py-1 rounded mr-2 dark:bg-green-900/30 dark:text-green-400">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M9 12l2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        On time
                      </span>
                      <span class="text-xs text-green-600 font-medium dark:text-green-400">Paid on {{ invoice.payments[0].paid_on }}</span>
                    </template>
                    <template v-else>
                      <span class="flex items-center bg-red-50 text-red-600 text-xs font-semibold px-3 py-1 rounded mr-2 dark:bg-red-900/30 dark:text-red-400">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Overdue
                      </span>
                      <span class="text-xs text-red-500 font-medium dark:text-red-400">
                        Paid {{ Math.ceil((new Date(invoice.payments[0].paid_on).getTime() - new Date(invoice.due_date).getTime()) / (1000*60*60*24)) }} days late
                      </span>
                    </template>
                  </template>
                  <template v-else>
                    <!-- Not paid: check if overdue or not -->
                    <template v-if="new Date() > new Date(invoice.due_date)">
                      <span class="flex items-center bg-red-50 text-red-600 text-xs font-semibold px-3 py-1 rounded mr-2 dark:bg-red-900/30 dark:text-red-400">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Overdue
                      </span>
                      <span class="text-xs text-red-500 font-medium dark:text-red-400">
                        {{ Math.ceil((new Date().getTime() - new Date(invoice.due_date).getTime()) / (1000*60*60*24)) }} days overdue
                      </span>
                    </template>
                    <template v-else>
                      <span class="flex items-center bg-green-50 text-green-600 text-xs font-semibold px-3 py-1 rounded mr-2 dark:bg-green-900/30 dark:text-green-400">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M9 12l2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Not due
                      </span>
                      <span class="text-xs text-green-600 font-medium dark:text-green-400">
                        {{ Math.ceil((new Date(invoice.due_date).getTime() - new Date().getTime()) / (1000*60*60*24)) }} days until due
                      </span>
                    </template>
                  </template>
                </template>
              </div>
              <div class="grid grid-cols-2 gap-4 mb-6 bg-white dark:bg-black rounded-xl border border-gray-200 dark:border-gray-700 p-8 shadow-sm">
                <div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">Total Amount</div>
                  <div class="text-lg font-semibold text-red-600 dark:text-red-400">{{ invoice.total?.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }} {{ invoice.currency }}</div>
                </div>
                <div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">Open Amount</div>
                  <div class="text-lg font-semibold dark:text-white">{{ openAmount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }} {{ invoice.currency }}</div>
                </div>
                <div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">VAT. Amount</div>
                  <div class="text-lg font-semibold dark:text-white">{{ (products?.reduce((sum, p) => sum + (p.vat_amount || 0), 0)).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }} {{ invoice.currency }}</div>
                </div>
                <div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">Due Date</div>
                  <div class="text-base font-medium dark:text-white">{{ invoice.due_date }}</div>
                </div>
                <div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">Paid On</div>
                  <div class="text-base font-medium dark:text-white">{{ invoice.payments && invoice.payments.length > 0 ? (invoice.payments[0].paid_on || '-') : '-' }}</div>
                </div>
                <div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">Customer av delay</div>
                  <div class="text-base font-medium dark:text-white">
                    {{ invoice.due_date && invoice.payments && invoice.payments.length > 0 && invoice.payments[0].paid_on ?
                      (Math.max(0, Math.ceil(((new Date(invoice.payments[0].paid_on).getTime() - new Date(invoice.due_date).getTime()) / (1000*60*60*24)))) + ' days') : '-' }}
                  </div>
                </div>
              </div>
              <div class="flex items-center justify-between mt-6 mb-2">
                <div class="font-semibold text-lg">Payments</div>
                <Button variant="outline" size="sm" class="text-black border-black hover:bg-gray-100 dark:text-white dark:border-gray-500 dark:hover:bg-gray-800" @click="openPaymentModal">+ Add</Button>
              </div>
              <div class="flex flex-col gap-3 mb-6">
                <template v-if="payments.length > 0">
                  <div v-for="(payment, idx) in payments" :key="idx" class="rounded-xl border border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 via-white to-gray-100 dark:from-neutral-900 dark:via-black dark:to-neutral-800 shadow-sm px-4 py-3 flex items-center gap-4">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30">
                      <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M9 12l2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                      <div class="text-base font-semibold text-gray-900 dark:text-white">{{ payment.amount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }} {{ invoice.currency }}</div>
                      <div class="text-xs text-gray-500 dark:text-gray-400">Paid on <span class="font-medium">{{ payment.paid_on }}</span></div>
                    </div>
                    <div class="text-xs text-gray-400">#{{ idx + 1 }}</div>
                  </div>
                </template>
                <template v-else>
                  <div class="rounded-xl border border-dashed border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-neutral-900/40 px-4 py-6 text-center text-xs text-gray-400">
                    <svg class="mx-auto mb-2 w-8 h-8 text-gray-300 dark:text-gray-700" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 8v4l2 2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="9"/></svg>
                    No payments yet.
                  </div>
                </template>
              </div>

              <!-- Payment Modal -->
              <div v-if="showPaymentModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
                <div class="w-full max-w-xs rounded-2xl p-6 flex flex-col gap-5 shadow-2xl border border-gray-100 dark:border-neutral-900 bg-white dark:bg-black">
                  <div class="font-semibold text-lg mb-2 text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-6 h-6 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 8v4l2 2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Add Payment
                  </div>
                  <div class="flex flex-col gap-3">
                    <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Amount</label>
                    <input v-model="newPayment.amount" type="number" min="0" step="0.01" placeholder="0.00" class="rounded-lg px-3 py-2 border border-gray-200 dark:border-neutral-900 bg-white dark:bg-black focus:ring-2 focus:ring-gray-200 dark:focus:ring-neutral-900 outline-none text-base text-gray-900 dark:text-white transition" />
                  </div>
                  <div class="flex flex-col gap-3">
                    <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Paid On</label>
                    <input v-model="newPayment.paid_on" type="date" class="rounded-lg px-3 py-2 border border-gray-200 dark:border-neutral-900 bg-white dark:bg-black focus:ring-2 focus:ring-gray-200 dark:focus:ring-neutral-900 outline-none text-base text-gray-900 dark:text-white transition" />
                  </div>
                  <div class="flex gap-2 mt-2">
                    <Button variant="outline" size="sm" class="flex-1 bg-gray-50 dark:bg-black border-gray-200 dark:border-neutral-900 hover:bg-gray-100 dark:hover:bg-neutral-900 text-gray-700 dark:text-gray-200" @click="showPaymentModal = false">Cancel</Button>
                    <Button variant="default" size="sm" class="flex-1 bg-black dark:bg-white text-white dark:text-black font-semibold shadow hover:bg-gray-800 dark:hover:bg-gray-200 dark:hover:bg-gray-100" @click="addPayment">Add</Button>
                  </div>
                </div>
              </div>
            </div>
            <div class="flex-1 min-w-[240px] max-w-1xl mx-auto px-4 mt-13">
              <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm bg-white dark:bg-black">
                <div class="mb-6 flex items-center justify-between">
                  <div>
                    <div class="text-2xl font-bold mb-1">Invoice</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Invoice No: <span class="font-semibold text-gray-900 dark:text-white">{{ invoice.number }} # {{ invoice.id }}</span></div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Due Date: <span class="font-semibold text-gray-900 dark:text-white">{{ invoice.due_date }}</span></div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Ref: <span class="font-semibold text-gray-900 dark:text-white">   EN092309</span></div>
                  </div>
                  <div class="text-right">
                    <div class="text-xs text-gray-500 dark:text-gray-400">Customer</div>
                    <div class="font-semibold text-gray-900 dark:text-white">{{ invoice.client_name }}</div>
                  </div>
                </div>
                <div class="mb-4">
                  <div class="text-xs flex items-center">
                    <span class="font-semibold mr-1">Deliver To:</span>
                    <span>
                      {{ invoice.client_name }}<span v-if="invoice.client_address && invoice.client_address.trim() !== ''">, {{ invoice.client_address }}</span><span v-if="invoice.client_city && invoice.client_city.trim() !== ''">, {{ invoice.client_city }}</span><span v-if="invoice.client_country && invoice.client_country.trim() !== ''">, {{ invoice.client_country }}</span><span v-if="invoice.client_vat_code && invoice.client_vat_code.trim() !== ''">, VAT: {{ invoice.client_vat_code }}</span>
                    </span>
                  </div>
                </div>
                <div class="mb-4">
                  <div class="text-xs font-semibold mb-2">Order Details</div>
                  <table class="min-w-full text-xs border rounded-xl overflow-hidden bg-white dark:bg-neutral-900">
                    <thead>
                      <tr class="bg-gray-100 dark:bg-neutral-800">
                        <th class="px-2 py-1 text-left">Item</th>
                        <th class="px-2 py-1 text-right">Qty</th>
                        <th class="px-2 py-1 text-right">Unit Price</th>
                        <th class="px-2 py-1 text-right">Tax</th>
                        <th class="px-2 py-1 text-right">Discount</th>
                        <th class="px-2 py-1 text-right">Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(product, idx) in products" :key="product.id">
                        <td class="px-2 py-1">{{ product.product_name }}</td>
                        <td class="px-2 py-1 text-right">{{ product.quantity }}</td>
                        <td class="px-2 py-1 text-right">{{ product.price.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }} {{ invoice.currency }}</td>
                        <td class="px-2 py-1 text-right">{{ (product.vat_amount || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }} {{ invoice.currency }}</td>
                        <td class="px-2 py-1 text-right">{{ (product.discount || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }} {{ invoice.currency }}</td>
                        <td class="px-2 py-1 text-right">{{ (product.price * product.quantity - (product.discount || 0)).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }} {{ invoice.currency }}</td>
                      </tr>
                      <tr v-if="!products || products.length === 0">
                        <td colspan="6" class="px-2 py-1 text-center text-gray-400">No items.</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="flex flex-col gap-1 mt-6 text-sm">
                  <div class="flex justify-between">
                    <span>Total HT:</span>
                    <span class="font-semibold">{{ (products?.reduce((sum, p) => sum + (p.price * p.quantity - (p.discount || 0)), 0)).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }} {{ invoice.currency }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span>Discount:</span>
                    <span class="font-semibold">{{ (products?.reduce((sum, p) => sum + (p.discount || 0), 0)).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }} {{ invoice.currency }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span>Total VAT:</span>
                    <span class="font-semibold">{{ (products?.reduce((sum, p) => sum + (p.vat_amount || 0), 0)).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }} {{ invoice.currency }}</span>
                  </div>
                  <div class="flex justify-between text-lg mt-2">
                    <span>Total Due:</span>
                    <span class="font-bold text-red-600 dark:text-red-400">{{ invoice.total?.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }} {{ invoice.currency }}</span>
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

