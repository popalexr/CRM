<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Tabs, TabsContent, TabsList } from '@/components/ui/tabs';
import { Separator } from '@/components/ui/separator';
import { Badge } from '@/components/ui/badge';
import { Plus, Settings, Building, Receipt } from 'lucide-vue-next';
import { ref } from 'vue';
import { BreadcrumbItem, VatRate } from '@/types';
import Icon from '@/components/Icon.vue';
import EditVatDialog from '@/components/EditVatDialog.vue';
import InputError from '@/components/InputError.vue';

const page = usePage();
    
const vatRates = ref(page.props.vatRates as VatRate[]);

const vatForm = useForm({
    name: '',
    rate: '',
});

const openEditVatDialog = ref(false);
const editVatForm = useForm({
    id: '',
    name: '',
    rate: '',
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Settings',
        href: route('settings.index'),
    },
    {
        title: 'VAT Settings',
        href: route('settings.vat'),
    },
];

const removeVatRate = (rateId: string) => {
    if (confirm("Are you sure you want to delete this VAT rate? This action cannot be undone.")) {
        const deleteForm = useForm({});
        deleteForm.post(route('settings.vat.delete', { id: rateId }), {
            onSuccess: () => {
                vatRates.value = vatRates.value.filter(rate => rate.id !== rateId);
            },
        });
    }
};

const submitVatRate = () => {
    vatForm.post(route('settings.vat.update'), {
        onSuccess: (response) => {
            window.location.reload();
        }
    });
};

const showGeneralInfo = () => {
    router.visit(route('settings.index'));
};

const showVatEditModal = (rateId: string, vatName: string, vatRate: number) => {
    openEditVatDialog.value = true;

    editVatForm.id = rateId;
    editVatForm.name = vatName;
    editVatForm.rate = vatRate;
};

const onSaveEdit = (payload) => {
    const { id, name, rate } = payload;

    // Update the local vatRates array
    const index = vatRates.value.findIndex(rate => rate.id === id);
    if (index !== -1) {
        vatRates.value[index].name = name;
        vatRates.value[index].rate = rate;
    }
};
</script>

<template>
    <Head title="VAT Rates" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight flex items-center gap-2">
                            <Settings class="h-6 w-6" />
                            VAT Rates
                        </h1>
                    </div>
                </div>
            </div>

            <Tabs default-value="vat" class="w-full">
                <TabsList class="mb-6 bg-light">
                    <Button class="flex items-center gap-2 bg-secondary text-secondary-foreground hover:bg-secondary/10 mr-2" @click="showGeneralInfo">
                        <Building class="h-4 w-4" />
                        General Information
                    </Button>
                    <Button class="flex items-center gap-2 active mr-2">
                        <Receipt class="h-4 w-4" />
                        VAT Rates
                    </Button>
                </TabsList>

                <!-- VAT Rates Tab -->
                <TabsContent value="vat">
                    <Card>
                        <CardHeader>
                            <CardTitle>
                                VAT Rates
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-6">
                            <div class="space-y-4" id="add-vat-form">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <Label for="vat_name">VAT Name</Label>
                                            <InputError :message="vatForm.errors.name" />
                                        </div>
                                        <Input 
                                            id="vat_name"
                                            v-model="vatForm.name"
                                            placeholder="Name of the VAT rate"
                                        />
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <Label for="vat_rate">VAT Rate</Label>
                                            <InputError :message="vatForm.errors.rate" />
                                        </div>
                                        <Input 
                                            id="vat_rate"
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            max="100"
                                            v-model="vatForm.rate"
                                            placeholder="0.00"
                                        />
                                    </div>
                                    
                                    <div class="flex items-end">
                                        <Button 
                                            @click="submitVatRate"
                                            class="w-auto gap-2"
                                        >
                                            <Plus class="h-4 w-4" />
                                            Add
                                        </Button>
                                    </div>
                                </div>
                            </div>

                            <Separator />

                            <div v-if="vatRates.length > 0" class="space-y-3">
                                <h4 class="font-medium">Current VAT rates</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3" v-if="vatRates.length > 0">
                                    <div 
                                        v-for="rate in vatRates" 
                                        :key="rate.id"
                                        class="flex items-center justify-between p-3 border rounded-lg"
                                    >
                                        <div class="flex items-center justify-between w-full">
                                            <div class="font-medium">{{ rate.name }}</div>
                                            <Badge variant="secondary">{{ rate.rate }}%</Badge>
                                        </div>

                                        <div class="flex ms-4">
                                            <Button 
                                                variant="ghost" 
                                                size="sm"
                                                class="text-blue-600 hover:text-blue-800"
                                                @click="showVatEditModal(rate.id, rate.name, rate.rate)"
                                            >
                                                <Icon name="edit" class="h-4 w-4" />
                                            </Button>
                                            <Button 
                                                variant="ghost" 
                                                size="sm"
                                                @click="removeVatRate(rate.id)"
                                                class="text-red-600 hover:text-red-800"
                                            >
                                                <Icon name="trash" class="h-4 w-4" />
                                            </Button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-muted-foreground">
                                No VAT rates have been added yet.
                            </div>
                        </CardContent>
                    </Card>
                </TabsContent>
            </Tabs>
        </div>

        <EditVatDialog
            :model-value="openEditVatDialog"
            :vat-name="editVatForm.name"
            :vat-rate="editVatForm.rate"
            :vat-id="editVatForm.id"
            @save="onSaveEdit"
            title="Edit VAT Rate"
        />
    </AppLayout>
</template>