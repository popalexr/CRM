<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Link, Head, usePage, router } from '@inertiajs/vue3';
import { computed, ref, reactive } from 'vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator'; // Adaugat un separator optional
import { Button } from '@/components/ui/button';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Client, ClientContact } from '@/types';
import { ArrowLeft, Edit, Plus, Trash2 } from 'lucide-vue-next';
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import axios from 'axios';


const page = usePage();

const client = page.props.client as Client;
const initialContacts = (page.props.clientContacts || []) as ClientContact[];
const contacts = ref<ClientContact[]>(initialContacts);

const showContactDialog = ref(false);
const editingContact = ref<ClientContact | null>(null);
const loading = ref(false);

const contactForm = reactive({
  contact_name: '',
  contact_position: '',
  contact_email: '',
  contact_phone: '',
});

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
    router.visit(route('clients.index'));
};

const resetForm = () => {
  contactForm.contact_name = '';
  contactForm.contact_position = '';
  contactForm.contact_email = '';
  contactForm.contact_phone = '';
};

const openAddContactDialog = () => {
  editingContact.value = null;
  resetForm();
  showContactDialog.value = true;
};

const openEditContactDialog = (contact: ClientContact) => {
  editingContact.value = contact;
  contactForm.contact_name = contact.contact_name;
  contactForm.contact_position = contact.contact_position || '';
  contactForm.contact_email = contact.contact_email || '';
  contactForm.contact_phone = contact.contact_phone || '';
  showContactDialog.value = true;
};

const saveContact = async () => {
  loading.value = true;
  
  try {
    if (editingContact.value) {
      await axios.put(`/clients/${client.id}/contacts/${editingContact.value.id}`, contactForm);
    } else {
      await axios.post(`/clients/${client.id}/contacts`, contactForm);
    }
    
    await loadContacts();
    showContactDialog.value = false;
    resetForm();
  } catch (error) {
    console.error('Error saving contact:', error);
  } finally {
    loading.value = false;
  }
};

const deleteContact = async (contactId: number) => {
  if (!confirm('Are you sure you want to delete this contact?')) return;
  
  try {
    await axios.delete(`/clients/${client.id}/contacts/${contactId}`);
    await loadContacts();
  } catch (error) {
    console.error('Error deleting contact:', error);
  }
};

const loadContacts = async () => {
  try {
    const response = await axios.get(`/clients/${client.id}/contacts`);
    contacts.value = response.data.contacts;
  } catch (error) {
    console.error('Error loading contacts:', error);
  }
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
                        <div class="flex items-center justify-between">
                            <CardTitle>Persoane de Contact</CardTitle>
                            <Button variant="outline" size="sm" @click="openAddContactDialog" class="gap-1">
                                <Plus class="h-4 w-4" />
                                Add
                            </Button>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div v-if="hasContactPersons" class="space-y-4 text-sm">
                             <div v-for="contact in contacts" :key="contact.id" class="border-b pb-2 last:border-0 last:pb-0">
                                 <div class="flex items-start justify-between">
                                     <div class="flex-1">
                                         <p class="font-medium">{{ contact.contact_name }}</p>
                                         <p v-if="contact.contact_position" class="text-xs text-muted-foreground">{{ contact.contact_position }}</p>
                                         <p v-if="contact.contact_email" class="text-muted-foreground">{{ contact.contact_email }}</p>
                                         <p v-if="contact.contact_phone" class="text-muted-foreground">{{ contact.contact_phone }}</p>
                                     </div>
                                     <div class="flex gap-1">
                                         <Button variant="ghost" size="sm" @click="openEditContactDialog(contact)">
                                             <Edit class="h-3 w-3" />
                                         </Button>
                                         <Button variant="ghost" size="sm" @click="deleteContact(contact.id!)">
                                             <Trash2 class="h-3 w-3" />
                                         </Button>
                                     </div>
                                 </div>
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
     
     <!-- Add/Edit Contact Dialog -->
     <Dialog v-model:open="showContactDialog">
      <DialogContent class="sm:max-w-[425px]">
        <DialogHeader>
          <DialogTitle>
            {{ editingContact ? 'Edit Contact' : 'Add Contact' }}
          </DialogTitle>
        </DialogHeader>
        <form @submit.prevent="saveContact" class="space-y-4">
          <div class="space-y-2">
            <Label for="contact_name">Name *</Label>
            <Input
              id="contact_name"
              v-model="contactForm.contact_name"
              placeholder="Contact name"
              required
            />
          </div>
          <div class="space-y-2">
            <Label for="contact_position">Position</Label>
            <Input
              id="contact_position"
              v-model="contactForm.contact_position"
              placeholder="Job title or position"
            />
          </div>
          <div class="space-y-2">
            <Label for="contact_email">Email</Label>
            <Input
              id="contact_email"
              v-model="contactForm.contact_email"
              type="email"
              placeholder="Email address"
            />
          </div>
          <div class="space-y-2">
            <Label for="contact_phone">Phone</Label>
            <Input
              id="contact_phone"
              v-model="contactForm.contact_phone"
              type="tel"
              placeholder="Phone number"
            />
          </div>
          <div class="flex justify-end gap-2">
            <Button type="button" variant="outline" @click="showContactDialog = false">
              Cancel
            </Button>
            <Button type="submit" :disabled="loading">
              {{ editingContact ? 'Update' : 'Add' }} Contact
            </Button>
          </div>
        </form>
      </DialogContent>
    </Dialog>
</template>

<style scoped>
/* Add specific styles if needed */
</style>