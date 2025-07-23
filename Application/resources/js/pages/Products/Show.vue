<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { ArrowLeftIcon } from 'lucide-vue-next';
import { computed } from 'vue';

import ProfileHeader from '@/components/ProfileHeader.vue';
import NotesSection from '@/components/NotesSection.vue';
import SearchInput from '@/components/SearchInput.vue';

interface ProductDetails {
    id: number;
    name: string;
    image?: string;
    type: 'product' | 'service';
    price: number;
    currency: string;
    quantity?: number;
    unit: string;
    description?: string;
    created_at: string;
}


interface Props {
    product: ProductDetails;
    formLabels: any;
}

const props = defineProps<Props>();
const formLabels = props.formLabels || {};

const entityData = computed(() => ({
    id: props.product.id,
    name: props.product.name,
    email: (formLabels.types && formLabels.types[props.product.type]) ? formLabels.types[props.product.type] : props.product.type,
    phone: undefined, 
    avatar: props.product.image
}));

const handleEditProduct = (productId: number) => {
    router.visit(route('products.form', { id: productId }));
};

const handleBack = () => {
    router.visit(route('products.index'));
};
</script>

<template>
    <Head :title="`${formLabels.headings?.product_details || 'Product Details'}: ${product.name}`" />

    <AppLayout>
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="sm" @click="handleBack" class="p-2">
                        <ArrowLeftIcon class="w-4 h-4" />
                    </Button>
                    <h1 class="text-xl font-semibold dark:text-white">{{ formLabels.headings?.product_details || 'Product Details' }}</h1>
                </div>
                <Button @click="handleEditProduct(product.id)">{{ formLabels.buttons?.edit || 'Edit' }}</Button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <ProfileHeader 
                        :entity="entityData"
                    />

                    <div class="bg-white dark:bg-black rounded-xl border border-gray-200 dark:border-gray-700 p-8 shadow-sm">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ formLabels.headings?.product_details || 'Product Details' }}</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-4">
                                <div class="flex items-center space-x-2 pb-3 border-b border-gray-100 dark:border-gray-800">
                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">{{ formLabels.labels?.commercial_details || 'Commercial Details' }}</h4>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex flex-col space-y-1">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ formLabels.labels?.price || 'Price' }}</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ product.price }} {{ product.currency }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="flex items-center space-x-2 pb-3 border-b border-gray-100 dark:border-gray-800">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">{{ formLabels.labels?.logistics_details || 'Logistics Details' }}</h4>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex flex-col space-y-1">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ formLabels.labels?.unit || 'Unit' }}</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ product.unit }}</span>
                                    </div>
                                    <div v-if="product.type === 'product'" class="flex flex-col space-y-1">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ formLabels.labels?.quantity || 'Quantity' }}</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ product.quantity ?? 0 }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <NotesSection 
                        :content="product.description"
                        :default-text="formLabels.messages?.no_notes_available || 'No notes available.'"
                        :title="formLabels.labels?.description || 'Description'"
                    />
                </div>

                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-4 dark:text-white">{{ formLabels.labels?.associated_invoices || 'Associated Invoices' }}</h3>
                        
                        <div class="mb-4">
                            <SearchInput 
                                :placeholder="formLabels.placeholders?.search_invoices || 'Search invoices...'"
                            />
                        </div>

                        <div class="bg-white dark:bg-black rounded-lg border border-gray-200 dark:border-gray-700 p-6 text-center">
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ formLabels.messages?.no_invoices_found || 'No invoices found for this product.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>