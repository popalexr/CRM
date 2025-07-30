<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Tabs, TabsContent, TabsList } from '@/components/ui/tabs';
import { Switch } from '@/components/ui/switch';
import { Plus, Settings, Building, Receipt, ImageIcon } from 'lucide-vue-next'; 
import { ref } from 'vue';
import { BreadcrumbItem } from '@/types';
import InputError from '@/components/InputError.vue';

const page = usePage();
    
const companyInfo = ref(page.props.companyInfo as any);
const companyTypes = page.props.companyTypes as Array<{ value: string; label: string }>;

const companyForm = useForm({
    company_name: companyInfo.value.company_name,
    company_type: companyInfo.value.company_type,
    cui: companyInfo.value.cui,
    registration_number: companyInfo.value.registration_number,
    address: companyInfo.value.address,
    city: companyInfo.value.city,
    county: companyInfo.value.county,
    country: companyInfo.value.country,
    email: companyInfo.value.email,
    phone: companyInfo.value.phone,
    iban: companyInfo.value.iban,
    bank: companyInfo.value.bank,
    swift: companyInfo.value.swift,
    vat_payer: companyInfo.value.vat_payer,
});


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Settings',
        href: route('settings.index'),
    },
    {
        title: 'Company Settings',
        href: route('settings.index'),
    },
];

const saveCompanyInfo = () => {
    companyForm.post(route('settings.company.update'), {
        onSuccess: () => {
            Object.assign(companyInfo.value, companyForm.data());
        }
    });
};

const showVatRates = () => {
    router.visit(route('settings.vat'));
};
</script>

<template>
    <Head title="Company Settings" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight flex items-center gap-2">
                            <Settings class="h-6 w-6" />
                            Company Settings
                        </h1>
                    </div>
                </div>
            </div>

            <Tabs default-value="company" class="w-full">
                <TabsList class="mb-6 bg-light">
                    <Button class="flex items-center gap-2 active mr-2">
                        <Building class="h-4 w-4" />
                        General Information
                    </Button>
                    <Button class="flex items-center gap-2 bg-secondary text-secondary-foreground hover:bg-secondary/10 mr-2" @click="showVatRates">
                        <Receipt class="h-4 w-4" />
                        VAT Rates
                    </Button>
                    <Button class="flex items-center gap-2 bg-secondary text-secondary-foreground hover:bg-secondary/10" @click="router.visit(route('settings.logo'))">
                        <ImageIcon class="h-4 w-4" />
                        Logo
                    </Button>
                </TabsList>

                <TabsContent value="company">
                    <Card>
                        <CardHeader>
                            <CardTitle>General Information</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <Label for="company_name">Company Name</Label>
                                        <InputError :message="companyForm.errors.company_name" />
                                    </div>
                                    <Input 
                                        id="company_name"
                                        v-model="companyForm.company_name"
                                        placeholder="Company name"
                                    />
                                </div>

                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <Label for="company_type">Company Type</Label>
                                        <InputError :message="companyForm.errors.company_type" />
                                    </div>
                                    <Select v-model="companyForm.company_type">
                                        <SelectTrigger class="w-full">
                                            <SelectValue placeholder="Select the company type" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem 
                                                v-for="type in companyTypes" 
                                                :key="type.value" 
                                                :value="type.value"
                                            >
                                                {{ type.label }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>

                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <Label for="cui">CUI</Label>
                                        <InputError :message="companyForm.errors.cui" />
                                    </div>
                                    <Input 
                                        id="cui"
                                        v-model="companyForm.cui"
                                        placeholder="Cod Unic de Identificare (CUI)"
                                    />
                                </div>

                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <Label for="registration_number">Nr. Înregistrare</Label>
                                        <InputError :message="companyForm.errors.registration_number" />
                                    </div>
                                    <Input 
                                        id="registration_number"
                                        v-model="companyForm.registration_number"
                                        placeholder="Număr de înregistrare la Registrul Comerțului"
                                    />
                                </div>

                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <Label for="email">Email</Label>
                                        <InputError :message="companyForm.errors.email" />
                                    </div>
                                    <Input 
                                        id="email"
                                        type="email"
                                        v-model="companyForm.email"
                                        placeholder="Email address"
                                    />
                                </div>

                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <Label for="phone">Phone Number</Label>
                                        <InputError :message="companyForm.errors.phone" />
                                    </div>
                                    <Input 
                                        id="phone"
                                        v-model="companyForm.phone"
                                        placeholder="Phone number"
                                    />
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <Label for="iban">IBAN</Label>
                                        <InputError :message="companyForm.errors.iban" />
                                    </div>
                                    <Input 
                                        id="iban"
                                        v-model="companyForm.iban"
                                        placeholder="IBAN account"
                                    />
                                </div>
                                
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <Label for="bank">Bank</Label>
                                        <InputError :message="companyForm.errors.bank" />
                                    </div>
                                    <Input 
                                        id="bank"
                                        v-model="companyForm.bank"
                                        placeholder="Bank"
                                    />
                                </div>
                                
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <Label for="swift">SWIFT Code</Label>
                                        <InputError :message="companyForm.errors.swift" />
                                    </div>
                                    <Input 
                                        id="swift"
                                        v-model="companyForm.swift"
                                        placeholder="SWFT"
                                    />
                                </div>
                            </div>

                            <div class="mt-6 space-y-2">
                                <div class="flex justify-between">
                                    <Label for="address">Address</Label>
                                    <InputError :message="companyForm.errors.address" />
                                </div>
                                <Input 
                                    id="address"
                                    v-model="companyForm.address"
                                    placeholder="Full address"
                                />
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <Label for="city">City</Label>
                                        <InputError :message="companyForm.errors.city" />
                                    </div>
                                    <Input 
                                        id="city"
                                        v-model="companyForm.city"
                                        placeholder="City"
                                    />
                                </div>
                                
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <Label for="county">County</Label>
                                        <InputError :message="companyForm.errors.county" />
                                    </div>
                                    <Input 
                                        id="county"
                                        v-model="companyForm.county"
                                        placeholder="County"
                                    />
                                </div>
                                
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <Label for="country">Country</Label>
                                        <InputError :message="companyForm.errors.country" />
                                    </div>
                                    <Input 
                                        id="country"
                                        v-model="companyForm.country"
                                        placeholder="Country"
                                    />
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-2 mt-6">
                                <Switch 
                                    id="vat_payer"
                                    v-model="companyForm.vat_payer"
                                />
                                <Label for="vat_payer">VAT Payer</Label>
                            </div>
                            
                            <div class="flex justify-end mt-6">
                                <Button 
                                    @click="saveCompanyInfo"
                                    class="gap-2"
                                >
                                    <Plus class="h-4 w-4" />
                                    Save
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                </TabsContent>
            </Tabs>
        </div>
    </AppLayout>
</template>