<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type Client } from '@/types';
import { Head, usePage, router } from '@inertiajs/vue3';
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
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Clients',
        href: '/clients',
    },
];

const page = usePage();
const clients = ref(page.props.clients as Array<Client>);

const getFullAddress = (client: any) => {
    return `${client.address}, ${client.city}, ${client.county}, ${client.country}`;
};

const handleView = (clientId: number) => {
    router.visit(route('clients.details', { id: clientId }));
};

const handleEdit = (clientId: number) => {
    router.visit(route('clients.form', { id: clientId }));
};

const handleDelete = (clientId: number) => {
    router.post(route('clients.delete', {id: clientId}), {}, {
        onSuccess: () => {
            clients.value = clients.value.filter(client => client.id !== clientId);
        },
        onError: (errors) => {
            console.error('Eroare la ștergerea clientului:', errors);
        }
    });
};

const handleAddClient = () => {
    router.visit(route('clients.form'));
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
                            <TableHead>Company Name</TableHead>
                            <TableHead>Client Type</TableHead>
                            <TableHead>Full Address</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                            <TableHead>Status TVA</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="client in clients" :key="client.id">
                            <TableCell>
                                <div class="flex items-center gap-3">
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
                                    <div>
                                        <div class="font-medium">{{ client.client_name }}</div>
                                        <div class="text-sm text-muted-foreground">CUI: {{ client.cui }}</div>
                                    </div>
                                </div>
                            </TableCell>
                            <TableCell>{{ client.client_type }}</TableCell>
                            <TableCell class="text-muted-foreground">
                                {{ getFullAddress(client) }}
                            </TableCell>
                            <TableCell>
                              <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full"
                                    :class="client.client_tva ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-600'"
                                 > 
                                    {{ client.client_tva ? 'Plătitor TVA' : 'Neplătitor' }}
                                 </span>
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
    </AppLayout>
</template>