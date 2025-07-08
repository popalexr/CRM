<template>
  <div class="space-y-4">
    <!-- Add Contact Button -->
    <div class="flex justify-end">
      <Button @click="addContact" variant="outline" size="sm" class="gap-2">
        <Plus class="h-4 w-4" />
        Add Contact
      </Button>
    </div>

    <!-- Contacts List -->
    <div v-if="contacts.length > 0" class="space-y-3">
      <div
        v-for="(contact, index) in contacts"
        :key="index"
        class="p-4 border rounded-lg bg-card"
      >
        <div class="flex items-start justify-between">
          <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-2">
              <Label :for="`contact_name_${index}`">Name *</Label>
              <Input
                :id="`contact_name_${index}`"
                v-model="contact.name"
                placeholder="Contact name"
                required
              />
            </div>
            <div class="space-y-2">
              <Label :for="`contact_position_${index}`">Position</Label>
              <Input
                :id="`contact_position_${index}`"
                v-model="contact.position"
                placeholder="Job title or position"
              />
            </div>
            <div class="space-y-2">
              <Label :for="`contact_email_${index}`">Email</Label>
              <Input
                :id="`contact_email_${index}`"
                v-model="contact.email"
                type="email"
                placeholder="Email address"
              />
            </div>
            <div class="space-y-2">
              <Label :for="`contact_phone_${index}`">Phone</Label>
              <Input
                :id="`contact_phone_${index}`"
                v-model="contact.phone"
                type="tel"
                placeholder="Phone number"
              />
            </div>
          </div>
          <div class="ml-4">
            <Button
              variant="ghost"
              size="sm"
              @click="removeContact(index)"
              type="button"
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
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Plus, Trash2 } from 'lucide-vue-next';

interface Contact {
  name: string;
  position: string;
  email: string;
  phone: string;
}

interface Props {
  modelValue: Contact[];
}

interface Emits {
  (e: 'update:modelValue', value: Contact[]): void;
}

const props = defineProps<Props>();
const emit = defineEmits<Emits>();

const contacts = ref<Contact[]>(props.modelValue || []);

const addContact = () => {
  contacts.value.push({
    name: '',
    position: '',
    email: '',
    phone: '',
  });
};

const removeContact = (index: number) => {
  contacts.value.splice(index, 1);
};

watch(
  contacts,
  (newValue) => {
    emit('update:modelValue', newValue);
  },
  { deep: true }
);

watch(
  () => props.modelValue,
  (newValue) => {
    contacts.value = newValue || [];
  }
);
</script>
