<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { ArrowLeftIcon } from 'lucide-vue-next';
import { ProfileHeader, NotesSection, DataList, SearchInput } from '@/components/ui';
import { computed, ref } from 'vue';
import { Client, ClientContact, type ClientPageProps } from '@/types/index';

const page = usePage<ClientPageProps>();
const client = page.props.client as Client;

const initialContacts = (page.props.clientContacts || []).map((contact: any) => ({
    id: contact.id,
    name: contact.name || contact.contact_name || '',
    email: contact.email || contact.contact_email || '',
    phone: contact.phone || contact.contact_phone || '',
    position: contact.position || contact.contact_position || '',
    client_id: contact.client_id,
    created_at: contact.created_at,
    updated_at: contact.updated_at,
    deleted_at: contact.deleted_at
})) as ClientContact[];
const formLabels = page.props.formLabels;
const contacts = ref<ClientContact[]>(initialContacts);


const searchQuery = ref('');
const invoices = ref<any[]>([]);
const loadingInvoices = ref(true);

const fetchInvoices = async () => {
    loadingInvoices.value = true;
    try {
        const res = await fetch(`/clients/invoices?id=${client.id}`);
        const data = await res.json();
        invoices.value = data.invoices || [];
    } catch (e) {
        invoices.value = [];
    } finally {
        loadingInvoices.value = false;
    }
};

import { onMounted, computed as vueComputed } from 'vue';
onMounted(fetchInvoices);

const filteredInvoices = vueComputed(() => {
    if (!searchQuery.value) return invoices.value;
    return invoices.value.filter(inv => {
        const clientName = inv.client?.client_name || inv.client?.name || '';
        return (
            String(inv.id).includes(searchQuery.value) ||
            clientName.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            (inv.status && inv.status.toLowerCase().includes(searchQuery.value.toLowerCase()))
        );
    });
});

const goToInvoice = (id: number) => {
    router.visit(route('invoices.details', { id }));
};

const isOverdue = (dateString: string) => {
    return new Date(dateString) < new Date();
};

const getStatusBadgeVariant = (status: string) => {
    switch (status) {
        case 'draft':
            return 'outline';
        case 'submitted':
            return 'secondary';
        case 'paid':
            return 'default';
        case 'not_paid':
            return 'destructive';
        case 'anulled':
            return 'secondary';
        case 'corrected':
            return 'default';
        default:
            return 'default';
    }
};

const entityData = computed(() => ({
    id: client.id,
    name: client.client_name,
    email: client.client_email || '',
    phone: client.client_phone,
    avatar: client.client_logo
}));

const fullAddress = computed(() => {
    const parts = [];
    if (client.address) parts.push(client.address);
    if (client.city) parts.push(client.city);
    if (client.county) parts.push(client.county);
    if (client.country) parts.push(client.country);
    return parts.filter(part => part).join(', ') || formLabels.messages.no_address_available;
});

const contactItems = computed(() => {
    return contacts.value.map(contact => {
        const name = contact.name && contact.name.trim() ? contact.name : 'Contact fără nume';
        return {
            id: contact.id || 0,
            title: name,
            subtitle: contact.email || '',
            avatar: undefined,
            contact_email: contact.email,
            contact_phone: contact.phone,
            contact_position: contact.position
        };
    });
});

const handleSelectContact = (contactId: number) => {
    console.log('Select contact:', contactId);
};

const handleBack = () => {
    router.visit(route('clients.index'));
};

</script>

<template>
    <Head :title="`${formLabels.headings.client_details}: ${client.client_name}`" />

    <AppLayout>
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center mb-6">
                <Button variant="ghost" size="sm" @click="handleBack" class="mr-4 p-2">
                    <ArrowLeftIcon class="w-4 h-4" />
                </Button>
                <h1 class="text-xl font-semibold dark:text-white">{{ formLabels.headings.client_details }}</h1>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <ProfileHeader :entity="{ ...entityData, address: fullAddress }" />

                    <div class="bg-white dark:bg-black rounded-xl border border-gray-200 dark:border-gray-700 p-8 shadow-sm hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ formLabels.headings.client_details }}</h3>
                            <div class="flex items-center space-x-3">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ formLabels.labels.client_tva }}</span>
                                <span :class="client.client_tva ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200'" class="px-3 py-1.5 rounded-lg text-sm font-semibold border shadow-sm">
                                    {{ client.client_tva ? formLabels.labels.yes : formLabels.labels.no }}
                                </span>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            <div class="space-y-4">
                                <div class="flex items-center space-x-2 pb-3 border-b border-gray-100">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">{{ formLabels.labels.general_info }}</h4>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex flex-col space-y-1">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ formLabels.labels.client_type }}</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ client.client_type === 'individual' ? formLabels.client_types.individual : formLabels.client_types.business }}</span>
                                    </div>
                                    <div v-if="client.cui" class="flex flex-col space-y-1">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ formLabels.labels.cui }}</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ client.cui }}</span>
                                    </div>
                                    <div v-if="client.registration_number" class="flex flex-col space-y-1">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ formLabels.labels.registration_number }}</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ client.registration_number }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="flex items-center space-x-2 pb-3 border-b border-gray-100">
                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">{{ formLabels.labels.fiscal_info }}</h4>
                                </div>
                                <div class="space-y-4">
                                    <div v-if="client.vat_number" class="flex flex-col space-y-1">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ formLabels.labels.vat_number }}</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ client.vat_number }}</span>
                                    </div>
                                    <div v-if="client.tax_code" class="flex flex-col space-y-1">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ formLabels.labels.tax_code }}</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ client.tax_code }}</span>
                                    </div>
                                    <div v-if="client.currency" class="flex flex-col space-y-1">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ formLabels.labels.currency }}</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ client.currency }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="flex items-center space-x-2 pb-3 border-b border-gray-100">
                                    <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">{{ formLabels.labels.banking_info }}</h4>
                                </div>
                                <div class="space-y-4">
                                    <div v-if="client.iban" class="flex flex-col space-y-1">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ formLabels.labels.iban }}</span>
                                         <span class="text-sm font-medium text-gray-900 dark:text-white">{{ client.iban }}</span>
                                    </div>
                                    <div v-if="client.bank_name" class="flex flex-col space-y-1">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ formLabels.labels.bank }}</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ client.bank_name }}</span>
                                    </div>
                                    <div v-if="client.swift" class="flex flex-col space-y-1">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ formLabels.labels.swift }}</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ client.swift }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-black rounded-xl border border-gray-200 dark:border-gray-700 p-8 shadow-sm hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ formLabels.headings.invoices }}</h3>
                        </div>
                        <div class="mb-4">
                            <SearchInput :placeholder="formLabels.placeholders.search_invoices" v-model="searchQuery" />
                        </div>
                        <div v-if="loadingInvoices" class="text-center p-6">
                            <span class="text-gray-500 dark:text-gray-400">Loading...</span>
                        </div>
                        <div v-else-if="filteredInvoices.length === 0" class="text-center p-6">
                            <span class="text-gray-500 dark:text-gray-400">{{ formLabels.messages.no_invoices_found }}</span>
                        </div>
                        <div v-else class="overflow-x-auto rounded-md border">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-neutral-900">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-black divide-y divide-gray-100 dark:divide-gray-800">
                                    <tr v-for="invoice in filteredInvoices" :key="invoice.id">
                                        <td class="px-4 py-2">
                                            <span v-if="invoice.original_invoice_id">
                                                #{{ invoice.id }} → #{{ invoice.original_invoice_id }}
                                            </span>
                                            <span v-else>#{{ invoice.id }}</span>
                                        </td>
                                        <td class="px-4 py-2">
                                            <div class="flex items-center gap-2">
                                                <span>{{ invoice.client?.client_name || invoice.client?.name || '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-2">
                                            <span v-if="invoice.total !== null">
                                                {{ Number(invoice.total).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }} {{ invoice.currency }}
                                            </span>
                                            <span v-else class="text-gray-400 italic">Calculating...</span>
                                        </td>
                                        <td class="px-4 py-2">
                                            <Badge :variant="getStatusBadgeVariant(invoice.status)">
                                                {{ invoice.status }}
                                            </Badge>
                                        </td>
                                        <td class="px-4 py-2">
                                            <span :class="isOverdue(invoice.payment_deadline) ? 'text-red-600 font-semibold' : ''">
                                                {{ invoice.payment_deadline }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-right">
                                            <Button size="sm" variant="outline" @click="goToInvoice(invoice.id)">
                                                View
                                            </Button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="space-y-6" style="margin-top: -2.8rem;">
                    <div>
                        <h3 class="text-lg font-semibold mb-4 dark:text-white">{{ formLabels.headings.contact_persons }}</h3>
                        <div v-if="contacts.length > 0">
                            <div class="[&>div]:!py-4 max-h-80 overflow-y-auto pr-2">
                                <DataList 
                                    :items="contactItems"
                                    @select-item="handleSelectContact"
                                />
                            </div>
                        </div>
                        <div v-else class="bg-white dark:bg-black rounded-lg border border-gray-200 dark:border-gray-700 p-6 text-center">
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ formLabels.headings.no_contact_persons }}</p>
                        </div>
                    </div>
                    <NotesSection 
                        :content="client.notes"
                        :default-text="formLabels.messages.no_notes_available"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>