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
import type { StatCard, InfoListItem, DashboardProps, PeriodOption, BreadcrumbItem } from '@/types';
import { getPeriodOptions } from '../periodOptions';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

const props = defineProps<DashboardProps>();
const t = (key: string) => {
    const fallback: Record<string, string> = {
        'dashboard': 'Dashboard',
        'dashboard.total_clients': 'Total Clients',
        'dashboard.issued_invoices': 'Issued Invoices',
        'dashboard.overdue_invoices': 'Overdue Invoices',
        'dashboard.period_revenue': 'Period Revenue',
        'overview': 'Overview',
        'select_period': 'Select period',
        'add_client': 'Add Client',
        'add_product': 'Add Product',
        'monthly_revenue': 'Monthly Revenue',
        'revenue_evolution': 'Revenue Evolution',
        'top_clients': 'Top Clients',
        'alerts': 'Alerts',
        'no_clients': 'No clients',
        'no_overdue': 'No overdue invoices',
    };
    return props.dashboardLang[key] || fallback[key] || key;
};
const selectedPeriod = ref(props.currentFilter);
const periodOptions: PeriodOption[] = getPeriodOptions(t);

watch(() => props.currentFilter, (newVal) => {
    selectedPeriod.value = newVal;
});

watch(selectedPeriod, (newValue) => {
    if (newValue !== props.currentFilter) {
        router.get(route('home'), { filter: newValue }, {
            preserveState: false,
            preserveScroll: true,
        });
    }
});

const icons: { [key: string]: any } = { Users, Package, FileText };
const chartData = {
    labels: props.revenueData.labels,
    datasets: [{
        label: t('monthly_revenue'),
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

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: t('dashboard'),
        href: route('home'),
    },
];

const stats: StatCard[] = props.stats;
const topClients: InfoListItem[] = props.topClients;
const overdueInvoices: InfoListItem[] = props.overdueInvoices;
</script>

<template>
    <Head :title="t('dashboard')" />

    <AppLayout>
        <div class="container mx-auto max-w-6xl px-4 py-6 space-y-6 gap-x-4">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold">{{ t('dashboard') }}</h1>
                    <p class="text-muted-foreground">{{ t('overview') }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <Select v-model="selectedPeriod">
                        <SelectTrigger class="w-[180px]">
                            <SelectValue :placeholder="t('select_period')" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="option in periodOptions" :key="option.value" :value="option.value">
                                {{ option.label }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <Button as-child variant="secondary">
                        <Link :href="route('clients.form')"><Plus class="w-4 h-4 mr-2" /> {{ t('add_client') }}</Link>
                    </Button>
                    <Button as-child variant="outline">
                        <Link :href="route('products.form')"><Plus class="w-4 h-4 mr-2" /> {{ t('add_product') }}</Link>
                    </Button>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <Card v-for="stat in stats" :key="stat.title">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">{{ t(stat.title) || stat.title }}</CardTitle>
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
                        <CardTitle>{{ t('revenue_evolution') }}</CardTitle>
                    </CardHeader>
                    <CardContent class="h-[350px]">
                        <Bar :data="chartData" :options="chartOptions" />
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">{{ t('top_clients') || 'Top Clients' }}</CardTitle>
                        <TrendingUp class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent class="space-y-4 pt-4">
                        <div v-if="topClients.length > 0">
                            <div v-for="client in topClients" :key="client.name" class="flex items-center">
                                <p class="text-sm font-medium flex-1 truncate">{{ client.name }}</p>
                                <p class="text-sm text-muted-foreground">{{ client.value }}</p>
                            </div>
                        </div>
                        <p v-else class="text-sm text-center text-muted-foreground py-4">{{ t('no_clients') || 'No clients' }}</p>
                    </CardContent>
                </Card>
            </div>

            <div class="grid grid-cols-1">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">{{ t('alerts') || 'Alerts' }}</CardTitle>
                        <AlertTriangle class="h-5 w-5 text-destructive animate-pulse" />
                    </CardHeader>
                    <CardContent class="space-y-4 pt-4">
                        <div v-if="overdueInvoices.length > 0">
                            <div v-for="invoice in overdueInvoices" :key="invoice.name"
                                class="flex items-center rounded-lg border px-3 py-2 mb-2 shadow-sm border-gray-200 bg-white dark:border-gray-800 dark:bg-[rgba(0,0,0,0)]">
                                <div class="flex-1">
                                    <p class="text-base font-semibold text-gray-900 dark:text-white truncate">{{ invoice.name }}</p>
                                    <p class="text-xs text-red-600">{{ invoice.value }}</p>
                                </div>
                                <span class="ml-2 px-2 py-1 rounded bg-red-100 text-xs text-red-800 font-bold">Overdue</span>
                                <a :href="route('invoices.details', { id: invoice.id })"
                                   class="ml-4 px-3 py-1 rounded text-xs font-semibold transition bg-black text-white hover:bg-gray-900 dark:bg-white dark:text-black dark:hover:bg-gray-200"
                                >View Invoice</a>
                            </div>
                        </div>
                        <p v-else class="text-sm text-center text-muted-foreground py-4">{{ t('no_overdue') || 'No overdue invoices' }}</p>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>