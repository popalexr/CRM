<script setup lang="ts">
import { ref, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Users, Package, FileText, Plus, AlertTriangle, TrendingUp } from 'lucide-vue-next';
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

interface StatCard {
    title: string;
    value: string;
    icon: string;
}
interface InfoListItem {
    name: string;
    value: string;
}
interface Props {
    stats: StatCard[];
    topClients: InfoListItem[];
    overdueInvoices: InfoListItem[];
    revenueData: {
        labels: string[];
        data: number[];
    };
    currentFilter: string;
}

const props = defineProps<Props>();

const selectedPeriod = ref(props.currentFilter);
const periodOptions = [
    { value: 'last_30_days', label: 'Ultimele 30 de zile' },
    { value: 'this_month', label: 'Luna aceasta' },
    { value: 'this_quarter', label: 'Acest trimestru' },
    { value: 'this_year', label: 'Acest an' },
];

watch(selectedPeriod, (newValue) => {
    router.get(route('home'), { filter: newValue }, {
        preserveState: true,
        preserveScroll: true,
    });
});

const icons: { [key: string]: any } = { Users, Package, FileText };
const chartData = {
    labels: props.revenueData.labels,
    datasets: [{
        label: 'Venituri Lunare',
        backgroundColor: '#3b82f6',
        data: props.revenueData.data,
        borderRadius: 4,
    }],
};
const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: { y: { beginAtZero: true } },
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout>
        <div class="container mx-auto px-4 py-6 space-y-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold">Dashboard</h1>
                    <p class="text-muted-foreground">O privire de ansamblu asupra afacerii tale.</p>
                </div>
                <div class="flex items-center space-x-2">
                    <Select v-model="selectedPeriod">
                        <SelectTrigger class="w-[180px]">
                            <SelectValue placeholder="Selectează perioada" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="option in periodOptions" :key="option.value" :value="option.value">
                                {{ option.label }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
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

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <Card class="lg:col-span-2">
                    <CardHeader>
                        <CardTitle>Evoluție Venituri (ultimele 6 luni)</CardTitle>
                    </CardHeader>
                    <CardContent class="h-[350px]">
                        <Bar :data="chartData" :options="chartOptions" />
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Top Clienți</CardTitle>
                        <TrendingUp class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent class="space-y-4 pt-4">
                        <div v-if="topClients.length > 0">
                            <div v-for="client in topClients" :key="client.name" class="flex items-center">
                                <p class="text-sm font-medium flex-1 truncate">{{ client.name }}</p>
                                <p class="text-sm text-muted-foreground">{{ client.value }}</p>
                            </div>
                        </div>
                        <p v-else class="text-sm text-center text-muted-foreground py-4">Nu există date pentru perioada selectată.</p>
                    </CardContent>
                </Card>
            </div>

            <div class="grid grid-cols-1">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Alerte: Facturi Scadente</CardTitle>
                        <AlertTriangle class="h-4 w-4 text-destructive" />
                    </CardHeader>
                    <CardContent class="space-y-4 pt-4">
                        <div v-if="overdueInvoices.length > 0">
                            <div v-for="invoice in overdueInvoices" :key="invoice.name" class="flex items-center">
                                <p class="text-sm font-medium flex-1 truncate">{{ invoice.name }}</p>
                                <p class="text-sm text-red-600">{{ invoice.value }}</p>
                            </div>
                        </div>
                        <p v-else class="text-sm text-center text-muted-foreground py-4">Nicio factură scadentă. Felicitări!</p>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>