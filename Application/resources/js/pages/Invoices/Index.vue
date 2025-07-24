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

// Tipurile venite din backend
interface Invoice {
  id: number
  original_invoice_id?: number | null // pentru facturi storno
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

const getInitial = (name: string) => name.charAt(0).toUpperCase()

const isOverdue = (dateString: string) => {
  return new Date(dateString) < new Date()
}

// Funcție pentru returnarea variantei de culoare în funcție de status
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
  <Head title="Facturi" />
  <AppLayout>
    <div class="p-6 space-y-6">
      <h1 class="text-2xl font-bold">Facturi</h1>

      <!-- Când NU există facturi -->
      <div v-if="invoices.length === 0" class="text-center py-12">
        <div class="max-w-md mx-auto space-y-4">
          <h3 class="text-lg font-semibold">Nu există facturi</h3>
          <p class="text-muted-foreground">
            Creează prima ta factură pentru a începe.
          </p>
          <Button @click="router.visit(route('invoices.form'))">
            Adaugă factură
          </Button>
        </div>
      </div>

      <!-- Când EXISTĂ facturi -->
      <div v-else class="overflow-x-auto">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>Factura</TableHead>
              <TableHead>Client</TableHead>
              <TableHead>User</TableHead>
              <TableHead>Valoare</TableHead>
              <TableHead>Status</TableHead>
              <TableHead>Termen plată</TableHead>
              <TableHead class="text-right">Acțiuni</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="invoice in invoices" :key="invoice.id">
              <!-- Factura -->
              <TableCell>
                <span v-if="invoice.original_invoice_id">
                  #{{ invoice.id }} → #{{ invoice.original_invoice_id }}
                </span>
                <span v-else>#{{ invoice.id }}</span>
              </TableCell>

              <!-- Client -->
              <TableCell class="flex items-center gap-2">
                <Avatar class="h-8 w-8">
                  <AvatarImage v-if="invoice.client.image" :src="invoice.client.image" />
                  <AvatarFallback>{{ getInitial(invoice.client.name) }}</AvatarFallback>
                </Avatar>
                <span>{{ invoice.client.name }}</span>
              </TableCell>

              <!-- User -->
              <TableCell class="flex items-center gap-2">
                <Avatar class="h-8 w-8">
                  <AvatarImage v-if="invoice.user.image" :src="invoice.user.image" />
                  <AvatarFallback>{{ getInitial(invoice.user.name) }}</AvatarFallback>
                </Avatar>
                <span>{{ invoice.user.name }}</span>
              </TableCell>

              <!-- Valoare -->
              <TableCell>
                <span v-if="invoice.total !== null">
                  {{ invoice.total }} {{ invoice.currency }}
                </span>
                <span v-else class="text-gray-400 italic">Calculating...</span>
              </TableCell>

              <!-- Status -->
              <TableCell>
                <Badge :variant="getStatusBadgeVariant(invoice.status)">
                  {{ invoice.status }}
                </Badge>
              </TableCell>

              <!-- Termen plată -->
              <TableCell>
                <Badge v-if="isOverdue(invoice.payment_deadline)" variant="destructive">
                  {{ invoice.payment_deadline }}
                </Badge>
                <span v-else>
                  {{ invoice.payment_deadline }}
                </span>
              </TableCell>

              <!-- Acțiuni -->
              <TableCell class="text-right">
                <Button size="sm" variant="outline">Vezi</Button>
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </div>
    </div>
  </AppLayout>
</template>
