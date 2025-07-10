<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Separator } from '@/components/ui/separator';
import { Badge } from '@/components/ui/badge';
import { Switch } from '@/components/ui/switch';
import { Plus, Settings, Building, Receipt } from 'lucide-vue-next';
import { useSettings } from '@/composables/useSettings';

const {
    labels, breadcrumbs, companyForm, vatForm, vatRates, companyTypes, saveCompanyInfo, addVatRate, removeVatRate, } = useSettings();
</script>

<template>
    <Head :title="labels.headings.page_title" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight flex items-center gap-2">
                            <Settings class="h-6 w-6" />
                            {{ labels.headings.page_title }}
                        </h1>
                    </div>
                </div>
            </div>

            <Tabs default-value="company" class="w-full">
                <TabsList class="mb-6">
                    <TabsTrigger value="company" class="flex items-center gap-2">
                        <Building class="h-4 w-4" />
                        Informații Companie
                    </TabsTrigger>
                    <TabsTrigger value="vat" class="flex items-center gap-2">
                        <Receipt class="h-4 w-4" />
                        Cote TVA
                    </TabsTrigger>
                </TabsList>

                <!-- Company Information Tab -->
                <TabsContent value="company">
                    <Card>
                        <CardHeader>
                            <CardTitle>{{ labels.headings.company_info }}</CardTitle>
                            <CardDescription>
                                {{ labels.headings.company_info_desc }}
                            </CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <Label for="company_name">{{ labels.labels.company_name }}</Label>
                                    <Input 
                                        id="company_name"
                                        v-model="companyForm.company_name"
                                        :placeholder="labels.placeholders.company_name"
                                    />
                                </div>
                                
                                <div class="space-y-2">
                                    <Label for="company_type">{{ labels.labels.company_type }}</Label>
                                    <Select v-model="companyForm.company_type">
                                        <SelectTrigger>
                                            <SelectValue :placeholder="labels.placeholders.company_type" />
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
                                    <Label for="email">{{ labels.labels.email }}</Label>
                                    <Input 
                                        id="email"
                                        type="email"
                                        v-model="companyForm.email"
                                        :placeholder="labels.placeholders.email"
                                    />
                                </div>
                                
                                <div class="space-y-2">
                                    <Label for="phone">{{ labels.labels.phone }}</Label>
                                    <Input 
                                        id="phone"
                                        v-model="companyForm.phone"
                                        :placeholder="labels.placeholders.phone"
                                    />
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                                <div class="space-y-2">
                                    <Label for="iban">{{ labels.labels.iban }}</Label>
                                    <Input 
                                        id="iban"
                                        v-model="companyForm.iban"
                                        :placeholder="labels.placeholders.iban"
                                    />
                                </div>
                                
                                <div class="space-y-2">
                                    <Label for="bank">{{ labels.labels.bank }}</Label>
                                    <Input 
                                        id="bank"
                                        v-model="companyForm.bank"
                                        :placeholder="labels.placeholders.bank"
                                    />
                                </div>
                                
                                <div class="space-y-2">
                                    <Label for="swift">{{ labels.labels.swift }}</Label>
                                    <Input 
                                        id="swift"
                                        v-model="companyForm.swift"
                                        :placeholder="labels.placeholders.swift"
                                    />
                                </div>
                            </div>

                            <div class="mt-6 space-y-2">
                                <Label for="address">{{ labels.labels.address }}</Label>
                                <Input 
                                    id="address"
                                    v-model="companyForm.address"
                                    :placeholder="labels.placeholders.address"
                                />
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                <div class="space-y-2">
                                    <Label for="city">{{ labels.labels.city }}</Label>
                                    <Input 
                                        id="city"
                                        v-model="companyForm.city"
                                        :placeholder="labels.placeholders.city"
                                    />
                                </div>
                                
                                <div class="space-y-2">
                                    <Label for="county">{{ labels.labels.county }}</Label>
                                    <Input 
                                        id="county"
                                        v-model="companyForm.county"
                                        :placeholder="labels.placeholders.county"
                                    />
                                </div>
                                
                                <div class="space-y-2">
                                    <Label for="country">{{ labels.labels.country }}</Label>
                                    <Input 
                                        id="country"
                                        v-model="companyForm.country"
                                        :placeholder="labels.placeholders.country"
                                    />
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-2 mt-6">
                                <Switch 
                                    id="vat_payer"
                                    v-model="companyForm.vat_payer"
                                />
                                <Label for="vat_payer">{{ labels.labels.vat_payer }}</Label>
                            </div>
                            
                            <div class="flex justify-end mt-6">
                                <Button 
                                    @click="saveCompanyInfo"
                                    :disabled="companyForm.processing"
                                    class="gap-2"
                                >
                                    <Plus class="h-4 w-4" />
                                    {{ labels.buttons.save_company }}
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                </TabsContent>

                <!-- VAT Rates Tab -->
                <TabsContent value="vat">
                    <Card>
                        <CardHeader>
                            <CardTitle>{{ labels.headings.vat_rates }}</CardTitle>
                            <CardDescription>
                                {{ labels.headings.vat_rates_desc }}
                            </CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-6">
                            <div v-if="vatRates.length > 0" class="space-y-3">
                                <h4 class="font-medium">{{ labels.headings.vat_configured }}</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    <div 
                                        v-for="rate in vatRates" 
                                        :key="rate.id"
                                        class="flex items-center justify-between p-3 border rounded-lg"
                                    >
                                        <div>
                                            <div class="font-medium">{{ rate.name }}</div>
                                            <Badge variant="secondary">{{ rate.rate }}%</Badge>
                                        </div>
                                        <Button 
                                            variant="ghost" 
                                            size="sm"
                                            @click="removeVatRate(rate.id)"
                                            class="text-red-600 hover:text-red-800"
                                        >
                                            {{ labels.buttons.delete_vat }}
                                        </Button>
                                    </div>
                                </div>
                            </div>

                            <Separator />

                            <div class="space-y-4">
                                <h4 class="font-medium">{{ labels.headings.add_new_vat }}</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="space-y-2">
                                        <Label for="vat_name">{{ labels.labels.vat_name }}</Label>
                                        <Input 
                                            id="vat_name"
                                            v-model="vatForm.name"
                                            :placeholder="labels.placeholders.vat_name"
                                        />
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <Label for="vat_rate">{{ labels.labels.vat_rate }}</Label>
                                        <Input 
                                            id="vat_rate"
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            max="100"
                                            v-model="vatForm.rate"
                                            :placeholder="labels.placeholders.vat_rate"
                                        />
                                    </div>
                                    
                                    <div class="flex items-end">
                                        <Button 
                                            @click="addVatRate"
                                            :disabled="vatForm.processing || !vatForm.name || vatForm.rate < 0"
                                            class="w-full gap-2"
                                        >
                                            <Plus class="h-4 w-4" />
                                            {{ labels.buttons.add_vat }}
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </TabsContent>
            </Tabs>
        </div>
    </AppLayout>
</template>