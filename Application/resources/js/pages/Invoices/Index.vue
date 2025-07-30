<script setup lang="ts">
import type { BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import { Table, TableHeader, TableBody, TableHead, TableRow, TableCell } from '@/components/ui/table'
import { ContextMenu, ContextMenuContent, ContextMenuItem, ContextMenuTrigger } from '@/components/ui/context-menu'
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem } from '@/components/ui/dropdown-menu'
import { MoreHorizontal, Eye, Trash2, Edit, Replace } from 'lucide-vue-next'
import ConfirmDialog from '@/components/ConfirmDialog.vue'
import { parseDate, parseStatus, getStatusBadgeVariant } from '@/invoices_const'
import LaravelPagination from '@/components/LaravelPagination.vue';
import { Invoice } from '@/types/invoices';
import { hasPermission } from '@/composables/hasPermission';

const props = defineProps<{ invoices: Invoice[], meta: Array<any> }>()

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Invoices',
    href: route('invoices.index'),
  },
];

const meta = props.meta;

const links = ref({
    current_page: meta.current_page,
    per_page: meta.per_page,
    total: meta.total,
    last_page: meta.last_page
});

const showDeleteDialog = ref(false)
const invoiceToDelete = ref<Invoice | null>(null)

const handleDeleteRequest = (invoice: Invoice) => {
  invoiceToDelete.value = invoice
  showDeleteDialog.value = true
}

const handleView = (id: number) => {
  router.visit(route('invoices.details', { id: id }))
}

const handleDeleteConfirm = () => {
  if (!invoiceToDelete.value) return

  router.visit(route('invoices.delete', { id: invoiceToDelete.value.id }), {
    method: 'post',
  })
}

const handleDeleteCancel = () => {
  invoiceToDelete.value = null
  showDeleteDialog.value = false
}

const getInitial = (name: string) => name.charAt(0).toUpperCase()

const isOverdue = (dateString: string) => {
  return new Date(dateString) < new Date()
}

const handleStornoInvoice = (id: number) => {
  router.visit(route('invoices.storno', { id: id }), {
    method: 'post',
  })
}

</script>

<template>
  <Head title="Invoices" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6 space-y-6">
      <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Invoices</h1>
        <Button @click="router.visit(route('invoices.form'))" variant="default" v-if="hasPermission('invoices-form')">
          + Add Invoice
        </Button>
      </div>

      <div v-if="invoices.length === 0" class="text-center py-12">
        <div class="max-w-md mx-auto space-y-4">
          <h3 class="text-lg font-semibold">No invoices</h3>
          <p class="text-muted-foreground">
            Create your first invoice to get started.
          </p>
        </div>
      </div>

      <div v-else class="overflow-x-auto rounded-md border">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>Invoice</TableHead>
              <TableHead>Client</TableHead>
              <TableHead>User</TableHead>
              <TableHead>Amount</TableHead>
              <TableHead>Status</TableHead>
              <TableHead>Due Date</TableHead>
              <TableHead class="text-right">Actions</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <template v-for="invoice in invoices" :key="invoice.id">
              <ContextMenu>
                <ContextMenuTrigger as-child>
                  <TableRow @click="handleView(invoice.id)" class="cursor-pointer">
                    <TableCell>
                      <span v-if="invoice.status === 'anulled'">
                        #{{ invoice.id }} → #{{ invoice.storno.storno_invoice_id }}
                      </span>
                      <span v-else-if="invoice.status === 'storno'">
                        #{{ invoice.storno.original_invoice_id }} → #{{ invoice.id }}
                      </span>
                      <span v-else>#{{ invoice.id }}</span>
                    </TableCell>
                    <TableCell>
                      <div class="flex items-center gap-2">
                        <Avatar class="h-8 w-8">
                          <AvatarImage v-if="invoice.client.client_logo" :src="invoice.client.client_logo" />
                          <AvatarFallback>{{ getInitial(invoice.client.client_name) }}</AvatarFallback>
                        </Avatar>
                        <Link :href="route('clients.details', { id: invoice.client_id })">{{ invoice.client.client_name }}</Link>
                      </div>
                    </TableCell>
                    <TableCell>
                      <div class="flex items-center gap-2">
                        <Avatar class="h-8 w-8">
                          <AvatarImage v-if="invoice.user.avatar" :src="invoice.user.avatar" />
                          <AvatarFallback>{{ getInitial(invoice.user.name) }}</AvatarFallback>
                        </Avatar>
                        <Link :href="route('users.details', {id: invoice.created_by})">{{ invoice.user.name }}</Link>
                      </div>
                    </TableCell>
                    <TableCell>
                      <span v-if="invoice.total !== null">
                        {{ Number(invoice.total).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }} {{ invoice.currency }}
                      </span>
                      <span v-else class="text-gray-400 italic">Calculating...</span>
                    </TableCell>
                    <TableCell>
                      <Badge :class="getStatusBadgeVariant(invoice.status)">
                        {{ parseStatus(invoice.status) }}
                      </Badge>
                    </TableCell>
                    <TableCell>
                      <Badge v-if="isOverdue(invoice.payment_deadline)" variant="destructive">
                        {{ parseDate(invoice.payment_deadline) }}
                      </Badge>
                      <span v-else>
                        {{ parseDate(invoice.payment_deadline) }}
                      </span>
                    </TableCell>
                    <TableCell class="text-right" @click.stop>
                      <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                          <Button variant="ghost" size="sm" class="h-8 w-8 p-0">
                            <MoreHorizontal class="h-4 w-4" />
                            <span class="sr-only">Deschide meniu</span>
                          </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end">
                          <DropdownMenuItem @click="handleView(invoice.id)">
                            <Eye class="mr-2 h-4 w-4" />
                            View
                          </DropdownMenuItem>
                          <DropdownMenuItem @click="router.visit(route('invoices.form', { id: invoice.id }))" v-if="invoice.status === 'draft' && hasPermission('invoices-form')">
                            <Edit class="mr-2 h-4 w-4" />
                            Edit
                          </DropdownMenuItem>
                          <DropdownMenuItem @click="handleStornoInvoice(invoice.id)" v-if="!invoice.status.includes('draft', 'anulled', 'storno') && hasPermission('invoices-storno')">
                            <Replace class="mr-2 h-4 w-4" />
                            Storno
                          </DropdownMenuItem>
                          <DropdownMenuItem
                            v-if="(invoice.status === 'draft' || invoice.status === 'paid') && hasPermission('invoices-delete')"
                            @click="handleDeleteRequest(invoice)"
                            class="text-red-600 focus:text-red-600"
                          >
                            <Trash2 class="mr-2 h-4 w-4" />
                            Delete
                          </DropdownMenuItem>
                        </DropdownMenuContent>
                      </DropdownMenu>
                    </TableCell>
                  </TableRow>
                </ContextMenuTrigger>
                <ContextMenuContent>
                  <ContextMenuItem @click="handleView(invoice.id)">
                    <Eye class="mr-2 h-4 w-4" />
                    View
                  </ContextMenuItem>
                  <ContextMenuItem @click="router.visit(route('invoices.form', { id: invoice.id }))" v-if="invoice.status === 'draft' && hasPermission('invoices-form')">
                    <Edit class="mr-2 h-4 w-4" />
                    Edit
                  </ContextMenuItem>
                  <ContextMenuItem @click="handleStornoInvoice(invoice.id)" v-if="!invoice.status.includes('draft', 'anulled', 'storno') && hasPermission('invoices-storno')">
                    <Replace class="mr-2 h-4 w-4" />
                    Storno
                  </ContextMenuItem>
                  <ContextMenuItem
                    v-if="(invoice.status === 'draft' || invoice.status === 'paid') && hasPermission('invoices-delete')"
                    @click="handleDeleteRequest(invoice)"
                    class="text-red-600 focus:text-red-600"
                  >
                    <Trash2 class="mr-2 h-4 w-4" />
                    Delete
                  </ContextMenuItem>
                </ContextMenuContent>
              </ContextMenu>
            </template>
          </TableBody>
        </Table>

        <div class="mt-4 flex justify-end" v-if="links.total > links.per_page">
          <LaravelPagination
              :meta="links"
              :route-name="'invoices.index'"
          />
      </div>
      </div>
    </div>

    <ConfirmDialog
      v-if="invoiceToDelete && hasPermission('invoices-delete')"
      v-model:open="showDeleteDialog"
      title="Delete Invoice"
      :description="`Are you sure you want to delete invoice #${invoiceToDelete.id}? This action cannot be undone.`"
      confirm-text="Yes, delete"
      cancel-text="Cancel"
      variant="destructive"
      @confirm="handleDeleteConfirm"
      @cancel="handleDeleteCancel"
    />
  </AppLayout>
</template>