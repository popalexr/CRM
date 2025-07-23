<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
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
import { DEFAULT_PRODUCT_FORM_DATA } from '@/constants/products';
import axios from 'axios';

const props = defineProps<ProductCreateProps>();
const page = usePage();


type ProductCreateProps = {
    formConfig?: any;
    formLabels: any;
    productTypes?: any;
};

const config = props.formConfig || {};
const productTypes = props.productTypes || {};
const formLabels = props.formLabels || {};

const breadcrumbs: BreadcrumbItem[] = config.breadcrumbs?.create || [];

const showQuantityField = ref(false);

const csrf_token = computed(() => page.props.csrf as string);

const form = useForm({ ...DEFAULT_PRODUCT_FORM_DATA });

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
    form.post(route('products.form.post'), {
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
    <Head :title="formLabels.headings?.add_product || 'Add Product'" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="sm" @click="handleBack" class="gap-2" v-if="config.form_structure?.create?.back_button_enabled !== false">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight">{{ formLabels.headings?.add_product || 'Add Product' }}</h1>
                    </div>
                </div>
            </div>

            <form @submit.prevent="handleSubmit">
                <Tabs :default-value="config.form_structure?.create?.default_tab || 'general'">
                    <TabsList class="mb-2">
                        <TabsTrigger 
                            v-for="tab in config.form_tabs || {}" 
                            :key="tab.key" 
                            :value="tab.key"
                        >
                            {{ formLabels.tabs?.[tab.key]?.label || tab.label }}
                        </TabsTrigger>
                    </TabsList>

                    <TabsContent value="general">
                        <Card>
                            <CardHeader>
                                <CardTitle>{{ formLabels.tabs?.general?.title || 'General' }}</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="name">{{ formLabels.labels?.name || 'Name' }}</Label>
                                            <InputError :message="form.errors.name" />
                                        </div>
                                        <Input 
                                            id="name" 
                                            v-model="form.name as string" 
                                            :placeholder="formLabels.placeholders?.name || 'Product name'"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="type">{{ formLabels.labels?.type || 'Type' }}</Label>
                                            <InputError :message="form.errors.type" />
                                        </div>
                                        <Select v-model="form.type as string" @update:model-value="typeChanged" class="w-full">
                                            <SelectTrigger class="w-100">
                                                <SelectValue :placeholder="formLabels.placeholders?.type || 'Select type'" />
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
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="price">{{ formLabels.labels?.price || 'Price' }}</Label>
                                            <InputError :message="form.errors.price" />
                                        </div>
                                        <Input 
                                            id="price" 
                                            v-model="form.price as string" 
                                            :placeholder="formLabels.placeholders?.price || '0.00'"
                                            type="number"
                                            step="0.01"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="currency">{{ formLabels.labels?.currency || 'Currency' }}</Label>
                                            <InputError :message="form.errors.currency" />
                                        </div>
                                        <Input 
                                            id="currency" 
                                            v-model="form.currency as string" 
                                            :placeholder="formLabels.placeholders?.currency || 'Currency (e.g. EUR, USD, RON)'"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="vat_id">{{ formLabels.labels?.vat || 'VAT' }}</Label>
                                            <InputError :message="form.errors.vat_id" />
                                        </div>
                                        <Select v-model="form.vat_id" class="w-full">
                                             <SelectTrigger class="w-60">
                                                <SelectValue :placeholder="formLabels.placeholders?.vat || 'Select VAT'" />
                                            </SelectTrigger>
                                            <SelectContent class="w-full">
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
                                            <Label for="unit">{{ formLabels.labels?.unit || 'Unit' }}</Label>
                                            <InputError :message="form.errors.unit" />
                                        </div>
                                        <Input 
                                            id="unit" 
                                            v-model="form.unit as string" 
                                            :placeholder="formLabels.placeholders?.unit || 'Unit (e.g pcs, kg, m, etc.)'"
                                        />
                                    </div>
                                    <div class="space-y-2" v-if="showQuantityField">
                                        <div class="flex items-center justify-between">
                                            <Label for="quantity">{{ formLabels.labels?.quantity || 'Quantity' }}</Label>
                                            <InputError :message="form.errors.quantity" />
                                        </div>
                                        <Input 
                                            id="quantity" 
                                            v-model="form.quantity as string" 
                                            :placeholder="formLabels.placeholders?.quantity || '0'"
                                            type="number"
                                            step="1"
                                        />
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </TabsContent>

                    <TabsContent value="image" force-mount class="data-[state=inactive]:hidden">
                        <Card>
                            <CardHeader>
                                <CardTitle>{{ formLabels.tabs?.image?.title || 'Product Image' }}</CardTitle>
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
                                <CardTitle>{{ formLabels.tabs?.description?.title || 'Product Description' }}</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <InputError :message="form.errors.description" />
                                    </div>
                                    <Textarea 
                                        id="description" 
                                        v-model="form.description as string" 
                                        :placeholder="formLabels.placeholders?.description || 'Detailed description of the product...'"
                                        :rows="config.form_layout?.description_tab?.rows?.[0]?.textarea_rows || 10"
                                    />
                                </div>
                            </CardContent>
                        </Card>
                    </TabsContent>
                </Tabs>

                <div class="flex justify-end mt-6">
                    <Button type="submit" :disabled="form.processing">
                        {{ form.processing ? (formLabels.buttons?.creating || 'Creating...') : (formLabels.buttons?.create || 'Create Product') }}
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
