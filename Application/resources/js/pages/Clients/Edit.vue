<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { 
    Select, 
    SelectContent, 
    SelectItem, 
    SelectTrigger, 
    SelectValue,
    SelectIcon
} from '@/components/ui/select';
import { 
    Table, 
    TableBody, 
    TableCell, 
    TableHead, 
    TableHeader, 
    TableRow 
} from '@/components/ui/table';
import { 
    Dialog, 
    DialogContent, 
    DialogHeader, 
    DialogTitle, 
    DialogTrigger,
    DialogFooter
} from '@/components/ui/dialog';
import { FormField } from '@/components/ui/form';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { ArrowLeft, Edit, MoreHorizontal, Plus, Trash2 } from 'lucide-vue-next';
import { ref, computed, onMounted } from 'vue';

interface Props {
    client: {
        id: number;
        name: string;
        clientType: string;
        cui?: string;
        registrationNumber?: string;
        email: string;
        phone: string;
        country: string;
        iban?: string;
        bank?: string;
        address: string;
        city: string;
        county: string;
        currency: string;
        contactPersons?: Array<{
            id: number;
            name: string;
            email: string;
            phone: string;
        }>;
    };
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Clients',
        href: '/clients',
    },
    {
        title: 'Edit Client',
        href: `/clients/${props.client.id}/edit`,
    },
];

// Form data - populate with existing client data
const form = useForm({
    name: props.client.name || '',
    clientType: props.client.clientType || '',
    cui: props.client.cui || '',
    registrationNumber: props.client.registrationNumber || '',
    email: props.client.email || '',
    phone: props.client.phone || '',
    country: props.client.country || '',
    iban: props.client.iban || '',
    bank: props.client.bank || '',
    address: props.client.address || '',
    city: props.client.city || '',
    county: props.client.county || '',
    currency: props.client.currency || '',
});

// Contact persons data - populate with existing data
const contactPersons = ref(props.client.contactPersons || []);

// Dialog state for contact person
const isContactDialogOpen = ref(false);
const editingContactId = ref<number | null>(null);

// Form for contact person
const contactForm = useForm({
    name: '',
    email: '',
    phone: '',
});

const isEditingContact = computed(() => editingContactId.value !== null);

const clientTypes = [
    { value: 'individual', label: 'Persoana Fizica' },
    { value: 'company', label: 'Persoana Juridica' }
];

const currencies = [
    { value: 'ron', label: 'RON' },
    { value: 'eur', label: 'EUR' },
    { value: 'usd', label: 'USD' }
];

const countries = [
    { value: 'ro', label: 'Romania' },
    { value: 'hu', label: 'Hungary' },
    { value: 'bg', label: 'Bulgaria' },
    { value: 'de', label: 'Germany' },
    { value: 'fr', label: 'France' }
];

const isCompanyClient = computed(() => form.clientType === 'company');

const handleSubmit = () => {
    // Add contact persons to form data if it's a company
    if (isCompanyClient.value) {
        form.transform(data => ({
            ...data,
            contactPersons: contactPersons.value
        }));
    }
    
    form.put(route('clients.update', props.client.id), {
        onSuccess: () => {
            // Handle success
        }
    });
};

const handleBack = () => {
    router.visit(route('clients.index'));
};

const openContactDialog = (contactId: number | null = null) => {
    editingContactId.value = contactId;
    
    if (contactId) {
        const contact = contactPersons.value.find(c => c.id === contactId);
        if (contact) {
            contactForm.name = contact.name;
            contactForm.email = contact.email;
            contactForm.phone = contact.phone;
        }
    } else {
        contactForm.reset();
    }
    
    isContactDialogOpen.value = true;
};

const handleSaveContact = () => {
    if (isEditingContact.value) {
        // Update existing contact
        const index = contactPersons.value.findIndex(c => c.id === editingContactId.value);
        if (index !== -1) {
            contactPersons.value[index] = {
                ...contactPersons.value[index],
                name: contactForm.name,
                email: contactForm.email,
                phone: contactForm.phone
            };
        }
    } else {
        // Add new contact
        const newContact = {
            id: Date.now(), // In production, this would be handled by the backend
            name: contactForm.name,
            email: contactForm.email,
            phone: contactForm.phone
        };
        contactPersons.value.push(newContact);
    }
    
    isContactDialogOpen.value = false;
    editingContactId.value = null;
    contactForm.reset();
};

const handleDeleteContact = (contactId: number) => {
    const index = contactPersons.value.findIndex(c => c.id === contactId);
    if (index !== -1) {
        contactPersons.value.splice(index, 1);
    }
};
</script>

<template>
    <Head :title="`Edit ${client.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <!-- Header Section -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="sm" @click="handleBack" class="gap-2">
                        <ArrowLeft class="h-4 w-4" />
                        Back
                    </Button>
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight">Edit Client</h1>
                        <p class="text-muted-foreground">Update {{ client.name }} information</p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="handleSubmit" class="space-y-6">
                <!-- General Information Section -->
                <Card>
                    <CardHeader>
                        <CardTitle>Informații generale</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Client Name -->
                            <div class="space-y-2">
                                <Label for="name">Nume client *</Label>
                                <Input 
                                    id="name" 
                                    v-model="form.name" 
                                    required 
                                    placeholder="Introduceți numele clientului"
                                />
                            </div>

                            <!-- Client Type -->
                            <div class="space-y-2">
                                <Label for="clientType">Tip client *</Label>
                                <Select v-model="form.clientType" required>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Selectați tipul clientului" />
                                        <SelectIcon />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem 
                                            v-for="type in clientTypes" 
                                            :key="type.value" 
                                            :value="type.value"
                                        >
                                            {{ type.label }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            <!-- CUI -->
                            <div class="space-y-2">
                                <Label for="cui">CUI</Label>
                                <Input 
                                    id="cui" 
                                    v-model="form.cui" 
                                    placeholder="Introduceți CUI-ul"
                                />
                            </div>

                            <!-- Registration Number -->
                            <div class="space-y-2">
                                <Label for="registrationNumber">Nr. înregistrare</Label>
                                <Input 
                                    id="registrationNumber" 
                                    v-model="form.registrationNumber" 
                                    placeholder="Introduceți nr. de înregistrare"
                                />
                            </div>

                            <!-- Email -->
                            <div class="space-y-2">
                                <Label for="email">Email *</Label>
                                <Input 
                                    id="email" 
                                    type="email" 
                                    v-model="form.email" 
                                    required 
                                    placeholder="email@example.com"
                                />
                            </div>

                            <!-- Phone -->
                            <div class="space-y-2">
                                <Label for="phone">Telefon *</Label>
                                <Input 
                                    id="phone" 
                                    v-model="form.phone" 
                                    required 
                                    placeholder="+40 123 456 789"
                                />
                            </div>

                            <!-- Country -->
                            <div class="space-y-2">
                                <Label for="country">Țară *</Label>
                                <Select v-model="form.country" required>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Selectați țara" />
                                        <SelectIcon />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem 
                                            v-for="country in countries" 
                                            :key="country.value" 
                                            :value="country.value"
                                        >
                                            {{ country.label }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            <!-- IBAN -->
                            <div class="space-y-2">
                                <Label for="iban">IBAN</Label>
                                <Input 
                                    id="iban" 
                                    v-model="form.iban" 
                                    placeholder="RO49 AAAA 1B31 0075 9384 0000"
                                />
                            </div>

                            <!-- Bank -->
                            <div class="space-y-2">
                                <Label for="bank">Bancă</Label>
                                <Input 
                                    id="bank" 
                                    v-model="form.bank" 
                                    placeholder="Introduceți numele băncii"
                                />
                            </div>

                            <!-- Currency -->
                            <div class="space-y-2">
                                <Label for="currency">Monedă de facturare *</Label>
                                <Select v-model="form.currency" required>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Selectați moneda" />
                                        <SelectIcon />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem 
                                            v-for="currency in currencies" 
                                            :key="currency.value" 
                                            :value="currency.value"
                                        >
                                            {{ currency.label }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>

                        <!-- Address Section -->
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <Label for="address">Adresă *</Label>
                                <Input 
                                    id="address" 
                                    v-model="form.address" 
                                    required 
                                    placeholder="Introduceți adresa"
                                />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- City -->
                                <div class="space-y-2">
                                    <Label for="city">Localitate *</Label>
                                    <Input 
                                        id="city" 
                                        v-model="form.city" 
                                        required 
                                        placeholder="Introduceți localitatea"
                                    />
                                </div>

                                <!-- County -->
                                <div class="space-y-2">
                                    <Label for="county">Județ *</Label>
                                    <Input 
                                        id="county" 
                                        v-model="form.county" 
                                        required 
                                        placeholder="Introduceți județul"
                                    />
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Contact Persons Section (only for company clients) -->
                <Card v-if="isCompanyClient">
                    <CardHeader>
                        <div class="flex items-center justify-between">
                            <CardTitle>Persoane de contact</CardTitle>
                            <Button type="button" @click="openContactDialog()" class="gap-2">
                                <Plus class="h-4 w-4" />
                                Adaugă persoană
                            </Button>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="rounded-md border">
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Nume persoană</TableHead>
                                        <TableHead>Email</TableHead>
                                        <TableHead>Număr de telefon</TableHead>
                                        <TableHead class="text-right">Opțiuni</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow v-for="contact in contactPersons" :key="contact.id">
                                        <TableCell class="font-medium">{{ contact.name }}</TableCell>
                                        <TableCell>{{ contact.email }}</TableCell>
                                        <TableCell>{{ contact.phone }}</TableCell>
                                        <TableCell class="text-right">
                                            <DropdownMenu>
                                                <DropdownMenuTrigger as-child>
                                                    <Button variant="ghost" size="sm" class="h-8 w-8 p-0">
                                                        <MoreHorizontal class="h-4 w-4" />
                                                        <span class="sr-only">Open menu</span>
                                                    </Button>
                                                </DropdownMenuTrigger>
                                                <DropdownMenuContent align="end">
                                                    <DropdownMenuItem @click="openContactDialog(contact.id)">
                                                        <Edit class="mr-2 h-4 w-4" />
                                                        Editare
                                                    </DropdownMenuItem>
                                                    <DropdownMenuItem 
                                                        @click="handleDeleteContact(contact.id)"
                                                        class="text-red-600 focus:text-red-600"
                                                    >
                                                        <Trash2 class="mr-2 h-4 w-4" />
                                                        Ștergere
                                                    </DropdownMenuItem>
                                                </DropdownMenuContent>
                                            </DropdownMenu>
                                        </TableCell>
                                    </TableRow>
                                    <TableRow v-if="contactPersons.length === 0">
                                        <TableCell colspan="4" class="text-center text-muted-foreground py-8">
                                            Nu există persoane de contact adăugate
                                        </TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>
                    </CardContent>
                </Card>

                <!-- Form Actions -->
                <div class="flex justify-end gap-4">
                    <Button type="button" variant="outline" @click="handleBack">
                        Cancel
                    </Button>
                    <Button type="submit" :disabled="form.processing">
                        {{ form.processing ? 'Updating...' : 'Update Client' }}
                    </Button>
                </div>
            </form>
        </div>

        <!-- Contact Person Dialog -->
        <Dialog v-model:open="isContactDialogOpen">
            <DialogContent class="sm:max-w-[425px]">
                <DialogHeader>
                    <DialogTitle>
                        {{ isEditingContact ? 'Editare persoană de contact' : 'Adaugă persoană de contact' }}
                    </DialogTitle>
                </DialogHeader>
                <div class="space-y-4 py-4">
                    <div class="space-y-2">
                        <Label for="contactName">Nume *</Label>
                        <Input 
                            id="contactName" 
                            v-model="contactForm.name" 
                            required 
                            placeholder="Introduceți numele"
                        />
                    </div>
                    <div class="space-y-2">
                        <Label for="contactEmail">Email *</Label>
                        <Input 
                            id="contactEmail" 
                            type="email" 
                            v-model="contactForm.email" 
                            required 
                            placeholder="email@example.com"
                        />
                    </div>
                    <div class="space-y-2">
                        <Label for="contactPhone">Număr de telefon *</Label>
                        <Input 
                            id="contactPhone" 
                            v-model="contactForm.phone" 
                            required 
                            placeholder="+40 123 456 789"
                        />
                    </div>
                </div>
                <DialogFooter>
                    <Button type="button" variant="outline" @click="isContactDialogOpen = false">
                        Cancel
                    </Button>
                    <Button type="button" @click="handleSaveContact">
                        {{ isEditingContact ? 'Update' : 'Add' }}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
