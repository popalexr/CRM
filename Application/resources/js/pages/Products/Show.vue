<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Separator } from '@/components/ui/separator';

import { computed } from 'vue';

// Definirea tipului ProductDetails conform modelului si migrarii
interface ProductDetails {
    id: number;
    name: string;
    image?: string; // URL-ul imaginii
    type: 'product' | 'service'; // Tipul produsului
    price: number;
    currency: string;
    quantity?: number; // Optional, doar pentru produse fizice
    unit: string; // Unitate de masura
    description?: string;
    created_at: string;
    // invoices?: Array<any>; // Pentru facturi (TBD)
}

interface Props {
    product: ProductDetails;
}

const props = defineProps<Props>();

// Determina daca e un produs fizic (pentru a afisa cantitatea)
const isPhysicalProduct = computed(() => props.product.type === 'product');

// Functie pentru a naviga la editarea produsului (daca ai o pagina de editare)
const handleEditProduct = (productId: number) => {
    router.visit(route('products.form', { id: productId })); // Presupunem ruta products.form
};

// Functie pentru a naviga la lista de produse
const handleBackToList = () => {
    router.visit(route('products.index'));
};

// Functie pentru a formata data
const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('ro-RO');
};

// Functie pentru a obtine initialele numelui produsului pentru AvatarFallback
const getInitials = (name: string) => {
    return name.charAt(0).toUpperCase();
};

</script>

<template>
    <Head :title="`Detalii produs: ${product.name}`" />

    <AppLayout>
        <div class="container mx-auto px-4 py-6">
            <!-- Antet pagina -->
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold">Detalii Produs: {{ product.name }}</h1>
                <div class="flex space-x-2">
                    <Button variant="outline" @click="handleBackToList">Înapoi la lista produse</Button>
                    <Button @click="handleEditProduct(product.id)">Editează Produs</Button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Coloana 1: Informații Generale și Imagine Produs -->
                <Card class="lg:col-span-1">
                    <CardHeader>
                        <CardTitle>Informații Generale</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="flex flex-col items-center mb-6">
                            <Avatar class="w-24 h-24 rounded-lg mb-4">
                                <!-- Imagine Produs (foloseste campul 'image' din BD) -->
                                <AvatarImage
                                    v-if="product.image"
                                    :src="product.image"
                                    :alt="product.name"
                                />
                                <AvatarFallback class="text-3xl font-medium">
                                    {{ getInitials(product.name) }}
                                </AvatarFallback>
                            </Avatar>
                            <h2 class="text-xl font-semibold text-center">{{ product.name }}</h2>
                            <p class="text-muted-foreground text-sm">({{ product.type === 'product' ? 'Produs' : 'Serviciu' }})</p>
                        </div>

                        <Separator class="my-4" />

                        <div class="space-y-2 text-sm">
                            <p><strong>Preț:</strong> {{ product.price }} {{ product.currency }}</p>
                            <p><strong>Unit. Măsură:</strong> {{ product.unit }}</p>
                            <p v-if="isPhysicalProduct"><strong>Cantitate:</strong> {{ product.quantity }}</p>
                            <p v-if="product.description"><strong>Descriere:</strong> {{ product.description }}</p>
                            <p><strong>Creat la:</strong> {{ formatDate(product.created_at) }}</p>
                        </div>
                    </CardContent>
                </Card>

                <!-- Coloana 2 (sau 1 pe mobil): Facturi unde s-a folosit (TBD) -->
                <Card class="col-span-full lg:col-span-2"> <!-- Ocupa restul spatiului sau toata latimea pe mobil -->
                    <CardHeader>
                        <CardTitle>Facturi unde s-a folosit</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-muted-foreground text-sm">
                            Funcționalitatea de afișare a facturilor unde s-a folosit acest produs/serviciu va fi implementată ulterior.
                            <!-- Aici va veni un Table cu listarea facturilor (ex: invoice_id, quantity_used, etc.) -->
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>