<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Link, Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator'; // Adaugat un separator optional
import { Button } from '@/components/ui/button';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';


const props = defineProps({
    client: {
        type: Object as () => {
            id: number;
            client_name: string;
            client_logo: string | null;
            client_type: 'individual' | 'business';
            client_email: string | null;
            client_phone: string | null;
            cui: string | null;
            registration_number: string | null;
            address: string | null;
            city: string | null;
            county: string | null;
            country: string | null;
            bank_name: string | null;
            iban: string | null;
            swift: string | null;
            vat_number: string | null;
            tax_code: string | null;
            currency: string | null;
            notes: string | null;
            contact_persons?: Array<{
                 id: number;
                 name: string;
                 email: string | null;
                 phone: string | null;
             }>;
        },
        required: true,
    },
    // invoices: Array,
});

const isPJ = computed(() => props.client.client_type === 'business');

const hasContactPersons = computed(() =>
    Array.isArray(props.client.contact_persons) && props.client.contact_persons.length > 0
);

const fullAddress = computed(() => {
    const parts = [];
    if (props.client.address) parts.push(props.client.address);
    if (props.client.city) parts.push(props.client.city);
    if (props.client.county) parts.push(props.client.county);
    if (props.client.country) parts.push(props.client.country);
    return parts.filter(part => part).join(', ');
});

</script>

<template>
    <Head :title="'Detalii Client: ' + client.client_name" />

    <AppLayout>
        <div class="container mx-auto px-4 py-6">
             <div class="flex items-center justify-between mb-6">
                 <h1 class="text-2xl font-bold">{{ client.client_name }}</h1>
                 <div class="flex items-center gap-2">
                     <Link :href="route('clients.index')">
                         <Button variant="outline">Înapoi la lista clienți</Button>
                     </Link>
                     <Link :href="route('clients.edit', client.id)">
                          <Button>Editare Client</Button>
                     </Link>
                 </div>
             </div>

             <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                 <!-- Sectiunea 1: Detalii Generale -->
                 <Card class="md:col-span-2">
                     <CardHeader>
                         <CardTitle>Informații Generale</CardTitle>
                     </CardHeader>
                     <CardContent>
                         <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                             <div class="md:col-span-1 flex justify-center">
                                <Avatar class="w-24 h-24 rounded-lg">
                                     <AvatarImage
                                         v-if="client.client_logo"
                                         :src="client.client_logo"
                                         :alt="client.client_name"
                                     />
                                     <AvatarFallback class="text-4xl font-medium rounded-lg">
                                        {{ client.client_name.charAt(0).toUpperCase() }}
                                    </AvatarFallback>
                                 </Avatar>
                             </div>
                             <div class="md:col-span-2 space-y-2 text-sm">
                                 <p><strong>Tip client:</strong> {{ client.client_type === 'individual' ? 'Persoana Fizica' : 'Persoana Juridica' }}</p>
                                 <p v-if="client.client_email"><strong>Email:</strong> {{ client.client_email }}</p>
                                 <p v-if="client.client_phone"><strong>Telefon:</strong> {{ client.client_phone }}</p>
                                 <p v-if="fullAddress"><strong>Adresă:</strong> {{ fullAddress }}</p>

                                 <template v-if="isPJ">
                                     <Separator class="my-2" />
                                     <p v-if="client.cui"><strong>CUI:</strong> {{ client.cui }}</p>
                                     <p v-if="client.registration_number"><strong>Nr. înregistrare:</strong> {{ client.registration_number }}</p>
                                     <p v-if="client.vat_number"><strong>CIF/Nr. TVA:</strong> {{ client.vat_number }}</p>
                                     <p v-if="client.tax_code"><strong>Cod fiscal:</strong> {{ client.tax_code }}</p>
                                     <p v-if="client.swift"><strong>Cod Swift:</strong> {{ client.swift }}</p>
                                 </template>

                                 <Separator class="my-2" />
                                  <p v-if="client.iban"><strong>IBAN:</strong> {{ client.iban }}</p>
                                  <p v-if="client.bank_name"><strong>Bancă:</strong> {{ client.bank_name }}</p>
                                  <p v-if="client.currency"><strong>Monedă facturare:</strong> {{ client.currency }}</p>
                                  <p v-if="client.notes"><strong>Note:</strong> {{ client.notes }}</p>
                             </div>
                         </div>
                     </CardContent>
                 </Card>

                  <!-- Sectiunea 2: Persoane de Contact (in aceeasi coloana cu detalii generale pe md+) -->
                 <Card v-if="isPJ" class="md:col-span-1">
                    <CardHeader>
                        <CardTitle>Persoane de Contact</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div v-if="hasContactPersons" class="space-y-4 text-sm">
                             <div v-for="contact in client.contact_persons" :key="contact.id" class="border-b pb-2 last:border-0 last:pb-0">
                                 <p class="font-medium">{{ contact.name }}</p>
                                 <p v-if="contact.email" class="text-muted-foreground">{{ contact.email }}</p>
                                 <p v-if="contact.phone" class="text-muted-foreground">{{ contact.phone }}</p>
                             </div>
                        </div>
                         <div v-else class="text-center text-muted-foreground text-sm">
                             Nu există persoane de contact asociate.
                         </div>
                    </CardContent>
                 </Card>

                  <!-- Sectiunea 3: Facturi Emise -->
                 <Card class="col-span-full">
                    <CardHeader>
                        <CardTitle>Facturi Emise</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-muted-foreground text-sm">TO BE DONE - Aici va veni lista facturilor emise pentru acest client.</p>
                    </CardContent>
                 </Card>
             </div>
        </div>
     </AppLayout>
</template>

<style scoped>
/* Add specific styles if needed */
</style>