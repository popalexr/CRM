<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import {
  Table,
  TableHeader,
  TableBody,
  TableHead,
  TableRow,
  TableCell
} from '@/components/ui/table'
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger
} from '@/components/ui/dropdown-menu'
import { MoreHorizontal, Eye, Trash2, Edit } from 'lucide-vue-next'
import ConfirmDialog from '@/components/ConfirmDialog.vue'

interface Invoice {
  id: number
  original_invoice_id?: number | null
  client: {
    name: string
    image?: string | null
  }
  user: {
    name: string
    image?: string | null
  }
  total?: number | null
  currency: string
  status: 'draft' | 'submitted' | 'paid' | 'not_paid' | 'anulled' | 'corrected'
  payment_deadline: string
  created_at: string
}

const props = defineProps<{ invoices: Invoice[] }>()

const showDeleteDialog = ref(false)
const invoiceToDelete = ref<Invoice | null>(null)

const handleDeleteRequest = (invoice: Invoice) => {
  invoiceToDelete.value = invoice
  showDeleteDialog.value = true
}

const handleDeleteConfirm = () => {
  if (!invoiceToDelete.value) return

  // router.post(route('invoices.delete', { id: invoiceToDelete.value.id }), {}, {
  //   preserveScroll: true,
  //   onSuccess: () => {
  //     handleDeleteCancel()
  //   },
  //   onError: () => {
  //     handleDeleteCancel()
  //   }
  // })

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

const getStatusBadgeVariant = (status: string) => {
  switch (status) {
    case 'draft':
      return 'yellow'
    case 'submitted':
      return 'orange'
    case 'paid':
      return 'green'
    case 'not_paid':
      return 'destructive'
    case 'anulled':
      return 'muted'
    case 'corrected':
      return 'outline'
    default:
      return 'default'
  }
}
</script>

<template>
  <Head title="Invoices" />
  <AppLayout>
    <div class="p-6 space-y-6">
      <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Invoices</h1>
        <Button @click="router.visit(route('invoices.form'))" variant="default">
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
            <TableRow v-for="invoice in invoices" :key="invoice.id">
              <TableCell>
                <span v-if="invoice.original_invoice_id">
                  #{{ invoice.id }} → #{{ invoice.original_invoice_id }}
                </span>
                <span v-else>#{{ invoice.id }}</span>
              </TableCell>

              <TableCell>
                <div class="flex items-center gap-2">
                  <Avatar class="h-8 w-8">
                    <AvatarImage v-if="invoice.client.client_logo" :src="invoice.client.client_logo" />
                    <AvatarFallback>{{ getInitial(invoice.client.client_name) }}</AvatarFallback>
                  </Avatar>
                  <span>{{ invoice.client.name }}</span>
                </div>
              </TableCell>

              <TableCell>
                <div class="flex items-center gap-2">
                  <Avatar class="h-8 w-8">
                    <AvatarImage v-if="invoice.user.avatar" :src="invoice.user.avatar" />
                    <AvatarFallback>{{ getInitial(invoice.user.name) }}</AvatarFallback>
                  </Avatar>
                  <span>{{ invoice.user.name }}</span>
                </div>
              </TableCell>

              <TableCell>
                <span v-if="invoice.total !== null">
                  {{ Number(invoice.total).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }} {{ invoice.currency }}
                </span>
                <span v-else class="text-gray-400 italic">Calculating...</span>
              </TableCell>

              <TableCell>
                <Badge :variant="getStatusBadgeVariant(invoice.status)">
                  {{ invoice.status }}
                </Badge>
              </TableCell>

              <TableCell>
                <Badge v-if="isOverdue(invoice.payment_deadline)" variant="destructive">
                  {{ invoice.payment_deadline }}
                </Badge>
                <span v-else>
                  {{ invoice.payment_deadline }}
                </span>
              </TableCell>

              <TableCell class="text-right">
                <DropdownMenu>
                  <DropdownMenuTrigger as-child>
                    <Button variant="ghost" size="sm" class="h-8 w-8 p-0">
                      <MoreHorizontal class="h-4 w-4" />
                      <span class="sr-only">Deschide meniu</span>
                    </Button>
                  </DropdownMenuTrigger>
                  <DropdownMenuContent align="end">
                    <DropdownMenuItem @click="router.visit(route('invoices.form', { id: invoice.id }))" v-if="invoice.status === 'draft'">
                      <Edit class="mr-2 h-4 w-4" />
                      Edit
                    </DropdownMenuItem>
                      <Eye class="mr-2 h-4 w-4" />
                      View
                    </DropdownMenuItem>
                    <DropdownMenuItem
                      v-if="invoice.status === 'draft' || invoice.status === 'paid'"
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
          </TableBody>
        </Table>
      </div>
    </div>

    <ConfirmDialog
      v-if="invoiceToDelete"
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