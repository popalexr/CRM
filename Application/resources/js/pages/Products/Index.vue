<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';

// Definirea tipului Product conform modelului
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
}

const props = defineProps<Props>();

// Functie pentru a naviga la detaliile produsului
const handleShowProduct = (productId: number) => {
    router.visit(route('products.show', { id: productId }));
};

// Functie pentru a naviga la adaugarea unui produs nou
const handleCreateProduct = () => {
    router.visit(route('products.create'));
};

// Functie pentru a obtine initialele numelui produsului
const getInitials = (name: string) => {
    return name.charAt(0).toUpperCase();
};

// Functie pentru a formata pretul
const formatPrice = (price: number, currency: string) => {
    return `${price} ${currency}`;
};
</script>

<template>
    <Head title="Produse și Servicii" />

    <AppLayout>
        <div class="container mx-auto px-4 py-6">
            <!-- Antet pagina -->
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold">Produse și Servicii</h1>
                <Button @click="handleCreateProduct">Adaugă Produs/Serviciu</Button>
            </div>

            <!-- Lista de produse -->
            <div v-if="products.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <Card 
                    v-for="product in products" 
                    :key="product.id" 
                    class="cursor-pointer hover:shadow-lg transition-shadow"
                    @click="handleShowProduct(product.id)"
                >
                    <CardHeader class="pb-3">
                        <div class="flex items-center space-x-3">
                            <Avatar class="w-12 h-12 rounded-lg">
                                <AvatarImage
                                    v-if="product.image"
                                    :src="product.image"
                                    :alt="product.name"
                                />
                                <AvatarFallback class="text-lg font-medium">
                                    {{ getInitials(product.name) }}
                                </AvatarFallback>
                            </Avatar>
                            <div class="flex-1">
                                <CardTitle class="text-lg">{{ product.name }}</CardTitle>
                                <div class="flex items-center space-x-2 mt-1">
                                    <Badge :variant="product.type === 'product' ? 'default' : 'secondary'">
                                        {{ product.type === 'product' ? 'Produs' : 'Serviciu' }}
                                    </Badge>
                                </div>
                            </div>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-2 text-sm">
                            <p><strong>Preț:</strong> {{ formatPrice(product.price, product.currency) }}</p>
                            <p><strong>Unitate:</strong> {{ product.unit }}</p>
                            <p v-if="product.type === 'product' && product.quantity !== undefined">
                                <strong>Cantitate:</strong> {{ product.quantity }}
                            </p>
                            <p v-if="product.description" class="text-muted-foreground line-clamp-2">
                                {{ product.description }}
                            </p>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Mesaj cand nu sunt produse -->
            <div v-else class="text-center py-12">
                <div class="max-w-md mx-auto">
                    <h3 class="text-lg font-semibold mb-2">Nu există produse sau servicii</h3>
                    <p class="text-muted-foreground mb-4">
                        Începe prin a adăuga primul tău produs sau serviciu.
                    </p>
                    <Button @click="handleCreateProduct">Adaugă Produs/Serviciu</Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
