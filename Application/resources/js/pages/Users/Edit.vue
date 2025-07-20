<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type User, type Permission, type UserEditProps, type FormDataStructure } from '@/types';
import { Head, useForm, router, usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Plus } from 'lucide-vue-next';
import { computed } from 'vue';
import InputError from '@/components/InputError.vue';
import Dropzone from '@/components/Dropzone.vue';

interface Props extends UserEditProps {
    formData: FormDataStructure;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: route('users.index'),
    },
    {
        title: 'Edit User',
        href: route('users.form', { id: props.user.id }),
    },
];

const page = usePage();
const csrf_token = computed(() => page.props.csrf);

const form = useForm({
    id: props.user.id,
    name: props.user.name || '',
    email: props.user.email || '',
    phone: props.user.phone || '',
    birth_date: props.user.birth_date || '',
    address: props.user.address || '',
    city: props.user.city || '',
    county: props.user.county || '',
    country: props.user.country || '',
    avatar_file_id: '',
    permissions: [...(props.user.permissions || [])], // Create a copy of the array
    notes: props.user.notes || '',
});

const groupedPermissions = computed(() => {
    const groups: Record<string, Permission[]> = {};
    props.availablePermissions.forEach(permission => {
        if (!groups[permission.category]) {
            groups[permission.category] = [];
        }
        groups[permission.category].push(permission);
    });
    return groups;
});

const handleSubmit = () => {
    form.post(route('users.form.post'), {
        onSuccess: () => {
            router.visit(route('users.index'));
        },
        onError: (errors) => {
            console.error('Form submission errors:', errors);
        }
    });
};

const fileUploadSuccess = (response: any) => {
    const responseData = response.response || response;
    
    if (responseData && responseData.file_id) {
        form.avatar_file_id = responseData.file_id;
    }
};

const fileUploadError = (payload: { errorMessage: any }) => {
    console.error('File upload error:', payload.errorMessage);
};

const uploadedFileRemoved = () => {
    form.avatar_file_id = '';
};

const togglePermission = (checked: string | boolean, permissionId: string) => {
    const isChecked = Boolean(checked);
    const currentPermissions = [...form.permissions]; // Create a copy of current permissions
    
    if (isChecked && !currentPermissions.includes(permissionId)) { 
        // If the checkbox is checked and permission is not already in the list
        currentPermissions.push(permissionId);
        form.permissions = currentPermissions;
    } else if (!isChecked && currentPermissions.includes(permissionId)) {
        // If the checkbox is unchecked and permission exists, remove it
        form.permissions = currentPermissions.filter(id => id !== permissionId);
    }
};
</script>

<template>
    <Head title="Edit User" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight">{{ props.formData.labels.edit_user }}</h1>
                </div>
            </div>

            <form @submit.prevent="handleSubmit">
                <Tabs default-value="details">
                    <TabsList class="mb-2">
                        <TabsTrigger value="details">{{ props.formData.tabs.details.label }}</TabsTrigger>
                        <TabsTrigger value="avatar">{{ props.formData.tabs.avatar.label }}</TabsTrigger>
                        <TabsTrigger value="permissions">{{ props.formData.tabs.permissions.label }}</TabsTrigger>
                        <TabsTrigger value="notes">{{ props.formData.tabs.notes?.label || 'Notes' }}</TabsTrigger>
                    </TabsList>

                    <TabsContent value="details">
                        <Card>
                            <CardHeader>
                                <CardTitle>{{ props.formData.tabs.details.title }}</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="name">{{ props.formData.labels.name }}</Label>
                                            <InputError :message="form.errors.name" />
                                        </div>
                                        <Input 
                                            id="name" 
                                            v-model="form.name" 
                                            :placeholder="props.formData.placeholders.name"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="email">{{ props.formData.labels.email }}</Label>
                                            <InputError :message="form.errors.email" />
                                        </div>
                                        <Input 
                                            id="email" 
                                            v-model="form.email" 
                                            type="email" 
                                            :placeholder="props.formData.placeholders.email"
                                        />
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="phone">{{ props.formData.labels.phone }}</Label>
                                            <InputError :message="form.errors.phone" />
                                        </div>
                                        <Input 
                                            id="phone" 
                                            v-model="form.phone" 
                                            type="tel" 
                                            :placeholder="props.formData.placeholders.phone"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="birth_date">{{ props.formData.labels.birth_date }}</Label>
                                            <InputError :message="form.errors.birth_date" />
                                        </div>
                                        <Input 
                                            id="birth_date" 
                                            v-model="form.birth_date" 
                                            type="date"
                                        />
                                    </div>
                                </div>
                                <div class="mt-6 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <Label for="address">{{ props.formData.labels.address }}</Label>
                                        <InputError :message="form.errors.address" />
                                    </div>
                                    <Input
                                        id="address" 
                                        v-model="form.address" 
                                        :placeholder="props.formData.placeholders.address"
                                    />
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="city">{{ props.formData.labels.city }}</Label>
                                            <InputError :message="form.errors.city" />
                                        </div>
                                        <Input 
                                            id="city" 
                                            v-model="form.city" 
                                            :placeholder="props.formData.placeholders.city"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="county">{{ props.formData.labels.county }}</Label>
                                            <InputError :message="form.errors.county" />
                                        </div>
                                        <Input 
                                            id="county" 
                                            v-model="form.county" 
                                            :placeholder="props.formData.placeholders.county"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <Label for="country">{{ props.formData.labels.country }}</Label>
                                            <InputError :message="form.errors.country" />
                                        </div>
                                        <Input 
                                            id="country" 
                                            v-model="form.country" 
                                            :placeholder="props.formData.placeholders.country"
                                        />
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </TabsContent>

                    <TabsContent value="avatar" force-mount class="data-[state=inactive]:hidden">
                        <Card>
                            <CardHeader>
                                <CardTitle>{{ props.formData.tabs.avatar.title }}</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <InputError :message="form.errors.avatar_file_id" />
                                <!-- Show current avatar if exists -->
                                <div v-if="props.user.avatar && !form.avatar_file_id" class="mb-4">
                                    <p class="text-sm text-muted-foreground mb-2">Current Avatar:</p>
                                    <img 
                                        :src="props.user.avatar" 
                                        :alt="props.user.name"
                                        class="w-20 h-20 rounded-full object-cover border"
                                    />
                                </div>
                                <Dropzone 
                                    :url="route('upload.image')"
                                    @success="fileUploadSuccess"
                                    @error="fileUploadError"
                                    @removed="uploadedFileRemoved"
                                    :headers="{'X-CSRF-TOKEN': csrf_token as string }"
                                    accepted-files="image/jpeg,image/png,image/jpg,image/bmp"
                                />
                            </CardContent>
                        </Card>
                    </TabsContent>

                    <TabsContent value="permissions">
                        <Card>
                            <CardHeader>
                                <CardTitle>{{ props.formData.tabs.permissions.title }}</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="space-y-6">
                                    <div 
                                        v-for="(permissions, category) in groupedPermissions" 
                                        :key="category"
                                        class="space-y-3"
                                    >
                                        <h4 class="text-sm font-medium text-muted-foreground">{{ category }}</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pl-4">
                                            <div 
                                                v-for="permission in permissions" 
                                                :key="permission.id"
                                                class="flex items-center space-x-2"
                                            >
                                                <Checkbox 
                                                    :id="permission.id"
                                                    :model-value="form.permissions.includes(permission.id)"
                                                    @update:model-value="(value) => togglePermission(value, permission.id)"
                                                />
                                                <Label 
                                                    :for="permission.id"
                                                    class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                                >
                                                    {{ permission.label }}
                                                </Label>
                                            </div>
                                        </div>
                                    </div>
                                    <InputError :message="form.errors.permissions" />
                                </div>
                            </CardContent>
                        </Card>
                    </TabsContent>

                    <TabsContent value="notes">
                        <Card>
                            <CardHeader>
                                <CardTitle>{{ props.formData.tabs.notes?.title || 'Notes' }}</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <InputError :message="form.errors.notes" />
                                <Textarea 
                                    v-model="form.notes" 
                                    :placeholder="props.formData.placeholders.add_notes || 'Add notes...'"
                                    class="h-32"
                                />
                            </CardContent>
                        </Card>
                    </TabsContent>
                </Tabs>

                <div class="mt-6 flex justify-end">
                    <Button 
                        type="submit" 
                        class="gap-2" 
                        :disabled="form.processing"
                    >
                        <Plus class="h-4 w-4" />
                        {{ form.processing ? 'Updating...' : props.formData.buttons.update }}
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
