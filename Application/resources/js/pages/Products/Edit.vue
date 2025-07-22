<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue, SelectGroup } from '@/components/ui/select';
import { ArrowLeft } from 'lucide-vue-next';
import { computed } from 'vue';
import InputError from '@/components/InputError.vue';
import Dropzone from '@/components/Dropzone.vue';
import { useProductForm } from '@/composables/useProductForm';
import type { ProductFormData } from '@/types/products';

const page = usePage();
const formLabels = page.props.formLabels;
const product = page.props.product as any;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Products',
        href: '/products',
    },
    {
        title: 'Edit Product',
        href: '/products/form',
    },
];

const csrf_token = computed(() => usePage().props.csrf as string);

const productForm = useProductForm(product, 'edit');
const { form: rawForm, showQuantityField, handleTypeChange, getLabel, getPlaceholder, getButtonText } = productForm;

const form = rawForm as any as ProductFormData & {
    errors: Record<string, string>;
    processing: boolean;
    post: (url: string, options?: any) => void;
};

const productTypes = productForm.getProductTypes();

const handleBack = () => {
    router.visit(route('products.index'));
};

const typeChanged = (value: any) => {
    handleTypeChange(value);
};

const handleSubmit = () => {
    form.post(route('products.form.post', { id: product.id }), {
        onSuccess: () => {
            router.visit(route('products.index'));
        },
        onError: (errors: any) => {
            console.error('Form submission errors:', errors);
        }
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
</script>

<template>
    <Head title="Edit Product" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="sm" @click="handleBack" class="gap-2">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight">Edit Product</h1>
                    </div>
                </div>
            </div>

            <form @submit.prevent="handleSubmit">
                <Tabs default-value="general">
                    <TabsList class="mb-2">
                        <TabsTrigger value="general">General Information</TabsTrigger>
                        <TabsTrigger value="image">Image</TabsTrigger>
                        <TabsTrigger value="description">Description</TabsTrigger>
                    </TabsList>

                    <TabsContent value="general">
                        <Card>
                            <CardHeader>
                                <CardTitle>General Information</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="name">{{ getLabel('name') }}</Label>
                                            <InputError :message="form.errors.name" />
                                        </div>
                                        <Input 
                                            id="name" 
                                            v-model="form.name as string" 
                                            :placeholder="getPlaceholder('name')"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="type">{{ getLabel('type') }}</Label>
                                            <InputError :message="form.errors.type" />
                                        </div>
                                        <Select v-model="form.type as string" @update:model-value="typeChanged" class="w-full">
                                            <SelectTrigger class="w-100">
                                                <SelectValue :placeholder="getPlaceholder('type')" />
                                            </SelectTrigger>
                                            <SelectContent class="min-w-0 w-full">
                                                <SelectGroup>
                                                    <SelectItem 
                                                        v-for="(label, value) in productTypes" 
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
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="price">{{ getLabel('price') }}</Label>
                                            <InputError :message="form.errors.price" />
                                        </div>
                                        <Input 
                                            id="price" 
                                            v-model="form.price as string | number" 
                                            :placeholder="getPlaceholder('price')"
                                            type="number"
                                            step="0.01"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="currency">{{ getLabel('currency') }}</Label>
                                            <InputError :message="form.errors.currency" />
                                        </div>
                                        <Input 
                                            id="currency" 
                                            v-model="form.currency as string" 
                                            :placeholder="getPlaceholder('currency')"
                                        />
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="unit">{{ getLabel('unit') }}</Label>
                                            <InputError :message="form.errors.unit" />
                                        </div>
                                        <Input 
                                            id="unit" 
                                            v-model="form.unit as string" 
                                            :placeholder="getPlaceholder('unit')"
                                        />
                                    </div>
                                    <div class="space-y-2" v-if="showQuantityField">
                                        <div class="flex items-center justify-between">
                                            <Label for="quantity">{{ getLabel('quantity') }}</Label>
                                            <InputError :message="form.errors.quantity" />
                                        </div>
                                        <Input 
                                            id="quantity" 
                                            v-model="form.quantity as string | number" 
                                            :placeholder="getPlaceholder('quantity')"
                                            type="number"
                                            step="1"
                                        />
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </TabsContent>

                    <TabsContent value="image">
                        <Card>
                            <CardHeader>
                                <CardTitle>Product Image</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <InputError :message="form.errors.image_file_id" />
                                <Dropzone
                                    :url="route('upload.image')"
                                    @success="fileUploadSuccess"
                                    @error="fileUploadError"
                                    @removed="uploadedFileRemoved"
                                    :headers="{'X-CSRF-TOKEN': csrf_token }"
                                    accepted-files="image/jpeg,image/png,image/jpg,image/bmp"
                                />
                            </CardContent>
                        </Card>
                    </TabsContent>

                    <TabsContent value="description">
                        <Card>
                            <CardHeader>
                                <CardTitle>Description</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <Label for="description">{{ getLabel('description') }}</Label>
                                        <InputError :message="form.errors.description" />
                                    </div>
                                    <Textarea 
                                        id="description" 
                                        v-model="form.description as string" 
                                        :placeholder="getPlaceholder('description')"
                                        rows="10"
                                    />
                                </div>
                            </CardContent>
                        </Card>
                    </TabsContent>
                </Tabs>

                <div class="flex justify-end mt-6">
                    <Button type="submit" :disabled="form.processing">
                        {{ form.processing ? getButtonText('updating') : getButtonText('update') }}
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
