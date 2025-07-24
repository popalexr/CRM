<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Users, Package, FileText, Plus } from 'lucide-vue-next';
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

interface StatCard {
    title: string;
    value: string;
    icon: string;
}

interface RecentInvoice {
    id: number;
    client_name: string;
    total: number;
    currency: string;
    status: 'paid' | 'unpaid' | 'overdue';
}

interface Props {
    stats: StatCard[];
    recentInvoices: RecentInvoice[];
    revenueData: {
        labels: string[];
        data: number[];
    };
}

const props = defineProps<Props>();

const icons: { [key: string]: any } = {
    Users,
    Package,
    FileText,
};

const chartData = {
    labels: props.revenueData.labels,
    datasets: [
        {
            label: 'Venituri Lunare',
            backgroundColor: '#3b82f6',
            data: props.revenueData.data,
            borderRadius: 4,
        },
    ],
};

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false,
        },
        tooltip: {
            callbacks: {
                label: function (context: any) {
                    let label = context.dataset.label || '';
                    if (label) {
                        label += ': ';
                    }
                    if (context.parsed.y !== null) {
                        label += new Intl.NumberFormat('ro-RO', { style: 'currency', currency: 'RON' }).format(context.parsed.y);
                    }
                    return label;
                }
            }
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            ticks: {
                callback: function(value: any) {
                    return new Intl.NumberFormat('ro-RO', { style: 'currency', currency: 'RON', minimumFractionDigits: 0 }).format(value);
                }
            }
        },
    },
};

const getStatusVariant = (status: string) => {
    if (status === 'paid') return 'success';
    if (status === 'overdue') return 'destructive';
    return 'secondary';
};

const getStatusLabel = (status: string) => {
    const labels: { [key: string]: string } = {
        paid: 'Plătită',
        unpaid: 'Neplătită',
        overdue: 'Scadentă'
    };
    return labels[status] || status;
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout>
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold">Dashboard</h1>
                    <p class="text-muted-foreground">O privire de ansamblu asupra afacerii tale.</p>
                </div>
                <div class="flex space-x-2">
                         <!-- Comentează acest buton până când ruta este gata -->
    <!-- 
    <Button as-child>
        <Link :href="route('invoices.form')"><Plus class="w-4 h-4 mr-2" /> Factură Nouă</Link>
    </Button>
    -->

                    <Button as-child variant="secondary">
                        <Link :href="route('clients.form')"><Plus class="w-4 h-4 mr-2" /> Adaugă Client</Link>
                    </Button>
                    <Button as-child variant="outline">
                        <Link :href="route('products.form')"><Plus class="w-4 h-4 mr-2" /> Adaugă Produs</Link>
                    </Button>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4 mb-6">
                <Card v-for="stat in stats" :key="stat.title">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">{{ stat.title }}</CardTitle>
                        <component :is="icons[stat.icon]" class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stat.value }}</div>
                    </CardContent>
                </Card>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <Card class="lg:col-span-2">
                    <CardHeader>
                        <CardTitle>Evoluție Venituri (ultimele 6 luni)</CardTitle>
                    </CardHeader>
                    <CardContent class="h-[350px]">
                        <Bar :data="chartData" :options="chartOptions" />
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader>
                        <CardTitle>Facturi Recente</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div v-if="recentInvoices.length > 0">
                            <div v-for="invoice in recentInvoices" :key="invoice.id" class="flex items-center">
                                <div class="flex-1">
                                    <p class="text-sm font-medium leading-none">{{ invoice.client_name }}</p>
                                    <p class="text-sm text-muted-foreground">{{ invoice.total }} {{ invoice.currency }}</p>
                                </div>
                                <Badge :variant="getStatusVariant(invoice.status)">{{ getStatusLabel(invoice.status) }}</Badge>
                            </div>
                        </div>
                        <div v-else class="text-sm text-center text-muted-foreground py-4">
                            Nu există facturi recente.
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>