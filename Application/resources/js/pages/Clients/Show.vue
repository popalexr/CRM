<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Link, Head, usePage, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import { Button } from '@/components/ui/button';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from '@/components/ui/accordion';
import { ScrollArea } from '@/components/ui/scroll-area';
import { Client, ClientContact } from '@/types';
import { ArrowLeft } from 'lucide-vue-next';


const page = usePage();

const client = page.props.client as Client;
const initialContacts = (page.props.clientContacts || []) as ClientContact[];
const contacts = ref<ClientContact[]>(initialContacts);

const isPJ = computed(() => client.client_type === 'business');

const hasContactPersons = computed(() =>
    Array.isArray(contacts.value) && contacts.value.length > 0
);

const fullAddress = computed(() => {
    const parts = [];
    if (client.address) parts.push(client.address);
    if (client.city) parts.push(client.city);
    if (client.county) parts.push(client.county);
    if (client.country) parts.push(client.country);
    return parts.filter(part => part).join(', ');
});

const handleBack = () => {
    console.log('handleBack called');
    router.visit(route('clients.index'));
};

</script>

<template>
    <Head :title="'Detalii Client: ' + client.client_name" />

    <AppLayout>
        <div class="container mx-auto px-4 py-6">
             <div class="flex items-center justify-between mb-6">
                <div class="flex gap-2">
                    <Button variant="ghost" size="sm" @click="handleBack" class="gap-2">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                    <h1 class="text-2xl font-bold">{{ client.client_name }}</h1>
                </div>
                <div class="flex items-center gap-2">
                    <Link :href="route('clients.form', {id: client.id})">
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
                                <Avatar class="overflow-hidden aspect-square rounded-lg w-24 h-24">
                                     <AvatarImage
                                         v-if="client.client_logo"
                                         :src="client.client_logo"
                                         :alt="client.client_name"
                                         class="w-full h-full object-cover object-center"
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

                 <Card v-if="isPJ" class="md:col-span-1">
                    <CardHeader>
                        <div class="flex items-center justify-between">
                            <CardTitle>Persoane de Contact</CardTitle>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div v-if="hasContactPersons">
                            <ScrollArea class="h-[200px] w-full">
                                <div class="pr-4">
                                    <Accordion type="single" class="w-full" collapsible>
                                        <AccordionItem 
                                            v-for="contact in contacts" 
                                            :key="contact.id"
                                            :value="`contact-${contact.id}`"
                                            @click.stop
                                        >
                                            <AccordionTrigger class="text-left">
                                                <div class="flex items-center justify-between w-full pr-2">
                                                    <div>
                                                        <p class="font-medium">{{ contact.contact_name }}</p>
                                                    </div>
                                                </div>
                                            </AccordionTrigger>
                                            <AccordionContent>
                                                <div class="space-y-2 text-sm">
                                                    <p v-if="contact.contact_email" class="text-muted-foreground">
                                                        <strong>Email:</strong> {{ contact.contact_email }}
                                                    </p>
                                                    <p v-if="contact.contact_phone" class="text-muted-foreground">
                                                        <strong>Phone:</strong> {{ contact.contact_phone }}
                                                    </p>
                                                    <p v-if="contact.contact_position" class="text-muted-foreground">
                                                        <strong>Position:</strong> {{ contact.contact_position }}
                                                    </p>
                                                </div>
                                            </AccordionContent>
                                        </AccordionItem>
                                    </Accordion>
                                </div>
                            </ScrollArea>
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