<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { type User, type UserIndexProps } from '@/types/user';
import { Head, router, usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
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
import { Edit, Eye, MoreHorizontal, Plus, Trash2 } from 'lucide-vue-next';
import { ref, computed } from 'vue';
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import { hasPermission } from '@/composables/hasPermission';

interface Props extends UserIndexProps {
    users: User[];
    formData?: {
        labels: any;
        buttons: any;
        messages: any;
        placeholders: any;
        tabs: any;
        config: any;
    };
}

const props = defineProps<Props>();
const page = usePage();

const users = ref(props.users);
const showDeleteDialog = ref(false);
const userToDelete = ref<User | null>(null);

const formLabels = props.formData?.labels || {};
const formButtons = props.formData?.buttons || {};
const formMessages = props.formData?.messages || {};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: formLabels.create_user || 'Users',
        href: route('users.index'),
    },
];

const getUserRole = (user: User) => {
    if (user.is_admin) {
        return 'Administrator';
    }
    if (user.permissions && user.permissions.length > 0) {
        return `${user.permissions.length} permissions`;
    }
    return 'No permissions';
};

const handleView = (userId: number) => {
    router.visit(route('users.show', userId));
};

const handleEdit = (userId: number) => {
    if (!hasPermission('users-form')) {
        return;
    }
    router.visit(route('users.form', { id: userId }));
};

const handleDeleteRequest = (user: User) => {
    if (!hasPermission('users-delete')) {
        return;
    }
    userToDelete.value = user;
    showDeleteDialog.value = true;
};

const handleDeleteConfirm = () => {
    if (!userToDelete.value) return;
    
    router.delete(route('users.destroy'), {
        data: { id: userToDelete.value.id },
        onSuccess: () => {
            users.value = users.value.filter(user => user.id !== userToDelete.value!.id);
            userToDelete.value = null;
        },
        onError: (errors) => {
            console.error('Delete user error:', errors);
            userToDelete.value = null;
        }
    });
};

const handleDeleteCancel = () => {
    userToDelete.value = null;
    showDeleteDialog.value = false;
};

const handleAddUser = () => {
    if (!hasPermission('users-form')) {
        return;
    }
    router.visit(route('users.form'));
};
</script>

<template>
    <Head :title="formLabels.create_user || 'Users'" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight">{{ formLabels.create_user || 'Users' }}</h1>
                    <p class="text-muted-foreground">Manage system users and their permissions</p>
                </div>
                <Button @click="handleAddUser" class="gap-2" v-if="hasPermission('users-form')">
                    <Plus class="h-4 w-4" />
                    {{ formButtons.add_new || 'Add New User' }}
                </Button>
            </div>

            <div class="rounded-md border" v-if="users.length > 0">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>{{ formLabels.name || 'Name' }}</TableHead>
                            <TableHead>{{ formLabels.email || 'Email Address' }}</TableHead>
                            <TableHead>Rol</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="user in users" :key="user.id" @click="handleView(user.id)" class="cursor-pointer">
                            <TableCell>
                                <div class="flex items-center gap-3">
                                    <Avatar class="overflow-hidden aspect-square rounded-full h-10 w-10">
                                        <AvatarImage 
                                            v-if="user.avatar"
                                            :src="user.avatar"
                                            :alt="user.name"
                                        />
                                        <AvatarFallback class="text-sm font-medium">
                                            {{ user.name.charAt(0).toUpperCase() }}
                                        </AvatarFallback>
                                    </Avatar>
                                    <div>
                                        <div class="font-medium">{{ user.name }}</div>
                                        <div class="text-sm text-muted-foreground" v-if="user.phone">{{ user.phone }}</div>
                                    </div>
                                </div>
                            </TableCell>
                            <TableCell class="text-muted-foreground">
                                {{ user.email }}
                            </TableCell>
                            <TableCell>
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full"
                                    :class="user.is_admin ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'"
                                > 
                                    {{ getUserRole(user) }}
                                </span>
                            </TableCell>
                            <TableCell class="text-right" @click.stop>
                                <DropdownMenu>
                                    <DropdownMenuTrigger as-child>
                                        <Button variant="ghost" size="sm" class="h-8 w-8 p-0">
                                            <MoreHorizontal class="h-4 w-4" />
                                            <span class="sr-only">Open menu</span>
                                        </Button>
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent align="end">
                                        <DropdownMenuItem @click="handleView(user.id)" v-if="hasPermission('users-view')">
                                            <Eye class="mr-2 h-4 w-4" />
                                            {{ formButtons.view || 'View' }}
                                        </DropdownMenuItem>
                                        <DropdownMenuItem @click="handleEdit(user.id)" v-if="hasPermission('users-form')">
                                            <Edit class="mr-2 h-4 w-4" />
                                            {{ formButtons.edit || 'Edit' }}
                                        </DropdownMenuItem>
                                        <DropdownMenuItem  v-if="hasPermission('users-delete')"
                                            @click="handleDeleteRequest(user)"
                                            class="text-red-600 focus:text-red-600"
                                        >
                                            <Trash2 class="mr-2 h-4 w-4" />
                                            {{ formButtons.delete || 'Delete' }}
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
                    <h3 class="text-lg font-medium text-gray-900">No users found</h3>
                    <p class="mt-1 text-sm text-gray-500" v-if="hasPermission('users-form')">Get started by creating your first user</p>
                    <Button @click="handleAddUser" class="mt-4 gap-2" v-if="hasPermission('users-form')">
                        <Plus class="h-4 w-4" />
                        {{ formButtons.add_new || 'Add New User' }}
                    </Button>
                </div>
            </div>
        </div>

        <ConfirmDialog
            v-model:open="showDeleteDialog"
            :title="formMessages.delete_dialog?.title || 'Delete User'"
            :description="formMessages.delete_dialog?.description?.replace(':name', userToDelete?.name || '') || `Are you sure you want to delete the user '${userToDelete?.name}'? This action cannot be undone.`"
            :confirm-text="formMessages.delete_dialog?.confirm || 'Delete'"
            :cancel-text="formMessages.delete_dialog?.cancel || 'Cancel'"
            variant="destructive"
            @confirm="handleDeleteConfirm"
            @cancel="handleDeleteCancel"
        />
    </AppLayout>
</template>