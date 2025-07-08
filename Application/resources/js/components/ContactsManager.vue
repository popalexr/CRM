<template>
  <div class="space-y-4">
    <!-- Add Contact Button -->
    <div class="flex justify-end">
      <Button @click="openAddContactDialog" class="gap-2">
        <Plus class="h-4 w-4" />
        Add Contact
      </Button>
    </div>

    <!-- Contacts List -->
    <div v-if="contacts.length > 0" class="space-y-3">
      <div
        v-for="contact in contacts"
        :key="contact.id"
        class="p-4 border rounded-lg bg-card"
      >
        <div class="flex items-start justify-between">
          <div class="flex-1">
            <h4 class="font-medium">{{ contact.contact_name }}</h4>
            <p v-if="contact.contact_position" class="text-sm text-muted-foreground">
              {{ contact.contact_position }}
            </p>
            <div class="mt-2 space-y-1">
              <p v-if="contact.contact_email" class="text-sm">
                <span class="font-medium">Email:</span> {{ contact.contact_email }}
              </p>
              <p v-if="contact.contact_phone" class="text-sm">
                <span class="font-medium">Phone:</span> {{ contact.contact_phone }}
              </p>
            </div>
          </div>
          <div class="flex gap-2">
            <Button
              variant="ghost"
              size="sm"
              @click="openEditContactDialog(contact)"
            >
              <Edit class="h-4 w-4" />
            </Button>
            <Button
              variant="ghost"
              size="sm"
              @click="deleteContact(contact.id!)"
            >
              <Trash2 class="h-4 w-4" />
            </Button>
          </div>
        </div>
      </div>
    </div>

    <!-- No contacts message -->
    <div v-else class="text-center py-8 text-muted-foreground">
      No contacts added yet. Click "Add Contact" to add one.
    </div>

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
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { Plus, Edit, Trash2 } from 'lucide-vue-next';
import { ClientContact } from '@/types';
import axios from 'axios';

interface Props {
  clientId: number;
}

const props = defineProps<Props>();

const contacts = ref<ClientContact[]>([]);
const showContactDialog = ref(false);
const editingContact = ref<ClientContact | null>(null);
const loading = ref(false);

const contactForm = reactive({
  contact_name: '',
  contact_position: '',
  contact_email: '',
  contact_phone: '',
});

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
      // Update existing contact
      await axios.put(`/clients/${props.clientId}/contacts/${editingContact.value.id}`, contactForm);
    } else {
      // Create new contact
      await axios.post(`/clients/${props.clientId}/contacts`, contactForm);
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
    await axios.delete(`/clients/${props.clientId}/contacts/${contactId}`);
    await loadContacts();
  } catch (error) {
    console.error('Error deleting contact:', error);
  }
};

const loadContacts = async () => {
  try {
    const response = await axios.get(`/clients/${props.clientId}/contacts`);
    contacts.value = response.data.contacts;
  } catch (error) {
    console.error('Error loading contacts:', error);
  }
};

onMounted(() => {
  loadContacts();
});
</script>
