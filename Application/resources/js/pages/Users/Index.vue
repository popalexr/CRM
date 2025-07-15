<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type User, type UserIndexProps, type FormDataStructure } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Edit, MoreHorizontal, Plus, Trash2 } from 'lucide-vue-next';
import { computed } from 'vue';

interface Props extends UserIndexProps {
    users: User[];
    formData?: FormDataStructure;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/users',
    },
];

const page = usePage();
const successMessage = computed(() => page.props.flash.success);
const errorMessage = computed(() => page.props.flash.error);

const handleAddUser = () => {
    router.visit(route('users.form'));
};

const handleEditUser = (userId: number) => {
    router.visit(route('users.form', { id: userId }));
};

const handleDeleteUser = (userId: number) => {
    if (confirm('delete this user?')) {
        router.delete(route('users.destroy'), {
            data: { id: userId },
            onSuccess: () => {
                console.log('User successfully deleted!!');
            },
            onError: (errors) => {
                console.error('Error deleting user:', errors);
                let errorDetails = '';
                if (typeof errors === 'object') {
                    errorDetails = Object.values(errors).flat().join(', ');
                } else if (typeof errors === 'string') {
                    errorDetails = errors;
                }
                alert('Error deleting user: ' + errorDetails);
            }
        });
    }
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('ro-RO');
};

const getPermissionCount = (permissions: string[]) => {
    return permissions ? permissions.length : 0;
};
</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight">Users</h1>
                    <p class="text-muted-foreground">Manage system users and their permissions</p>
                </div>
                <Button @click="handleAddUser" class="gap-2">
                    <Plus class="h-4 w-4" />
                    {{ props.formData?.buttons?.add_new || 'Add New User' }}
                </Button>
            </div>

            <div v-if="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ successMessage }}
            </div>
            <div v-if="errorMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ errorMessage }}
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>All Users</CardTitle>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Name</TableHead>
                                <TableHead>Email</TableHead>
                                <TableHead>Phone</TableHead>
                                <TableHead>Permissions</TableHead>
                                <TableHead>Created</TableHead>
                                <TableHead class="w-[100px]">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="user in props.users" :key="user.id">
                                <TableCell class="font-medium">
                                    {{ user.name }}
                                </TableCell>
                                <TableCell>
                                    {{ user.email }}
                                </TableCell>
                                <TableCell>
                                    {{ user.phone || '-' }}
                                </TableCell>
                                <TableCell>
                                    <Badge variant="secondary">
                                        {{ getPermissionCount(user.permissions) }} permissions
                                    </Badge>
                                </TableCell>
                                <TableCell>
                                    {{ formatDate(user.created_at) }}
                                </TableCell>
                                <TableCell>
                                    <DropdownMenu>
                                        <DropdownMenuTrigger as-child>
                                            <Button variant="ghost" class="h-8 w-8 p-0">
                                                <span class="sr-only">Open menu</span>
                                                <MoreHorizontal class="h-4 w-4" />
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent align="end">
                                            <DropdownMenuItem @click="handleEditUser(user.id)">
                                                <Edit class="mr-2 h-4 w-4" />
                                                Edit
                                            </DropdownMenuItem>
                                            <DropdownMenuItem @click="handleDeleteUser(user.id)" class="text-destructive">
                                                <Trash2 class="mr-2 h-4 w-4" />
                                                Delete
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="!props.users.length">
                                <TableCell colspan="6" class="text-center text-muted-foreground py-8">
                                    No users found. <button @click="handleAddUser" class="text-primary hover:underline">Add the first user</button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>