<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type Client } from '@/types';
import { Head, usePage, useForm, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { 
    Table, 
    TableBody, 
    TableCell, 
    TableHead, 
    TableHeader, 
    TableRow 
} from '@/components/ui/table';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { 
    DropdownMenu, 
    DropdownMenuContent, 
    DropdownMenuItem, 
    DropdownMenuTrigger 
} from '@/components/ui/dropdown-menu';
import { 
    Dialog, 
    DialogContent, 
    DialogDescription, 
    DialogFooter, 
    DialogHeader, 
    DialogTitle, 
    DialogTrigger 
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { 
    Select, 
    SelectContent, 
    SelectItem, 
    SelectTrigger, 
    SelectValue,
    SelectIcon
} from '@/components/ui/select';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Edit, Eye, MoreHorizontal, Plus, Trash2 } from 'lucide-vue-next';
import { ref, computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Clients',
        href: '/clients',
    },
];

const page = usePage();
const clients = page.props.clients as Array<Client>;

// Modal state
const isAddClientDialogOpen = ref(false);
const activeTab = ref('general'); // 'general' or 'contacts'

// Form data for new client
const form = useForm({
    name: '',
    clientType: '',
    cui: '',
    registrationNumber: '',
    email: '',
    phone: '',
    country: '',
    iban: '',
    bank: '',
    address: '',
    city: '',
    county: '',
    currency: '',
});

// Contact persons data for the new client
interface ContactPerson {
    id: number;
    name: string;
    email: string;
    phone: string;
}

const contactPersons = ref<ContactPerson[]>([]);

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
    { value: 'individual', label: 'Persoană Fizică' },
    { value: 'company', label: 'Persoană Juridică' }
];

const currencies = [
    { value: 'ron', label: 'RON - Leu Românesc' },
    { value: 'eur', label: 'EUR - Euro' },
    { value: 'usd', label: 'USD - Dolar American' },
    { value: 'gbp', label: 'GBP - Liră Sterlină' },
    { value: 'chf', label: 'CHF - Franc Elvețian' },
    { value: 'cad', label: 'CAD - Dolar Canadian' },
    { value: 'aud', label: 'AUD - Dolar Australian' },
    { value: 'jpy', label: 'JPY - Yen Japonez' }
];

const countries = [
    { value: 'ro', label: 'România' },
    { value: 'de', label: 'Germania' },
    { value: 'fr', label: 'Franța' },
    { value: 'it', label: 'Italia' },
    { value: 'es', label: 'Spania' },
    { value: 'uk', label: 'Regatul Unit' },
    { value: 'us', label: 'Statele Unite' },
    { value: 'ca', label: 'Canada' },
    { value: 'au', label: 'Australia' },
    { value: 'hu', label: 'Ungaria' },
    { value: 'bg', label: 'Bulgaria' },
    { value: 'pl', label: 'Polonia' },
    { value: 'cz', label: 'Republica Cehă' },
    { value: 'sk', label: 'Slovacia' },
    { value: 'at', label: 'Austria' },
    { value: 'ch', label: 'Elveția' },
    { value: 'nl', label: 'Olanda' },
    { value: 'be', label: 'Belgia' },
    { value: 'se', label: 'Suedia' },
    { value: 'no', label: 'Norvegia' },
    { value: 'dk', label: 'Danemarca' },
    { value: 'fi', label: 'Finlanda' }
];

const isCompanyClient = computed(() => form.clientType === 'company');

const getFullAddress = (client: any) => {
    return `${client.address}, ${client.city}, ${client.county}, ${client.country}`;
};

const handleView = (clientId: number) => {
     router.visit(route('clients.show', clientId));
};

const handleEdit = (clientId: number) => {
    router.visit(route('clients.edit', clientId));
};

const handleDelete = (clientId: number) => {
    console.log('Delete client:', clientId);
};

const handleAddClient = () => {
    activeTab.value = 'general'; // Reset to general tab when opening modal
    isAddClientDialogOpen.value = true;
};

const handleSubmit = () => {
    console.log('Form data before submit:', form.data());
    console.log('Is company client:', isCompanyClient.value);
    console.log('Contact persons:', contactPersons.value);
    
    // Add contact persons to form data if it's a company
    if (isCompanyClient.value && contactPersons.value.length > 0) {
        form.transform(data => ({
            ...data,
            contactPersons: contactPersons.value
        }));
    }
    
    // Submit the form using the direct URL
    form.post('/clients', {
        onSuccess: () => {
            console.log('Client saved successfully');
            isAddClientDialogOpen.value = false;
            form.reset();
            contactPersons.value = [];
        },
        onError: (errors) => {
            console.error('Form submission errors:', errors);
        },
        onFinish: () => {
            console.log('Form submission finished');
        }
    });
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
    <Head title="Clients" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <!-- Header Section -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight">Clients</h1>
                    <p class="text-muted-foreground">Manage your company clients</p>
                </div>
                <Button @click="handleAddClient" class="gap-2">
                    <Plus class="h-4 w-4" />
                    Add Client
                </Button>
            </div>

            <!-- Table Section -->
            <div class="rounded-md border" v-if="clients.length > 0">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Logo</TableHead>
                            <TableHead>Company Name</TableHead>
                            <TableHead>Full Address</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="client in clients" :key="client.id">
                            <TableCell>
                                <Avatar class="h-10 w-10">
                                    <AvatarImage 
                                        v-if="client.client_logo" 
                                        :src="client.client_logo" 
                                        :alt="client.client_name" 
                                    />
                                    <AvatarFallback class="text-sm font-medium">
                                        {{ client.client_name.charAt(0).toUpperCase() }}
                                    </AvatarFallback>
                                </Avatar>
                            </TableCell>
                            <TableCell class="font-medium">{{ client.client_name }}</TableCell>
                            <TableCell class="text-muted-foreground">
                                {{ getFullAddress(client) }}
                            </TableCell>
                            <TableCell class="text-right">
                                <DropdownMenu>
                                    <DropdownMenuTrigger as-child>
                                        <Button variant="ghost" size="sm" class="h-8 w-8 p-0">
                                            <MoreHorizontal class="h-4 w-4" />
                                            <span class="sr-only">Open menu</span>
                                        </Button>
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent align="end">
                                        <DropdownMenuItem @click="handleView(client.id)">
                                            <Eye class="mr-2 h-4 w-4" />
                                            View
                                        </DropdownMenuItem>
                                        <DropdownMenuItem @click="handleEdit(client.id)">
                                            <Edit class="mr-2 h-4 w-4" />
                                            Edit
                                        </DropdownMenuItem>
                                        <DropdownMenuItem 
                                            @click="handleDelete(client.id)"
                                            class="text-red-600 focus:text-red-600"
                                        >
                                            <Trash2 class="mr-2 h-4 w-4" />
                                            Delete
                                        </DropdownMenuItem>
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

            <div v-else class="flex flex-col items-center justify-center py-16">
                <div class="text-center">
                    <h3 class="text-lg font-medium text-gray-900">No clients found</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by adding your first client.</p>
                    <Button @click="handleAddClient" class="mt-4 gap-2">
                        <Plus class="h-4 w-4" />
                        Add Client
                    </Button>
                </div>
            </div>
        </div>

        <!-- Add Client Dialog -->
        <Dialog v-model:open="isAddClientDialogOpen">
            <DialogContent class="sm:max-w-[800px] max-h-[90vh] overflow-y-auto">
                <DialogHeader>
                    <DialogTitle>Adăugare Client Nou</DialogTitle>
                    <DialogDescription>
                        Completați informațiile clientului mai jos. Faceți clic pe salvare când ați terminat.
                    </DialogDescription>
                </DialogHeader>
                
                <!-- Tab Navigation -->
                <div class="flex space-x-1 rounded-lg bg-muted p-1 mb-6">
                    <button
                        type="button"
                        @click="activeTab = 'general'"
                        :class="[
                            'flex-1 rounded-md px-3 py-2 text-sm font-medium transition-all',
                            activeTab === 'general'
                                ? 'bg-background text-foreground shadow-sm'
                                : 'text-muted-foreground hover:text-foreground'
                        ]"
                    >
                        Informații Generale
                    </button>
                    <button
                        type="button"
                        @click="activeTab = 'contacts'"
                        :disabled="!isCompanyClient"
                        :class="[
                            'flex-1 rounded-md px-3 py-2 text-sm font-medium transition-all',
                            activeTab === 'contacts' && isCompanyClient
                                ? 'bg-background text-foreground shadow-sm'
                                : 'text-muted-foreground hover:text-foreground',
                            !isCompanyClient && 'opacity-50 cursor-not-allowed'
                        ]"
                    >
                        Persoane de Contact
                        <span v-if="!isCompanyClient" class="ml-1 text-xs">(doar pentru companii)</span>
                    </button>
                </div>

                <form @submit.prevent="handleSubmit" class="space-y-6">
                    <!-- Informații Generale Section -->
                    <div v-show="activeTab === 'general'" class="space-y-4">
                        <h3 class="text-lg font-medium">Informații Generale</h3>
                        <div class="grid gap-4 py-4">
                            <!-- Nume Client -->
                            <div class="grid grid-cols-4 items-center gap-4">
                                <Label for="name" class="text-right">
                                    Nume client
                                </Label>
                                <Input 
                                    id="name" 
                                    v-model="form.name" 
                                    class="col-span-3" 
                                    placeholder="Introduceți numele clientului"
                                    required
                                />
                            </div>

                            <!-- Tip Client -->
                            <div class="grid grid-cols-4 items-center gap-4">
                                <Label for="clientType" class="text-right">
                                    Tip client
                                </Label>
                                <div class="col-span-3">
                                    <select 
                                        id="clientType"
                                        v-model="form.clientType" 
                                        required
                                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                    >
                                        <option value="" disabled>Selectați tipul clientului</option>
                                        <option v-for="type in clientTypes" :key="type.value" :value="type.value">
                                            {{ type.label }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- CUI (doar pentru companii) -->
                            <div v-if="isCompanyClient" class="grid grid-cols-4 items-center gap-4">
                                <Label for="cui" class="text-right">
                                    CUI
                                </Label>
                                <Input 
                                    id="cui" 
                                    v-model="form.cui" 
                                    class="col-span-3" 
                                    placeholder="Introduceți CUI"
                                />
                            </div>

                            <!-- Număr înregistrare (doar pentru companii) -->
                            <div v-if="isCompanyClient" class="grid grid-cols-4 items-center gap-4">
                                <Label for="registrationNumber" class="text-right">
                                    Nr. înregistrare
                                </Label>
                                <Input 
                                    id="registrationNumber" 
                                    v-model="form.registrationNumber" 
                                    class="col-span-3" 
                                    placeholder="Introduceți numărul de înregistrare"
                                />
                            </div>

                            <!-- Email -->
                            <div class="grid grid-cols-4 items-center gap-4">
                                <Label for="email" class="text-right">
                                    Email
                                </Label>
                                <Input 
                                    id="email" 
                                    type="email"
                                    v-model="form.email" 
                                    class="col-span-3" 
                                    placeholder="Introduceți adresa de email"
                                />
                            </div>

                            <!-- Telefon -->
                            <div class="grid grid-cols-4 items-center gap-4">
                                <Label for="phone" class="text-right">
                                    Telefon
                                </Label>
                                <Input 
                                    id="phone" 
                                    v-model="form.phone" 
                                    class="col-span-3" 
                                    placeholder="Introduceți numărul de telefon"
                                />
                            </div>

                            <!-- Țară -->
                            <div class="grid grid-cols-4 items-center gap-4">
                                <Label for="country" class="text-right">
                                    Țară
                                </Label>
                                <div class="col-span-3">
                                    <select 
                                        id="country"
                                        v-model="form.country"
                                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                    >
                                        <option value="" disabled>Selectați țara</option>
                                        <option v-for="country in countries" :key="country.value" :value="country.value">
                                            {{ country.label }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- IBAN -->
                            <div class="grid grid-cols-4 items-center gap-4">
                                <Label for="iban" class="text-right">
                                    IBAN
                                </Label>
                                <Input 
                                    id="iban" 
                                    v-model="form.iban" 
                                    class="col-span-3" 
                                    placeholder="Introduceți IBAN"
                                />
                            </div>

                            <!-- Bancă -->
                            <div class="grid grid-cols-4 items-center gap-4">
                                <Label for="bank" class="text-right">
                                    Bancă
                                </Label>
                                <Input 
                                    id="bank" 
                                    v-model="form.bank" 
                                    class="col-span-3" 
                                    placeholder="Introduceți numele băncii"
                                />
                            </div>

                            <!-- Adresă -->
                            <div class="grid grid-cols-4 items-center gap-4">
                                <Label for="address" class="text-right">
                                    Adresă
                                </Label>
                                <Input 
                                    id="address" 
                                    v-model="form.address" 
                                    class="col-span-3" 
                                    placeholder="Introduceți adresa"
                                />
                            </div>

                            <!-- Localitate -->
                            <div class="grid grid-cols-4 items-center gap-4">
                                <Label for="city" class="text-right">
                                    Localitate
                                </Label>
                                <Input 
                                    id="city" 
                                    v-model="form.city" 
                                    class="col-span-3" 
                                    placeholder="Introduceți localitatea"
                                />
                            </div>

                            <!-- Județ -->
                            <div class="grid grid-cols-4 items-center gap-4">
                                <Label for="county" class="text-right">
                                    Județ
                                </Label>
                                <Input 
                                    id="county" 
                                    v-model="form.county" 
                                    class="col-span-3" 
                                    placeholder="Introduceți județul"
                                />
                            </div>

                            <!-- Monedă de facturare -->
                            <div class="grid grid-cols-4 items-center gap-4">
                                <Label for="currency" class="text-right">
                                    Monedă de facturare
                                </Label>
                                <div class="col-span-3">
                                    <select 
                                        id="currency"
                                        v-model="form.currency"
                                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                    >
                                        <option value="" disabled>Selectați moneda</option>
                                        <option v-for="currency in currencies" :key="currency.value" :value="currency.value">
                                            {{ currency.label }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Persoane de Contact Section (doar pentru companii) -->
                    <div v-show="activeTab === 'contacts' && isCompanyClient" class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium">Persoane de Contact</h3>
                            <Button type="button" @click="openContactDialog()" variant="outline" size="sm" class="gap-2">
                                <Plus class="h-4 w-4" />
                                Adaugă persoană
                            </Button>
                        </div>
                        
                        <div v-if="contactPersons.length > 0" class="rounded-md border">
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
                                            <div class="flex gap-2 justify-end">
                                                <Button 
                                                    type="button"
                                                    @click="openContactDialog(contact.id)" 
                                                    variant="outline" 
                                                    size="sm"
                                                >
                                                    <Edit class="h-4 w-4" />
                                                </Button>
                                                <Button 
                                                    type="button"
                                                    @click="handleDeleteContact(contact.id)" 
                                                    variant="outline" 
                                                    size="sm"
                                                    class="text-red-600 hover:text-red-600"
                                                >
                                                    <Trash2 class="h-4 w-4" />
                                                </Button>
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>
                        
                        <div v-else class="text-center py-8 text-muted-foreground">
                            <p>Nu au fost adăugate persoane de contact.</p>
                            <Button type="button" @click="openContactDialog()" variant="outline" class="mt-2 gap-2">
                                <Plus class="h-4 w-4" />
                                Adaugă prima persoană de contact
                            </Button>
                        </div>
                    </div>

                    <DialogFooter>
                        <Button type="button" variant="outline" @click="isAddClientDialogOpen = false">
                            Anulează
                        </Button>
                        <Button type="submit" :disabled="form.processing">
                            Salvează Client
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <!-- Contact Person Dialog -->
        <Dialog v-model:open="isContactDialogOpen">
            <DialogContent class="sm:max-w-[425px]">
                <DialogHeader>
                    <DialogTitle>
                        {{ isEditingContact ? 'Editare Persoană de Contact' : 'Adăugare Persoană de Contact' }}
                    </DialogTitle>
                    <DialogDescription>
                        Completați informațiile persoanei de contact mai jos.
                    </DialogDescription>
                </DialogHeader>
                <form @submit.prevent="handleSaveContact" class="space-y-4">
                    <div class="grid gap-4 py-4">
                        <!-- Nume -->
                        <div class="grid grid-cols-4 items-center gap-4">
                            <Label for="contactName" class="text-right">
                                Nume
                            </Label>
                            <Input 
                                id="contactName" 
                                v-model="contactForm.name" 
                                class="col-span-3" 
                                placeholder="Introduceți numele"
                                required
                            />
                        </div>

                        <!-- Email -->
                        <div class="grid grid-cols-4 items-center gap-4">
                            <Label for="contactEmail" class="text-right">
                                Email
                            </Label>
                            <Input 
                                id="contactEmail" 
                                type="email"
                                v-model="contactForm.email" 
                                class="col-span-3" 
                                placeholder="Introduceți email-ul"
                            />
                        </div>

                        <!-- Număr de telefon -->
                        <div class="grid grid-cols-4 items-center gap-4">
                            <Label for="contactPhone" class="text-right">
                                Telefon
                            </Label>
                            <Input 
                                id="contactPhone" 
                                v-model="contactForm.phone" 
                                class="col-span-3" 
                                placeholder="Introduceți numărul de telefon"
                            />
                        </div>
                    </div>

                    <DialogFooter>
                        <Button type="button" variant="outline" @click="isContactDialogOpen = false">
                            Anulează
                        </Button>
                        <Button type="submit" :disabled="contactForm.processing">
                            {{ isEditingContact ? 'Actualizează' : 'Adaugă' }}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
