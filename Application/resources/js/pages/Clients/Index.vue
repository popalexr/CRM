<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type Client, type ClientPageProps } from '@/types';
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

const page = usePage<ClientPageProps>();
const clients = ref(page.props.clients as Array<Client>);
const formLabels = page.props.formLabels;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: formLabels.headings.clients,
        href: '/clients',
    },
];

const getFullAddress = (client: any) => {
    if (!client.address)
        client.address = '-';

    if (!client.city)
        client.city = '-';

    if (!client.county)
        client.county = '-';

    if (!client.country)
        client.country = '-';
    
    return `${client.address}, ${client.city}, ${client.county}, ${client.country}`;
};

const getClientType = (client: Client) => {
    return client.client_type === 'business' ? formLabels.client_types.business : formLabels.client_types.individual;
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
            console.error('Delete client error:', errors);
        }
    });
};

const handleAddClient = () => {
    router.visit(route('clients.form'));
};
</script>

<template>
    <Head :title="formLabels.headings.clients" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
                        <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight">{{ formLabels.headings.clients }}</h1>
                    <p class="text-muted-foreground">{{ formLabels.headings.manage_clients }}</p>
                </div>
                <Button @click="handleAddClient" class="gap-2">
                    <Plus class="h-4 w-4" />
                    {{ formLabels.buttons.add_client }}
                </Button>
            </div>

            <div class="rounded-md border" v-if="clients.length > 0">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>{{ formLabels.headings.company_name }}</TableHead>
                            <TableHead>{{ formLabels.headings.client_type }}</TableHead>
                            <TableHead>{{ formLabels.headings.full_address }}</TableHead>
                            <TableHead>{{ formLabels.headings.vat_status }}</TableHead>
                            <TableHead class="text-right">{{ formLabels.headings.actions }}</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="client in clients" :key="client.id" @click="handleView(client.id)" class="cursor-pointer">
                            <TableCell>
                                <div class="flex items-center gap-3">
                                    <Avatar class="overflow-hidden aspect-square rounded-full h-10 w-10">
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
                                        <div class="text-sm text-muted-foreground">{{ formLabels.labels.cui }}: {{ client.cui }}</div>
                                    </div>
                                </div>
                            </TableCell>
                            <TableCell>{{ getClientType(client) }}</TableCell>
                            <TableCell class="text-muted-foreground">
                                {{ getFullAddress(client) }}
                            </TableCell>
                            <TableCell>
                              <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full"
                                    :class="client.client_tva ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-600'"
                                 > 
                                    {{ client.client_tva ? formLabels.status.vat_payer : formLabels.status.non_vat_payer }}
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
                                        <DropdownMenuItem @click="handleView(client.id)">
                                            <Eye class="mr-2 h-4 w-4" />
                                            {{ formLabels.buttons.view }}
                                        </DropdownMenuItem>
                                        <DropdownMenuItem @click="handleEdit(client.id)">
                                            <Edit class="mr-2 h-4 w-4" />
                                            {{ formLabels.buttons.edit }}
                                        </DropdownMenuItem>
                                        <DropdownMenuItem 
                                            @click="handleDelete(client.id)"
                                            class="text-red-600 focus:text-red-600"
                                        >
                                            <Trash2 class="mr-2 h-4 w-4" />
                                            {{ formLabels.buttons.delete }}
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
                    <h3 class="text-lg font-medium text-gray-900">{{ formLabels.headings.no_clients_found }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ formLabels.headings.get_started }}</p>
                    <Button @click="handleAddClient" class="mt-4 gap-2">
                        <Plus class="h-4 w-4" />
                        {{ formLabels.buttons.add_client }}
                    </Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>