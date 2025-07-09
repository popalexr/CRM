<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Client, type BreadcrumbItem } from '@/types';
import { Head, useForm, router, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Textarea } from '@/components/ui/textarea';
import { 
    Select, 
    SelectContent, 
    SelectItem, 
    SelectTrigger, 
    SelectValue,
    SelectGroup
} from '@/components/ui/select';
import ScrollArea from '@/components/ui/scroll-area/ScrollArea.vue';

import { ArrowLeft, Plus } from 'lucide-vue-next';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import ContactsFormManager from '@/components/ContactsFormManager.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Clients',
        href: '/clients',
    },
    {
        title: 'Edit Client',
        href: '/clients/form',
    },
];

const page = usePage();
const client = page.props.client as Client;
const clientContacts = page.props.clientContacts as Array<{name: string, position: string, email: string, phone: string}>;

const isBusinessClient = ref(client.client_type === 'business');

const form = useForm({
    id: client.id,
    client_name: client.client_name,
    client_type: client.client_type,
    email: client.client_email,
    phone: client.client_phone,
    cui: client.cui,
    registration_number: client.registration_number,
    address: client.address,
    city: client.city,
    county: client.county,
    country: client.country,
    iban: client.iban,
    swift: client.swift,
    bank: client.bank_name,
    currency: client.currency || '',
    notes: client.notes || '',
    contactPersons: clientContacts,
});

const handleBack = () => {
    router.visit(route('clients.index'));
};

const clientTypeChanged = (value: any) => {
    const type = typeof value === 'object' && value !== null && 'value' in value ? value.value : value;
    isBusinessClient.value = type === 'business';
};

const handleSubmit = () => {
    form.post(route('clients.form.post', { id: client.id }), {
        onSuccess: () => {
            router.visit(route('clients.index'));
        },
        onError: (errors) => {
            console.error('Form submission errors:', errors);
        }
    });
};

</script>

<template>
    <Head title="Edit Client" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="sm" @click="handleBack" class="gap-2">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight">Edit Client</h1>
                    </div>
                </div>
            </div>

            <form @submit.prevent="handleSubmit">
                <Tabs default-value="general">
                    <!-- General Information Tab Content -->
                    <TabsList class="mb-2">
                        <TabsTrigger value="general">General</TabsTrigger>
                        <TabsTrigger value="contacts" v-show="isBusinessClient === true">Contacts</TabsTrigger>
                        <TabsTrigger value="notes">Notes</TabsTrigger>
                    </TabsList>

                    <TabsContent value="general">
                        <Card>
                            <CardHeader>
                                <CardTitle>General information</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="name">Client Name</Label>
                                            <InputError :message="form.errors.client_name" />
                                        </div>
                                        <Input 
                                            id="name" 
                                            v-model="form.client_name" 
                                            placeholder="Client name"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="client_type">Client Type</Label>
                                            <InputError :message="form.errors.client_type" />
                                        </div>
                                        <Select v-model="form.client_type" @update:model-value="clientTypeChanged" class="w-full">
                                            <SelectTrigger class="w-100">
                                                <SelectValue placeholder="Select client type" />
                                            </SelectTrigger>
                                            <SelectContent class="min-w-0 w-full">
                                                <SelectGroup>
                                                    <SelectItem value="individual">Individual</SelectItem>
                                                    <SelectItem value="business">Company</SelectItem>
                                                </SelectGroup>
                                            </SelectContent>
                                        </Select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="email">Email</Label>
                                            <InputError :message="form.errors.email" />
                                        </div>
                                        <Input 
                                            id="email" 
                                            v-model="form.email" 
                                            type="email" 
                                            placeholder="Email address"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="phone">Phone</Label>
                                            <InputError :message="form.errors.phone" />
                                        </div>
                                        <Input 
                                            id="phone" 
                                            v-model="form.phone" 
                                            type="tel" 
                                            placeholder="Phone number"
                                        />
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="cui">CUI</Label>
                                            <InputError :message="form.errors.cui" />
                                        </div>
                                        <Input 
                                            id="cui" 
                                            v-model="form.cui" 
                                            placeholder="CUI (if applicable)"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="registration_number">Registration Number</Label>
                                            <InputError :message="form.errors.registration_number" />
                                        </div>
                                        <Input 
                                            id="registration_number" 
                                            v-model="form.registration_number" 
                                            placeholder="Registration Number (if applicable)"
                                        />
                                    </div>
                                </div>
                                <!-- Add this section for banking information including currency -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="iban">IBAN</Label>
                                            <InputError :message="form.errors.iban" />
                                        </div>
                                        <Input
                                            id="iban" 
                                            v-model="form.iban" 
                                            placeholder="IBAN (if applicable)"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="swift">SWIFT/BIC</Label>
                                            <InputError :message="form.errors.swift" />
                                        </div>
                                        <Input
                                            id="swift" 
                                            v-model="form.swift" 
                                            placeholder="SWIFT/BIC (if applicable)"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="bank">Bank</Label>
                                            <InputError :message="form.errors.bank" />
                                        </div>
                                        <Input
                                            id="bank" 
                                            v-model="form.bank" 
                                            placeholder="Bank name (if applicable)"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="currency">Currency</Label>
                                            <InputError :message="form.errors.currency" />
                                        </div>
                                        <Input
                                            id="currency"
                                            v-model="form.currency"
                                            placeholder="Currency (e.g. EUR, USD, RON)"
                                        />
                                    </div>
                                </div>
                                <div class="mt-6 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <Label for="address">Address</Label>
                                        <InputError :message="form.errors.address" />
                                    </div>
                                    <Input
                                        id="address" 
                                        v-model="form.address" 
                                        placeholder="Client address"
                                    />
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="city">City</Label>
                                            <InputError :message="form.errors.city" />
                                        </div>
                                        <Input 
                                            id="city" 
                                            v-model="form.city" 
                                            placeholder="City"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="county">County</Label>
                                            <InputError :message="form.errors.county" />
                                        </div>
                                        <Input 
                                            id="county" 
                                            v-model="form.county" 
                                            placeholder="County"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="country">Country</Label>
                                            <InputError :message="form.errors.country" />
                                        </div>
                                        <Input 
                                            id="country" 
                                            v-model="form.country" 
                                            placeholder="Country"
                                        />
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </TabsContent>

                    <TabsContent value="contacts">
                        <!-- Contacts Tab Content -->
                        <Card>
                            <CardHeader>
                                <CardTitle>Client Contacts</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <ScrollArea class="h-[60vh] w-full">
                                    <div class="p-2">
                                        <ContactsFormManager v-model="form.contactPersons" />
                                        <p class="text-sm text-muted-foreground mt-2">
                                            Note: Changes here will be saved when you save the client form.
                                        </p>
                                    </div>
                                </ScrollArea>
                            </CardContent>
                        </Card>
                    </TabsContent>

                    <TabsContent value="notes">
                        <!-- Notes Tab Content -->
                        <Card>
                            <CardHeader>
                                <CardTitle>Client Notes</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <InputError :message="form.errors.notes" />
                                <Textarea 
                                    v-model="form.notes" 
                                    placeholder="Add notes about the client"
                                    class="h-32"
                                />
                            </CardContent>
                        </Card>
                    </TabsContent>
                </Tabs>

                <div class="mt-6 flex justify-end">
                    <Button type="submit" class="gap-2">
                        <Plus class="h-4 w-4" />
                        Save Client
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>