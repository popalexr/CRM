<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
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

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Clients',
        href: '/clients',
    },
];
// Test data for clients (Static data for demonstration purposes)
const clients = [
    {
        id: 1,
        logo: '/logo1.png',
        name: 'Test',
        address: 'Test',
        city: 'Test',
        county: 'Test',
        country: 'Test',
        initials: 'Te'
    }
];

const getFullAddress = (client: any) => {
    return `${client.address}, ${client.city}, ${client.county}, ${client.country}`;
};

const handleView = (clientId: number) => {
    console.log('View client:', clientId);
};

const handleEdit = (clientId: number) => {
    console.log('Edit client:', clientId);
};

const handleDelete = (clientId: number) => {
    console.log('Delete client:', clientId);
};

const handleAddClient = () => {
    console.log('Add new client');
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
            <div class="rounded-md border">
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
                                        v-if="client.logo" 
                                        :src="client.logo" 
                                        :alt="client.name" 
                                    />
                                    <AvatarFallback class="text-sm font-medium">
                                        {{ client.initials }}
                                    </AvatarFallback>
                                </Avatar>
                            </TableCell>
                            <TableCell class="font-medium">{{ client.name }}</TableCell>
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

            <!-- Empty State (if no clients) -->
            <div v-if="clients.length === 0" class="flex flex-col items-center justify-center py-16">
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
