<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Product, type BreadcrumbItem } from '@/types';
import { Head, useForm, router, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue, SelectGroup} from '@/components/ui/select';
import { ArrowLeft } from 'lucide-vue-next';
import { ref, computed, onBeforeMount } from 'vue';
import InputError from '@/components/InputError.vue';
import Dropzone from '@/components/Dropzone.vue';
import axios from 'axios';

const props = defineProps<Props>();
const page = usePage();

type Props = {
    product: Product;
    formConfig?: any;
    formLabels?: any;
    productTypes?: any;
};

const config = props.formConfig || {};
const productTypes = props.productTypes || {};

const breadcrumbs: BreadcrumbItem[] = config.breadcrumbs?.edit || [];

const showQuantityField = ref(props.product.type === 'product');

const csrf_token = computed(() => page.props.csrf as string);

const form = useForm({
    name: props.product.name,
    type: props.product.type,
    price: props.product.price,
    unit: props.product.unit,
    currency: props.product.currency,
    quantity: props.product.quantity,
    vat_id: props.product.vat_id,
    description: props.product.description,
    image_file_id: '',
})

const vats = ref<Array<{ id: number; name: string; rate: number }>>([]);

const handleBack = () => {
    router.visit(route('products.index'));
};

const typeChanged = (value: any) => {
    showQuantityField.value = value === 'product';
    if (value === 'service') {
        form.quantity = '';
    }
};

const handleSubmit = () => {
    form.post(route('products.form.post', {id: props.product.id}), {
        onSuccess: () => {
            router.visit(route('products.index'));
        },
        onError: (errors) => {
            console.error('Form submission errors:', errors);
        },
    });
};

const fileUploadSuccess = (response: any) => {
    const responseData = response.response || response;
    
    if (responseData && responseData.file_id) {
        form.image_file_id = responseData.file_id;
    }
}

const fileUploadError = (payload: { errorMessage: any }) => {
    console.error('File upload error:', payload.errorMessage);
};

const uploadedFileRemoved = () => {
    form.image_file_id = '';
}

onBeforeMount(() => {
    axios.get(route('api.getVAT')).then(response => {
        vats.value = response.data.vats || [];
    }).catch(error => {
        console.error('Error fetching VATs:', error);
    });
});
</script>

<template>
    <Head :title="config.form_structure?.edit?.page_title || 'Edit Product'" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="sm" @click="handleBack" class="gap-2" v-if="config.form_structure?.edit?.back_button_enabled !== false">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight">{{ config.form_structure?.edit?.form_title || 'Add Product' }}</h1>
                    </div>
                </div>
            </div>

            <form @submit.prevent="handleSubmit">
                <Tabs :default-value="config.form_structure?.edit?.default_tab || 'general'">
                    <TabsList class="mb-2">
                        <TabsTrigger 
                            v-for="tab in config.form_tabs || {}" 
                            :key="tab.key" 
                            :value="tab.key"
                        >
                            {{ tab.label }}
                        </TabsTrigger>
                    </TabsList>

                    <TabsContent value="general">
                        <Card>
                            <CardHeader>
                                <CardTitle>{{ config.form_tabs?.general?.title || 'General' }}</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="name">{{ config.form_labels?.labels?.name || 'Name' }}</Label>
                                            <InputError :message="form.errors.name" />
                                        </div>
                                        <Input 
                                            id="name" 
                                            v-model="form.name as string" 
                                            :placeholder="config.form_labels?.placeholders?.name || 'Product name'"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="type">{{ config.form_labels?.labels?.type || 'Type' }}</Label>
                                            <InputError :message="form.errors.type" />
                                        </div>
                                        <Select v-model="form.type as string" @update:model-value="typeChanged" class="w-full">
                                            <SelectTrigger class="w-100">
                                                <SelectValue :placeholder="config.form_labels?.placeholders?.type || 'Select type'" />
                                            </SelectTrigger>
                                            <SelectContent class="min-w-0 w-full">
                                                <SelectGroup>
                                                    <SelectItem 
                                                        v-for="(label, value) in config.product_types || productTypes" 
                                                        :key="value" 
                                                        :value="value"
                                                    >
                                                        {{ label }}
                                                    </SelectItem>
                                                </SelectGroup>
                                            </SelectContent>
                                        </Select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-6">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="price">{{ config.form_labels?.labels?.price || 'Price' }}</Label>
                                            <InputError :message="form.errors.price" />
                                        </div>
                                        <Input 
                                            id="price" 
                                            v-model="form.price as string" 
                                            :placeholder="config.form_labels?.placeholders?.price || '0.00'"
                                            type="number"
                                            step="0.01"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="currency">{{ config.form_labels?.labels?.currency || 'Currency' }}</Label>
                                            <InputError :message="form.errors.currency" />
                                        </div>
                                        <Input 
                                            id="currency" 
                                            v-model="form.currency as string" 
                                            :placeholder="config.form_labels?.placeholders?.currency || 'Currency (e.g. EUR, USD, RON)'"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="vat_id">{{ config.form_labels?.labels?.vat || 'VAT' }}</Label>
                                            <InputError :message="form.errors.vat_id" />
                                        </div>
                                        <Select v-model="form.vat_id" class="w-60">
                                            <SelectTrigger class="w-60">
                                                <SelectValue :placeholder="config.form_labels?.placeholders?.vat || 'Select VAT'" />
                                            </SelectTrigger>
                                            <SelectContent class="w-60">
                                                <SelectGroup>
                                                    <SelectItem 
                                                        v-for="vat in vats || []" 
                                                        :key="vat.id" 
                                                        :value="vat.id"
                                                    >
                                                        {{ vat.name }} ({{ vat.rate }}%)
                                                    </SelectItem>
                                                </SelectGroup>
                                            </SelectContent>
                                        </Select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="unit">{{ config.form_labels?.labels?.unit || 'Unit' }}</Label>
                                            <InputError :message="form.errors.unit" />
                                        </div>
                                        <Input 
                                            id="unit" 
                                            v-model="form.unit as string" 
                                            :placeholder="config.form_labels?.placeholders?.unit || 'Unit (e.g pcs, kg, m, etc.)'"
                                        />
                                    </div>
                                    <div class="space-y-2" v-if="showQuantityField">
                                        <div class="flex items-center justify-between">
                                            <Label for="quantity">{{ config.form_labels?.labels?.quantity || 'Quantity' }}</Label>
                                            <InputError :message="form.errors.quantity" />
                                        </div>
                                        <Input 
                                            id="quantity" 
                                            v-model="form.quantity as string" 
                                            :placeholder="config.form_labels?.placeholders?.quantity || '0'"
                                            type="number"
                                            step="1"
                                            class="w-103"
                                        />
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </TabsContent>

                    <TabsContent value="image" force-mount class="data-[state=inactive]:hidden">
                        <Card>
                            <CardHeader>
                                <CardTitle>{{ config.form_tabs?.image?.title || 'Product Image' }}</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <InputError :message="form.errors.image_file_id" />
                                <Dropzone
                                    :url="route('upload.image')"
                                    @success="fileUploadSuccess"
                                    @error="fileUploadError"
                                    @removed="uploadedFileRemoved"
                                    :headers="{'X-CSRF-TOKEN': csrf_token }"
                                    :accepted-files="config.file_upload?.accepted_files || 'image/jpeg,image/png,image/jpg,image/bmp'"
                                />
                            </CardContent>
                        </Card>
                    </TabsContent>

                    <TabsContent value="description">
                        <Card>
                            <CardHeader>
                                <CardTitle>{{ config.form_tabs?.description?.title || 'Product Description' }}</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <InputError :message="form.errors.description" />
                                    </div>
                                    <Textarea 
                                        id="description" 
                                        v-model="form.description as string" 
                                        :placeholder="config.form_labels?.placeholders?.description || 'Detailed description of the product...'"
                                        :rows="config.form_layout?.description_tab?.rows?.[0]?.textarea_rows || 10"
                                    />
                                </div>
                            </CardContent>
                        </Card>
                    </TabsContent>
                </Tabs>

                <div class="flex justify-end mt-6">
                    <Button type="submit" :disabled="form.processing">
                        {{ form.processing ? (config.form_labels?.buttons?.editing || 'Edit...') : (config.form_labels?.buttons?.edit || 'Edit Product') }}
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
