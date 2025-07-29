<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { router as inertiaRouter } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { Tabs, TabsList, TabsTrigger, TabsContent } from '@/components/ui/tabs';
import { MoreVerticalIcon, SendIcon, FileTextIcon, EditIcon, XCircleIcon, CheckCircleIcon } from 'lucide-vue-next';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import { InvoiceDetails } from '@/types/invoices';

import { ui, tabs } from '../../invoices_const';
import InvoiceDesign1 from './Designs/InvoiceDesign1.vue';
import InvoiceDesign2 from './Designs/InvoiceDesign2.vue';
import InvoiceDesign3 from './Designs/InvoiceDesign3.vue';
const invoiceDesign = ref(1);

const props = defineProps<{ invoice: InvoiceDetails, products: Array<any>, currencies: Record<string, string> }>();
const showPopover = ref(false);
const showPaymentModal = ref(false);
const payments = ref(props.invoice.payments ? [...props.invoice.payments] : []);

const newPayment = ref({ amount_paid: '', paid_at: '', currency: '' });

const openPaymentModal = () => {
  newPayment.value = { amount_paid: '', paid_at: '', currency: props.invoice.currency || '' };
  showPaymentModal.value = true;
};

const addPayment = () => {
  if (!newPayment.value.amount_paid || !newPayment.value.paid_at || !newPayment.value.currency) return;
  inertiaRouter.post(
    `/invoices/${props.invoice.id}/payments`,
    {
      amount_paid: newPayment.value.amount_paid,
      paid_at: newPayment.value.paid_at,
      currency: newPayment.value.currency
    },
    {
      preserveScroll: true,
      onSuccess: (page) => {
        const props: any = page.props;
        if (props?.invoice?.payments) {
          payments.value = [...props.invoice.payments];
          const open = props.invoice.total - (props.invoice.payments.reduce((sum: number, p: any) => sum + (p.amount_paid || 0), 0) || 0);
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
  return props.invoice.total - (payments.value.reduce((sum, p) => sum + (p.amount_paid || 0), 0) || 0);
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
      amount_paid: open,
      paid_at: today,
      currency: props.invoice.currency 

    },
    {
      preserveScroll: true,
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


const showStyleDropdown = ref(false);
const handleChangeStyle = () => {
  showStyleDropdown.value = !showStyleDropdown.value;
};
const selectInvoiceDesign = (design: number) => {
  invoiceDesign.value = design;
  showStyleDropdown.value = false;
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
        <TabsList class="mb-5 gap-2 bg-gray-50 text-gray-900 border border-gray-200 rounded-lg dark:bg-[rgba(0,0,0,0)] dark:text-gray-200 dark:border-neutral-900">
          <TabsTrigger
            v-for="([tabValue, tabLabel], idx) in Object.entries(tabs)"
            :key="tabValue"
            :value="tabValue"
            class="data-[state=active]:bg-white data-[state=active]:text-black dark:data-[state=active]:bg-white/10 dark:data-[state=active]:text-white rounded-md"
          >
            {{ tabLabel }}
          </TabsTrigger>
        </TabsList>
        <TabsContent value="invoice">
          <div class="flex flex-col md:flex-row gap-8">
            <div class="flex-1 min-w-[340px] max-w-[420px] flex flex-col gap-4 pl-1">              
              <div class="flex items-center gap-4 mb-4">
                <template v-if="invoice.due_date">
                  <template v-if="payments.length > 0">
                    <template v-if="openAmount <= 0">
                      <template v-if="payments.every(p => new Date(p.paid_at) <= new Date(invoice.due_date))">
                        <span class="flex items-center bg-green-50 text-green-600 text-xs font-semibold px-3 py-1 rounded mr-2 dark:bg-green-900/30 dark:text-green-400">
                          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M9 12l2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                          On time
                        </span>
                        <span class="text-xs text-green-600 font-medium dark:text-green-400">Paid on {{ payments[payments.length-1].paid_at }}</span>
                      </template>
                      <template v-else>
                        <span class="flex items-center bg-red-50 text-red-600 text-xs font-semibold px-3 py-1 rounded mr-2 dark:bg-red-900/30 dark:text-red-400">
                          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                          Overdue
                        </span>
                        <span class="text-xs text-red-500 font-medium dark:text-red-400">
                          Paid {{ Math.ceil((new Date(payments[payments.length-1].paid_at).getTime() - new Date(invoice.due_date).getTime()) / (1000*60*60*24)) }} days late
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
                          const validPayments = payments.filter(p => p.paid_at && p.paid_at <= today);
                          if (validPayments.length === 0) return '-';
                          return validPayments[validPayments.length-1].paid_at;
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
                    {{ invoice.due_date && invoice.payments && invoice.payments.length > 0 && invoice.payments[0].paid_at ?
                      (Math.max(0, Math.ceil(((new Date(invoice.payments[0].paid_at).getTime() - new Date(invoice.due_date).getTime()) / (1000*60*60*24)))) + ' days') : '-' }}
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
                      <div class="text-base font-semibold text-gray-900 dark:text-white">{{ (payment.amount_paid || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) }} {{ invoice.currency }}</div>
                      <div class="text-xs text-gray-500 dark:text-gray-400">{{ ui.paidOn }} <span class="font-medium">{{ payment.paid_at }}</span></div>
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

              <div v-if="showPaymentModal" class="fixed inset-0 z-50 flex items-center justify-center bg-[rgba(0,0,0,0)]/80 backdrop-blur-sm" @click.self="showPaymentModal = false">
                <div class="w-full max-w-xs rounded-2xl p-6 flex flex-col gap-5 shadow-2xl border border-gray-100 dark:border-neutral-900 bg-white dark:bg-black">
                  <div class="font-semibold text-lg mb-2 text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-6 h-6 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 8v4l2 2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    {{ ui.addPayment }}
                  </div>
                  <div class="flex flex-col gap-3">
                    <label class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ ui.amount_paid }}</label>
                    <input v-model="newPayment.amount_paid" type="number" min="0" step="0.01" placeholder="0.00" class="rounded-lg px-3 py-2 border border-gray-200 dark:border-neutral-900 bg-white dark:bg-[rgba(0,0,0,0)] focus:ring-2 focus:ring-gray-200 dark:focus:ring-neutral-900 outline-none text-base text-gray-900 dark:text-white transition" />
                  </div>
                  <div class="flex flex-col gap-3">
                    <label class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ ui.paidOn }}</label>
                    <input v-model="newPayment.paid_at" type="date" class="rounded-lg px-3 py-2 border border-gray-200 dark:border-neutral-900 bg-white dark:bg-[rgba(0,0,0,0)] focus:ring-2 focus:ring-gray-200 dark:focus:ring-neutral-900 outline-none text-base text-gray-900 dark:text-white transition" />
                  </div>
                  <div class="flex flex-col gap-3">
                    <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Currency</label>
                    <Select v-model="newPayment.currency" class="w-full">
                      <SelectTrigger class="w-full">
                        <SelectValue placeholder="Select currency" />
                      </SelectTrigger>
                      <SelectContent class="w-full">
                        <SelectGroup>
                          <SelectItem v-for="(label, code) in props.currencies" :key="code" :value="code">
                            {{ label }} ({{ code }})
                          </SelectItem>
                        </SelectGroup>
                      </SelectContent>
                    </Select>
                  </div>
                  <div class="flex gap-2 mt-2">
                    <Button variant="outline" size="sm" class="flex-1 bg-gray-50 dark:bg-[rgba(0,0,0,0)] border-gray-200 dark:border-neutral-900 hover:bg-gray-100 dark:hover:bg-[rgba(0,0,0,0)]/80 text-gray-700 dark:text-gray-200" @click="showPaymentModal = false">{{ ui.cancel }}</Button>
                    <Button variant="outline" size="sm" class="flex-1 bg-gray-50 dark:bg-[rgba(0,0,0,0)] border-gray-200 dark:border-neutral-900 hover:bg-gray-100 dark:hover:bg-[rgba(0,0,0,0)]/80 text-gray-700 dark:text-gray-200" @click="addPayment">{{ ui.add }}</Button>
                  </div>
                </div>
              </div>
            </div>
            <div class="flex-1 min-w-[240px] max-w-2xl mx-auto px-4 mt-13">
              <div style="margin-top: -3.7rem;" class="flex justify-end mb-4">
              </div>
              <div>
                <div class="flex justify-end mb-2">
                  <Tabs v-model="invoiceDesign">
                    <TabsList class="gap-2 bg-gray-50 text-gray-900 border border-gray-200 rounded-lg dark:bg-[rgba(0,0,0,0)] dark:text-gray-200 dark:border-neutral-900">
                      <TabsTrigger :value="1" :class="'data-[state=active]:bg-white data-[state=active]:text-black dark:data-[state=active]:bg-white/10 dark:data-[state=active]:text-white rounded-md'">Design 1</TabsTrigger>
                      <TabsTrigger :value="2" :class="'data-[state=active]:bg-white data-[state=active]:text-black dark:data-[state=active]:bg-white/10 dark:data-[state=active]:text-white rounded-md'">Design 2</TabsTrigger>
                      <TabsTrigger :value="3" :class="'data-[state=active]:bg-white data-[state=active]:text-black dark:data-[state=active]:bg-white/10 dark:data-[state=active]:text-white rounded-md'">Design 3</TabsTrigger>
                    </TabsList>
                  </Tabs>
                </div>
                <component :is="invoiceDesign === 1 ? InvoiceDesign1 : invoiceDesign === 2 ? InvoiceDesign2 : InvoiceDesign3" :invoice="invoice" :products="products" />
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

