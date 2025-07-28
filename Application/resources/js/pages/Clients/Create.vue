<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type ClientPageProps } from '@/types';
import { Head, useForm, router, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Textarea } from '@/components/ui/textarea';
import { 
    Select, 
    SelectContent, 
    SelectItem, 
    SelectTrigger, 
    SelectValue,
    SelectGroup
} from '@/components/ui/select';

import { ArrowLeft, Edit, MoreHorizontal, Plus, Trash2 } from 'lucide-vue-next';
import { ref, computed, onMounted } from 'vue';
import InputError from '@/components/InputError.vue';
import ContactsFormManager from '@/components/ContactsFormManager.vue';
import { Switch } from '@/components/ui/switch';
import Dropzone from '@/components/Dropzone.vue';

const props = defineProps<{
    currencies: Record<string, string>
}>();


const page = usePage<ClientPageProps>();
const formLabels = page.props.formLabels;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: formLabels.headings.clients,
        href: '/clients',
    },
    {
        title: formLabels.headings.add_client,
        href: '/clients/form',
    },
];

const isBusinessClient = ref(false);

const csrf_token = computed(() => usePage().props.csrf as string);

const form = useForm({
    client_name: '',
    client_type: '',
    email: '',
    phone: '',
    cui: '',
    registration_number: '',
    cnp: '',
    address: '',
    city: '',
    county: '',
    country: '',
    iban: '',
    swift: '',
    bank: '',
    currency: '',
    notes: '',
    client_tva: false,
    logo_file_id: '',
    contactPersons: [] as Array<{name: string, position: string, email: string, phone: string}>,
});

const handleBack = () => {
    router.visit(route('clients.index'));
};

const clientTypeChanged = (value: any) => {
    isBusinessClient.value = value === 'business';
};

const handleSubmit = () => {
    form.post(route('clients.form.post'), {
        onSuccess: () => {
            router.visit(route('clients.index'));
        },
        onError: (errors) => {
            console.error('Form submission errors:', errors);
        }
    });
};

const fileUploadSuccess = (response: any) => {
    const responseData = response.response || response;
    
    if (responseData && responseData.file_id) {
        form.logo_file_id = responseData.file_id;
    }
}

const fileUploadError = (payload: { errorMessage: any }) => {
    console.error('File upload error:', payload.errorMessage);
};

const uploadedFileRemoved = () => {
    form.logo_file_id = '';
}

</script>

<template>
    <Head :title="formLabels.headings.add_client" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="sm" @click="handleBack" class="gap-2">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight">{{ formLabels.headings.add_client }}</h1>
                    </div>
                </div>
            </div>

            <form @submit.prevent="handleSubmit">
                <Tabs default-value="general">
                    <TabsList class="mb-2">
                        <TabsTrigger value="general">{{ formLabels.tabs.general.label }}</TabsTrigger>
                        <TabsTrigger value="logo">{{ formLabels.tabs.logo.label }}</TabsTrigger>
                        <TabsTrigger value="contacts" v-show="isBusinessClient === true">{{ formLabels.tabs.contacts.label }}</TabsTrigger>
                        <TabsTrigger value="notes">{{ formLabels.tabs.notes.label }}</TabsTrigger>
                    </TabsList>

                    <TabsContent value="general">
                        <Card>
                            <CardHeader>
                                <CardTitle>{{ formLabels.tabs.general.title }}</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="name">{{ formLabels.labels.client_name }}</Label>
                                            <InputError :message="form.errors.client_name" />
                                        </div>
                                        <Input 
                                            id="name" 
                                            v-model="form.client_name" 
                                            :placeholder="formLabels.placeholders.client_name"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="client_type">{{ formLabels.labels.client_type }}</Label>
                                            <InputError :message="form.errors.client_type" />
                                        </div>
                                        <Select v-model="form.client_type" @update:model-value="clientTypeChanged" class="w-full">
                                            <SelectTrigger class="w-100">
                                                <SelectValue :placeholder="formLabels.placeholders.select_client_type" />
                                            </SelectTrigger>
                                            <SelectContent class="min-w-0 w-full">
                                                <SelectGroup>
                                                    <SelectItem value="individual">{{ formLabels.client_types.individual }}</SelectItem>
                                                    <SelectItem value="business">{{ formLabels.client_types.business }}</SelectItem>
                                                </SelectGroup>
                                            </SelectContent>
                                        </Select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6" v-if="isBusinessClient">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="cui">{{ formLabels.labels.cui }}</Label>
                                            <InputError :message="form.errors.cui" />
                                        </div>
                                        <Input 
                                            id="cui" 
                                            v-model="form.cui" 
                                            :placeholder="formLabels.placeholders.cui_optional"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="registration_number">{{ formLabels.labels.registration_number }}</Label>
                                            <InputError :message="form.errors.registration_number" />
                                        </div>
                                        <Input 
                                            id="registration_number" 
                                            v-model="form.registration_number" 
                                            :placeholder="formLabels.placeholders.registration_number_optional"
                                        />
                                    </div>
                                </div>
                                <div class="mt-6 space-y-2" v-else>
                                    <div class="flex items-center justify-between">
                                        <Label for="cnp">CNP</Label>
                                        <InputError :message="form.errors.cnp" />
                                    </div>
                                    <Input
                                        id="cnp"
                                        v-model="form.cnp"
                                        placeholder="CNP"
                                    />
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="email">{{ formLabels.labels.email }}</Label>
                                            <InputError :message="form.errors.email" />
                                        </div>
                                        <Input 
                                            id="email" 
                                            v-model="form.email" 
                                            type="email" 
                                            :placeholder="formLabels.placeholders.email_address"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="phone">{{ formLabels.labels.phone }}</Label>
                                            <InputError :message="form.errors.phone" />
                                        </div>
                                        <Input 
                                            id="phone" 
                                            v-model="form.phone" 
                                            type="tel" 
                                            :placeholder="formLabels.placeholders.phone_number"
                                        />
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="iban">{{ formLabels.labels.iban }}</Label>
                                            <InputError :message="form.errors.iban" />
                                        </div>
                                        <Input
                                            id="iban" 
                                            v-model="form.iban" 
                                            :placeholder="formLabels.placeholders.iban_optional"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="swift">{{ formLabels.labels.swift }}</Label>
                                            <InputError :message="form.errors.swift" />
                                        </div>
                                        <Input
                                            id="swift" 
                                            v-model="form.swift" 
                                            :placeholder="formLabels.placeholders.swift_optional"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="bank">{{ formLabels.labels.bank }}</Label>
                                            <InputError :message="form.errors.bank" />
                                        </div>
                                        <Input
                                            id="bank" 
                                            v-model="form.bank" 
                                            :placeholder="formLabels.placeholders.bank_name_optional"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="currency">{{ formLabels.labels.currency }}</Label>
                                            <InputError :message="form.errors.currency" />
                                        </div>
                                        <Select v-model="form.currency" class="w-full">
                                            <SelectTrigger>
                                                <SelectValue :placeholder="formLabels.placeholders.currency_example" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectGroup>
                                                    <SelectItem
                                                        v-for="(label, code) in props.currencies"
                                                        :key="code"
                                                        :value="code"
                                                    >
                                                        {{ label }} ({{ code }})
                                                    </SelectItem>
                                                </SelectGroup>
                                            </SelectContent>
                                        </Select>
                                    </div>
                                </div>
                                <div class="mt-6 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <Label for="address">{{ formLabels.labels.address }}</Label>
                                        <InputError :message="form.errors.address" />
                                    </div>
                                    <Input
                                        id="address" 
                                        v-model="form.address" 
                                        :placeholder="formLabels.placeholders.client_address"
                                    />
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="city">{{ formLabels.labels.city }}</Label>
                                            <InputError :message="form.errors.city" />
                                        </div>
                                        <Input 
                                            id="city" 
                                            v-model="form.city" 
                                            :placeholder="formLabels.placeholders.city"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="county">{{ formLabels.labels.county }}</Label>
                                            <InputError :message="form.errors.county" />
                                        </div>
                                        <Input 
                                            id="county" 
                                            v-model="form.county" 
                                            :placeholder="formLabels.placeholders.county"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="country">{{ formLabels.labels.country }}</Label>
                                            <InputError :message="form.errors.country" />
                                        </div>
                                        <Input 
                                            id="country" 
                                            v-model="form.country" 
                                            :placeholder="formLabels.placeholders.country"
                                        />
                                    </div>
                                </div>

                                <div class="mt-6 space-y-2" v-if="isBusinessClient">
                                    <Label for="client_tva">{{ formLabels.labels.client_tva }}</Label>
                                    <Switch id="client_tva" v-model="form.client_tva" />
                                    <InputError :message="form.errors.client_tva" />
                                </div>
                            </CardContent>
                        </Card>
                    </TabsContent>

                    <TabsContent value="logo" force-mount class="data-[state=inactive]:hidden">
                        <Card>
                            <CardHeader>
                                <CardTitle>{{ formLabels.tabs.logo.title }}</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <InputError :message="form.errors.logo_file_id" />
                                <Dropzone 
                                    :url="route('upload.image')"
                                    @success="fileUploadSuccess"
                                    @error="fileUploadError"
                                    @removed="uploadedFileRemoved"
                                    :headers="{'X-CSRF-TOKEN': csrf_token }"
                                    accepted-files="image/jpeg,image/png,image/jpg,image/bmp"
                                />
                            </CardContent>
                        </Card>
                    </TabsContent>

                    <TabsContent value="contacts">
                        <Card>
                            <CardHeader>
                                <CardTitle>{{ formLabels.tabs.contacts.title }}</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="max-h-[450px] overflow-y-auto pr-2">
                                    <ContactsFormManager v-model="form.contactPersons" />
                                </div>
                            </CardContent>
                        </Card>
                    </TabsContent>

                    <TabsContent value="notes">
                        <Card>
                            <CardHeader>
                                <CardTitle>{{ formLabels.tabs.notes.title }}</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <InputError :message="form.errors.notes" />
                                <Textarea 
                                    v-model="form.notes" 
                                    :placeholder="formLabels.placeholders.add_notes"
                                    class="h-32"
                                />
                            </CardContent>
                        </Card>
                    </TabsContent>
                </Tabs>

                <div class="mt-6 flex justify-end">
                    <Button type="submit" class="gap-2">
                        <Plus class="h-4 w-4" />
                        {{ formLabels.buttons.save_client }}
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>