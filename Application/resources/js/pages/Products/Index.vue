<script setup lang="ts">
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle, CardFooter } from '@/components/ui/card';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Trash2 } from 'lucide-vue-next';
import ConfirmDialog from '@/components/ConfirmDialog.vue';

interface Product {
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
    products: Product[];
    formLabels: any;
}

const props = defineProps<Props>();
const formLabels = props.formLabels || {};

const showDeleteDialog = ref(false);
const productToDelete = ref<Product | null>(null);

const handleDeleteRequest = (product: Product) => {
    productToDelete.value = product;
    showDeleteDialog.value = true;
};

const handleDeleteConfirm = () => {
    if (!productToDelete.value) return;

   
    router.post(route('products.delete'), { id: productToDelete.value.id }, {
        preserveScroll: true,
        onSuccess: () => {
            handleDeleteCancel();
        },
        onError: (errors) => {
            handleDeleteCancel();
        }
    });
};

const handleDeleteCancel = () => {
    productToDelete.value = null;
    showDeleteDialog.value = false;
};

const handleShowProduct = (productId: number) => {
    router.visit(route('products.details', { id: productId }));
};

const handleCreateProduct = () => {
    router.visit(route('products.form'));
};

const getInitials = (name: string) => {
    return name.charAt(0).toUpperCase();
};

const formatPrice = (price: number, currency: string) => {
    return `${price} ${currency}`;
};
</script>

<template>
    <Head :title="formLabels.headings?.products || 'Products'" />

    <AppLayout>
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold">{{ formLabels.headings?.products || 'Products' }}</h1>
                <Button @click="handleCreateProduct">{{ formLabels.buttons?.add_new || formLabels.buttons?.create || 'Add Product' }}</Button>
            </div>

            <div v-if="products.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <Card 
                    v-for="product in products" 
                    :key="product.id" 
                    class="flex flex-col cursor-pointer hover:shadow-lg transition-shadow"
                    @click="handleShowProduct(product.id)"
                >
                    <div class="flex-grow">
                        <CardHeader class="pb-3">
                            <div class="flex items-center space-x-3">
                                <Avatar class="w-12 h-12 rounded-lg">
                                    <AvatarImage v-if="product.image" :src="product.image" :alt="product.name" />
                                    <AvatarFallback class="text-lg font-medium">{{ getInitials(product.name) }}</AvatarFallback>
                                </Avatar>
                                <div class="flex-1">
                                    <CardTitle class="text-lg">{{ product.name }}</CardTitle>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <Badge :variant="product.type === 'product' ? 'default' : 'secondary'">
                                            {{ formLabels.types?.[product.type] || (product.type.charAt(0).toUpperCase() + product.type.slice(1)) }}
                                        </Badge>
                                    </div>
                                </div>
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-2 text-sm">
                                <p><strong>{{ formLabels.labels?.price || 'Price' }}:</strong> {{ formatPrice(product.price, product.currency) }}</p>
                                <p><strong>{{ formLabels.labels?.unit || 'Unit' }}:</strong> {{ product.unit }}</p>
                                <p v-if="product.type === 'product' && product.quantity !== undefined">
                                    <strong>{{ formLabels.labels?.quantity || 'Quantity' }}:</strong> {{ product.quantity }}
                                </p>
                                <p v-if="product.description" class="text-muted-foreground line-clamp-2">
                                    {{ product.description }}
                                </p>
                            </div>
                        </CardContent>
                    </div>

                    <CardFooter class="pt-4 flex justify-end border-t mt-auto">
                        <Button 
                            variant="destructive" 
                            size="icon"
                            @click.stop="handleDeleteRequest(product)"
                        >
                            <Trash2 class="w-4 h-4" />
                        </Button>
                    </CardFooter>
                </Card>
            </div>

            <div v-else class="text-center py-12">
                <div class="max-w-md mx-auto">
                    <h3 class="text-lg font-semibold mb-2">{{ formLabels.headings?.no_products_found || 'No products found' }}</h3>
                    <p class="text-muted-foreground mb-4">
                        {{ formLabels.headings?.get_started || 'Start by adding your first product or service.' }}
                    </p>
                    <Button @click="handleCreateProduct">{{ formLabels.buttons?.add_new || formLabels.buttons?.create || 'Add Product' }}</Button>
                </div>
            </div>
        </div>

        <ConfirmDialog
            v-model:open="showDeleteDialog"
            :title="formLabels.buttons?.delete || 'Delete'"
            :description="`${formLabels.messages?.product_deleted || 'Are you sure you want to delete'} '${productToDelete?.name}'?`"
            :confirm-text="formLabels.buttons?.delete || 'Delete'"
            :cancel-text="formLabels.buttons?.back || 'Back'"
            variant="destructive"
            @confirm="handleDeleteConfirm"
            @cancel="handleDeleteCancel"
        />
    </AppLayout>
</template>