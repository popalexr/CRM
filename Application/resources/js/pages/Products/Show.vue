<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { ArrowLeftIcon } from 'lucide-vue-next';
import { computed } from 'vue';

// Importăm componentele personalizate
import ProfileHeader from '@/components/ProfileHeader.vue';
import NotesSection from '@/components/NotesSection.vue';
import SearchInput from '@/components/SearchInput.vue';

// Definirea tipului ProductDetails
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
    // vat?: { name: string; rate: number }; // Exemplu dacă ai avea relația VAT
}

interface Props {
    product: ProductDetails;
}

const props = defineProps<Props>();

// Date pentru componenta ProfileHeader
const entityData = computed(() => ({
    id: props.product.id,
    name: props.product.name,
    // Folosim tipul produsului ca subtitlu
    email: props.product.type === 'product' ? 'Produs Fizic' : 'Serviciu',
    phone: undefined, // Produsele nu au număr de telefon
    avatar: props.product.image
}));

// Functie pentru a naviga la editarea produsului
const handleEditProduct = (productId: number) => {
    router.visit(route('products.form', { id: productId }));
};

// Functie pentru a naviga la lista de produse
const handleBack = () => {
    router.visit(route('products.index'));
};
</script>

<template>
    <Head :title="`Detalii: ${product.name}`" />

    <AppLayout>
        <div class="container mx-auto px-4 py-6">
            <!-- Antet pagină -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="sm" @click="handleBack" class="p-2">
                        <ArrowLeftIcon class="w-4 h-4" />
                    </Button>
                    <h1 class="text-xl font-semibold dark:text-white">Detalii Produs / Serviciu</h1>
                </div>
                <Button @click="handleEditProduct(product.id)">Editează</Button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Coloana principală (stânga) -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Header Profil Produs -->
                    <ProfileHeader 
                        :entity="entityData"
                    />

                    <!-- Card cu detalii structurate -->
                    <div class="bg-white dark:bg-black rounded-xl border border-gray-200 dark:border-gray-700 p-8 shadow-sm">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Specificații</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Secțiunea Comercială -->
                            <div class="space-y-4">
                                <div class="flex items-center space-x-2 pb-3 border-b border-gray-100 dark:border-gray-800">
                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Detalii Comerciale</h4>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex flex-col space-y-1">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Preț</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ product.price }} {{ product.currency }}</span>
                                    </div>
                                    <!-- Aici poți adăuga TVA dacă ai relația în model -->
                                    <!-- 
                                    <div v-if="product.vat" class="flex flex-col space-y-1">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">TVA</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ product.vat.name }} ({{ product.vat.rate }}%)</span>
                                    </div>
                                    -->
                                </div>
                            </div>
                            
                            <!-- Secțiunea Logistică -->
                            <div class="space-y-4">
                                <div class="flex items-center space-x-2 pb-3 border-b border-gray-100 dark:border-gray-800">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Detalii Logistice</h4>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex flex-col space-y-1">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Unitate de Măsură</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ product.unit }}</span>
                                    </div>
                                    <div v-if="product.type === 'product'" class="flex flex-col space-y-1">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Cantitate în Stoc</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ product.quantity ?? 0 }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Secțiunea de Descriere/Note -->
                    <NotesSection 
                        :content="product.description"
                        default-text="Acest produs nu are o descriere."
                        title="Descriere Produs"
                    />
                </div>

                <!-- Coloana secundară (dreapta) -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-4 dark:text-white">Facturi Asociate</h3>
                        
                        <div class="mb-4">
                            <SearchInput 
                                placeholder="Caută facturi..."
                            />
                        </div>

                        <div class="bg-white dark:bg-black rounded-lg border border-gray-200 dark:border-gray-700 p-6 text-center">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Nu au fost găsite facturi pentru acest produs.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>