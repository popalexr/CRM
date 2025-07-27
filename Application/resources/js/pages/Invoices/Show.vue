<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { router as inertiaRouter } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { Tabs, TabsList, TabsTrigger, TabsContent } from '@/components/ui/tabs';
import { MoreVerticalIcon, SendIcon, FileTextIcon, EditIcon, XCircleIcon, CheckCircleIcon } from 'lucide-vue-next';
import { InvoiceDetails } from '@/types/invoices';

const ui = {
  tabs: {
    invoice: 'Invoice',
    history: 'History',
    notes: 'Notes',
  },
  notDue: 'Not due',
  daysUntilDue: 'days until due',
  totalAmount: 'Total Amount',
  openAmount: 'Open Amount',
  vatAmount: 'VAT. Amount',
  dueDate: 'Due Date',
  paidOnLabel: 'Paid On',
  customerAvDelay: 'Customer av delay',
  payments: 'Payments',
  add: 'Add',
  paidOn: 'Paid on',
  noPayments: 'No payments yet.',
  addPayment: 'Add Payment',
  amount: 'Amount',
  cancel: 'Cancel',
  productsServices: 'Products / Services',
  noItems: 'No items.',
  subtotal: 'Subtotal:',
  discount: 'Discount:',
  vat: 'VAT:',
  total: 'Total:',
  paymentMethod: 'Payment method: Bank transfer',
  issuedBy: 'Issued by:',
  signature: 'Signature: ____________________',
  changeStyle: 'Change Style',
  historyContent: 'History content here...',
  notesContent: 'Notes content here...',
  voidInvoice: 'Void Invoice',
  changeStatus: 'Change Status',
  editInvoice: 'Edit Invoice',
};

const tabs = [
  { value: 'invoice', label: ui.tabs.invoice },
  { value: 'history', label: ui.tabs.history },
  { value: 'notes', label: ui.tabs.notes }
];


const props = defineProps<{ invoice: InvoiceDetails, products: Array<any> }>();
const showPopover = ref(false);
const showPaymentModal = ref(false);
const payments = ref(props.invoice.payments ? [...props.invoice.payments] : []);

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
        const props: any = page.props;
        if (props?.invoice?.payments) {
          payments.value = [...props.invoice.payments];
          const open = props.invoice.total - (props.invoice.payments.reduce((sum: number, p: any) => sum + (p.amount || 0), 0) || 0);
          if (open <= 0) {
            props.invoice.status = 'paid';
          }
        } else {
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

const handleMarkAsPaid = () => {
  const today = new Date().toISOString().slice(0, 10);
  const open = openAmount.value;
  if (open <= 0) {
    alert('Invoice is already fully paid.');
    return;
  }
  inertiaRouter.post(
    `/invoices/${props.invoice.id}/payments`,
    {
      amount: open,
      paid_on: today
    },
    {
      preserveScroll: true,
      onSuccess: (page) => {
        const props: any = page.props;
        if (props?.invoice?.payments) {
          payments.value = [...props.invoice.payments];
          const open = props.invoice.total - (props.invoice.payments.reduce((sum: number, p: any) => sum + (p.amount || 0), 0) || 0);
          if (open <= 0) {
            props.invoice.status = 'paid';
          }
        } else {
          window.location.reload();
        }
      },
      onError: (errors) => {
        alert('Failed to save payment: ' + (errors?.message || ''));
      }
    }
  );
};

const handleEdit = () => {
  router.visit(`/invoices/${props.invoice.id}/edit`);
};

const handleChangeStatus = () => {
};

const handleChangeStyle = () => {
  alert('Change Style: This will allow you to switch between 3 invoice designs. (To be implemented)');
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
          <Button variant="outline">
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
              <DropdownMenuItem>
                <XCircleIcon class="w-4 h-4 mr-2 text-rose-500" />
                {{ ui.voidInvoice }}
              </DropdownMenuItem>
              <DropdownMenuItem @click="handleChangeStatus">
                <EditIcon class="w-4 h-4 mr-2 text-blue-500" />
                {{ ui.changeStatus }}
              </DropdownMenuItem>
              <DropdownMenuItem @click="handleEdit">
                <EditIcon class="w-4 h-4 mr-2 text-gray-500" />
                {{ ui.editInvoice }}
              </DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>
        </div>
      </div>
      <Tabs default-value="invoice" class="mb-4 pl-3 bg-white dark:bg-[rgba(0,0,0,0)]">
        <TabsList class="mb-5 gap-2 bg-gray-100 text-gray-900 dark:bg-[rgba(0,0,0,0)] dark:text-white border-none">
          <TabsTrigger
            v-for="tab in tabs"
            :key="tab.value"
            :value="tab.value"
            class="data-[state=active]:bg-white data-[state=active]:text-black dark:data-[state=active]:bg-[rgba(0,0,0,0)] dark:data-[state=active]:text-white"
          >
            {{ tab.label }}
          </TabsTrigger>
        </TabsList>
        <TabsContent value="invoice">
          <div class="flex flex-col md:flex-row gap-8">
            <div class="flex-1 min-w-[340px] max-w-[420px] flex flex-col gap-4 pl-1">              
              <div class="flex items-center gap-4 mb-4">
                <template v-if="invoice.due_date">
                  <template v-if="payments.length > 0">
                    <template v-if="openAmount <= 0">
                      <template v-if="payments.every(p => new Date(p.paid_on) <= new Date(invoice.due_date))">
                        <span class="flex items-center bg-green-50 text-green-600 text-xs font-semibold px-3 py-1 rounded mr-2 dark:bg-green-900/30 dark:text-green-400">
                          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M9 12l2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                          On time
                        </span>
                        <span class="text-xs text-green-600 font-medium dark:text-green-400">Paid on {{ payments[payments.length-1].paid_on }}</span>
                      </template>
                      <template v-else>
                        <span class="flex items-center bg-red-50 text-red-600 text-xs font-semibold px-3 py-1 rounded mr-2 dark:bg-red-900/30 dark:text-red-400">
                          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                          Overdue
                        </span>
                        <span class="text-xs text-red-500 font-medium dark:text-red-400">
                          Paid {{ Math.ceil((new Date(payments[payments.length-1].paid_on).getTime() - new Date(invoice.due_date).getTime()) / (1000*60*60*24)) }} days late
                        </span>
                      </template>
                    </template>
                    <template v-else>
                      <template v-if="new Date() > new Date(invoice.due_date)">
                        <span class="flex items-center bg-red-50 text-red-600 text-xs font-semibold px-3 py-1 rounded mr-2 dark:bg-red-900/30 dark:text-red-400">
                          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                          Overdue
                        </span>
                        <span class="text-xs text-red-500 font-medium dark:text-red-400">
                          {{ Math.ceil((new Date().getTime() - new Date(invoice.due_date).getTime()) / (1000*60*60*24)) }} days overdue, partial payment
                        </span>
                      </template>
                      <template v-else>
                        <span class="flex items-center bg-yellow-50 text-yellow-600 text-xs font-semibold px-3 py-1 rounded mr-2 dark:bg-yellow-900/30 dark:text-yellow-400">
                          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M9 12l2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                          Partial payment
                        </span>
                        <span class="text-xs text-yellow-600 font-medium dark:text-yellow-400">
                          {{ Math.ceil((new Date(invoice.due_date).getTime() - new Date().getTime()) / (1000*60*60*24)) }} days until due
                        </span>
                      </template>
                    </template>
                  </template>
                  <template v-else>
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
                        {{ ui.notDue }}
                      </span>
                      <span class="text-xs text-green-600 font-medium dark:text-green-400">
                        {{ Math.ceil((new Date(invoice.due_date).getTime() - new Date().getTime()) / (1000*60*60*24)) }} {{ ui.daysUntilDue }}
                      </span>
                    </template>
                  </template>
                </template>
              </div>
              <div class="grid grid-cols-2 gap-4 mb-6 bg-white dark:bg-[rgba(0,0,0,0)] rounded-xl border border-gray-200 dark:border-gray-700 p-8 shadow-sm">
                <div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">{{ ui.totalAmount }}</div>
                  <div class="text-lg font-semibold text-red-600 dark:text-red-400">{{ invoice.total?.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }} {{ invoice.currency }}</div>
                </div>
                <div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">{{ ui.openAmount }}</div>
                  <div class="text-lg font-semibold dark:text-white">{{ openAmount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }} {{ invoice.currency }}</div>
                </div>
                <div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">{{ ui.vatAmount }}</div>
                  <div class="text-lg font-semibold dark:text-white">{{ (products?.reduce((sum, p) => sum + ((p.total || 0) - (p.total_no_vat || 0)), 0)).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }} {{ invoice.currency }}</div>
                </div>
                <div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">{{ ui.dueDate }}</div>
                  <div class="text-base font-medium dark:text-white">{{ invoice.due_date }}</div>
                </div>
                <div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">{{ ui.paidOnLabel }}</div>
                  <div class="text-base font-medium dark:text-white">
                    <template v-if="payments.length > 0 && openAmount <= 0">
                      {{
                        (() => {
                          const today = new Date().toISOString().slice(0, 10);
                          const validPayments = payments.filter(p => p.paid_on && p.paid_on <= today);
                          if (validPayments.length === 0) return '-';
                          return validPayments[validPayments.length-1].paid_on;
                        })()
                      }}
                    </template>
                    <template v-else>
                      -
                    </template>
                  </div>
                </div>
                <div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">{{ ui.customerAvDelay }}</div>
                  <div class="text-base font-medium dark:text-white">
                    {{ invoice.due_date && invoice.payments && invoice.payments.length > 0 && invoice.payments[0].paid_on ?
                      (Math.max(0, Math.ceil(((new Date(invoice.payments[0].paid_on).getTime() - new Date(invoice.due_date).getTime()) / (1000*60*60*24)))) + ' days') : '-' }}
                  </div>
                </div>
              </div>
                <div class="flex items-center justify-between mt-6 mb-2">
                    <div class="font-semibold text-lg">{{ ui.payments }}</div>
                  <Button
                    variant="outline"
                    size="sm"
                    class="text-black border-black hover:bg-gray-100 dark:text-white dark:border-gray-500 dark:hover:bg-gray-800"
                    :disabled="openAmount <= 0"
                    @click="openAmount > 0 && openPaymentModal()"
                  >
                    {{ ui.add }}
                  </Button>
                </div>
              <div class="flex flex-col gap-3 mb-6 max-h-64 overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-700 scrollbar-track-transparent">
                <template v-if="payments.length > 0">
                  <div v-for="(payment, idx) in payments" :key="idx" class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#262626] shadow-sm px-4 py-3 flex items-center gap-4">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30">
                      <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M9 12l2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                      <div class="text-base font-semibold text-gray-900 dark:text-white">{{ payment.amount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }} {{ invoice.currency }}</div>
                      <div class="text-xs text-gray-500 dark:text-gray-400">{{ ui.paidOn }} <span class="font-medium">{{ payment.paid_on }}</span></div>
                    </div>
                    <div class="text-xs text-gray-400">#{{ idx + 1 }}</div>
                  </div>
                </template>
                <template v-else>
                  <div class="rounded-xl border border-dashed border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-[rgba(0,0,0,0)]/40 px-4 py-6 text-center text-xs text-gray-400">
                    <svg class="mx-auto mb-2 w-8 h-8 text-gray-300 dark:text-gray-700" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 8v4l2 2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="9"/></svg>
                    {{ ui.noPayments }}
                  </div>
                </template>
              </div>

              <div v-if="showPaymentModal" class="fixed inset-0 z-50 flex items-center justify-center bg-[rgba(0,0,0,0)]/80 backdrop-blur-sm">
                <div class="w-full max-w-xs rounded-2xl p-6 flex flex-col gap-5 shadow-2xl border border-gray-100 dark:border-neutral-900 bg-white dark:bg-[rgba(0,0,0,0)]">
                  <div class="font-semibold text-lg mb-2 text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-6 h-6 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 8v4l2 2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    {{ ui.addPayment }}
                  </div>
                  <div class="flex flex-col gap-3">
                    <label class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ ui.amount }}</label>
                    <input v-model="newPayment.amount" type="number" min="0" step="0.01" placeholder="0.00" class="rounded-lg px-3 py-2 border border-gray-200 dark:border-neutral-900 bg-white dark:bg-[rgba(0,0,0,0)] focus:ring-2 focus:ring-gray-200 dark:focus:ring-neutral-900 outline-none text-base text-gray-900 dark:text-white transition" />
                  </div>
                  <div class="flex flex-col gap-3">
                    <label class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ ui.paidOn }}</label>
                    <input v-model="newPayment.paid_on" type="date" class="rounded-lg px-3 py-2 border border-gray-200 dark:border-neutral-900 bg-white dark:bg-[rgba(0,0,0,0)] focus:ring-2 focus:ring-gray-200 dark:focus:ring-neutral-900 outline-none text-base text-gray-900 dark:text-white transition" />
                  </div>
                  <div class="flex gap-2 mt-2">
                    <Button variant="outline" size="sm" class="flex-1 bg-gray-50 dark:bg-[rgba(0,0,0,0)] border-gray-200 dark:border-neutral-900 hover:bg-gray-100 dark:hover:bg-[rgba(0,0,0,0)]/80 text-gray-700 dark:text-gray-200" @click="showPaymentModal = false">{{ ui.cancel }}</Button>
                    <Button variant="default" size="sm" class="flex-1 bg-[rgba(0,0,0,0)] dark:bg-white text-white dark:text-black font-semibold shadow hover:bg-gray-800 dark:hover:bg-gray-200 dark:hover:bg-gray-100" @click="addPayment">{{ ui.add }}</Button>
                  </div>
                </div>
              </div>
            </div>
            <div class="flex-1 min-w-[240px] max-w-2xl mx-auto px-4 mt-13">
              <div style="margin-top: -2.9rem;" lass="flex justify-end mb-4">
                <Button variant="outline" class="flex items-center gap-2 ml-auto" style="margin-bottom: 12px;" @click="handleChangeStyle">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-4 h-4 mr-1"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/></svg>
                  {{ ui.changeStyle }}
                </Button>
              </div>
              <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-8 shadow-sm bg-white dark:bg-[rgba(0,0,0,0)] max-h-155 overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-700 scrollbar-track-transparent dark:text-white">
                <div class="flex justify-between items-start mb-6">
                  <div class="flex flex-col items-start justify-center gap-6 flex-1 min-w-[180px] text-left">
                    <div>
                      <div class="text-3xl font-bold mb-2">INVOICE</div>
                      <div class="text-xs text-gray-500 dark:text-white mb-1">No: <span class="font-semibold text-gray-900 dark:text-white">{{ invoice.number }} / {{ invoice.id }}</span></div>
                      <div class="text-xs text-gray-500 dark:text-white mb-1">Data:  <span class="font-semibold text-gray-900 dark:text-white">{{ invoice.created_at ? invoice.created_at.slice(0, 10) : '-' }}</span></div>
                      <div class="text-xs text-gray-500 dark:text-white mb-1">Due Date:  <span class="font-semibold text-gray-900 dark:text-white">{{ invoice.due_date }}</span></div>
                    </div>
                    <div style="margin-top: 0.9rem;" class="flex flex-col items-center text-center max-w-xs">
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
                      <div class="text-xs text-gray-500 dark:text-white">CUI/CNP: {{ invoice.client_vat_code || '-' }}</div>
                      <div class="text-xs text-gray-500 dark:text-white">Bank: {{ invoice.client_bank || '-' }}</div>
                      <div class="text-xs text-gray-500 dark:text-white">IBAN: {{ invoice.client_iban || '-' }}</div>
                      <div class="text-xs text-gray-500 dark:text-white">Phone: {{ invoice.client_phone || '-' }}</div>
                    </div>
                  </div>
                  <div class="flex flex-col items-center w-64 min-w-[180px] gap-2 text-center">
                    <img :src="($page.props.companyInfo as any)?.logo_url || '/favicon.svg'" alt="Company Logo" class="w-32 h-32 object-contain mb-2" />
                    <div>
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
                    </div>
                  </div>
                </div>
                <div class="mb-4">
                  <div class="text-xs font-semibold mb-2">{{ ui.productsServices }}</div>
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
                          <td colspan="7" class="px-2 py-1 text-center text-gray-400">{{ ui.noItems }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="flex flex-col gap-2 mt-6 text-sm w-full max-w-xs ml-auto items-end">
                  <div class="grid grid-cols-2 gap-x-4 w-full">
                    <div class="flex flex-col gap-2 items-start">
                      <span class="text-gray-500 dark:text-white">{{ ui.subtotal }}</span>
                      <span class="text-gray-500 dark:text-white">{{ ui.discount }}</span>
                      <span class="text-gray-500 dark:text-white">{{ ui.vat }}</span>
                      <span class="text-gray-500 dark:text-white text-lg mt-2">{{ ui.total }}</span>
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
                  <div>{{ ui.paymentMethod }}</div>
                  <div>
                    IBAN: {{ ($page.props.companyInfo as any)?.iban || '-' }}
                  </div>
                  <div>
                    Bank: {{ ($page.props.companyInfo as any)?.bank || '-' }}
                  </div>
                  </div>
                  <div class="text-xs text-gray-500 text-right dark:text-gray-300">
                    <div>{{ ui.issuedBy }} <span class="font-semibold text-gray-900 dark:text-white">{{ ($page.props.companyInfo as any)?.company_name || '-' }}</span></div>
                    <div>{{ ui.signature }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </TabsContent>
        <TabsContent value="history">
          <div class="text-gray-500 italic">{{ ui.historyContent }}</div>
        </TabsContent>
        <TabsContent value="notes">
          <div class="text-gray-500 italic">{{ ui.notesContent }}</div>
        </TabsContent>
      </Tabs>
    </div>
  </AppLayout>
</template>

