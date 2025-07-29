<script setup lang="ts">
import { ref, watch, computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Users, Package, FileText, Plus, AlertTriangle, TrendingUp, Check } from 'lucide-vue-next';
import { Line } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, PointElement, CategoryScale, LinearScale } from 'chart.js';
import type { StatCard, InfoListItem, PeriodOption, BreadcrumbItem } from '@/types';

interface DashboardProps {
    stats: StatCard[];
    topClients: InfoListItem[];
    overdueInvoices: InfoListItem[];
    revenueData: { labels: string[]; data: number[] };
    currentFilter: string;
    dashboardLang: Record<string, string>;
    paidInvoicesCount?: number;
    totalInvoicesCount?: number;
    reminderDashboard?: string | null;
}
import { getPeriodOptions } from '../periodOptions';


ChartJS.register(Title, Tooltip, Legend, LineElement, PointElement, CategoryScale, LinearScale);

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
const chartData = computed(() => ({
    labels: props.revenueData && Array.isArray(props.revenueData.labels) ? props.revenueData.labels : [],
    datasets: [
        {
            label: t('revenue_evolution'),
            data: props.revenueData && Array.isArray(props.revenueData.data) ? props.revenueData.data : [],
            fill: true,
            borderColor: '#22c55e',
            backgroundColor: 'rgba(34, 197, 94, 0.15)',
            tension: 0.4,
            pointBackgroundColor: '#22c55e',
            pointBorderColor: '#fff',
            pointRadius: 5,
            pointHoverRadius: 7,
        },
    ],
}));

watch(selectedPeriod, (newValue) => {
    if (newValue !== props.currentFilter) {
        router.get(route('home'), { filter: newValue }, {
            preserveState: false,
            preserveScroll: true,
        });
    }
});
const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false,
        },
        tooltip: {
            backgroundColor: '#111',
            titleColor: '#fff',
            bodyColor: '#fff',
        },
    },
    scales: {
        x: {
            grid: { display: false },
            ticks: { color: '#111' },
        },
        y: {
            beginAtZero: true,
            grid: { color: '#d1fae5' },
            ticks: { color: '#111' },
        },
    },
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: t('dashboard'),
        href: route('home'),
    },
];

const stats: StatCard[] = props.stats;
const projectProgress = 41;
const reminder = ref<{ title: string; time: string; finished?: boolean } | null>(props.reminderDashboard ? JSON.parse(props.reminderDashboard) : null);

function finishReminder() {
    if (reminder.value) {
        reminder.value.finished = true;
        router.post(route('dashboard.reminder'), { reminder: reminder.value }, {
            onSuccess: () => {}
        });
    }
}

function deleteReminder() {
    reminder.value = null;
    router.post(route('dashboard.reminder'), { reminder: null }, {
        onSuccess: () => {}
    });
}

function addReminder() {
    const title = prompt('Reminder title:');
    if (!title) return;
    const time = prompt('Reminder time:');
    if (!time) return;
    const newReminder = { title, time };
    reminder.value = newReminder;
    router.post(route('dashboard.reminder'), { reminder: newReminder }, {
        onSuccess: () => {
        }
    });
}
const topClients: InfoListItem[] = props.topClients;
const overdueInvoices: InfoListItem[] = props.overdueInvoices;
</script>

<template>
    <Head :title="t('dashboard')" />

    <AppLayout>
        <div class="container mx-auto max-w-6xl px-4 py-6 space-y-6 gap-x-4">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold">Dashboard</h1>
                    <p class="text-muted-foreground">Plan, prioritize, and accomplish your tasks with ease.</p>
                </div>
                <div class="flex items-center space-x-2">
                    <Select v-model="selectedPeriod">
                        <SelectTrigger class="w-[180px]">
                            <SelectValue placeholder="Select period" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="option in periodOptions" :key="option.value" :value="option.value">
                                {{ option.label }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <Button as-child variant="secondary" class="p-0 border-none shadow-none">
                        <Link :href="route('clients.form')"
                              class="!bg-black text-white dark:bg-white dark:text-black hover:!bg-gray-900 dark:hover:bg-gray-200 rounded px-4 py-2 flex items-center">
                            <Plus class="w-4 h-4 mr-2" /> Add Client
                        </Link>
                    </Button>
                    <Button as-child variant="outline">
                        <Link :href="route('products.form')"
                              class="bg-black text-white dark:bg-white dark:text-black hover:bg-gray-900 dark:hover:bg-gray-200">
                            <Plus class="w-4 h-4 mr-2" /> Add Product
                        </Link>
                    </Button>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <Card v-for="stat in stats" :key="stat.title" class="bg-gradient-to-br from-green-100 to-green-200 dark:from-green-900 dark:to-green-800">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium text-green-900 dark:text-green-200">{{ stat.title }}</CardTitle>
                        <component :is="icons[stat.icon]" class="h-4 w-4 text-green-700 dark:text-green-300" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold text-green-900 dark:text-green-200">{{ stat.value }}</div>
                    </CardContent>
                </Card>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <Card class="lg:col-span-2">
                    <CardHeader>
                        <CardTitle>Revenue Evolution</CardTitle>
                    </CardHeader>
                    <CardContent class="h-[350px]">
                        <Line :data="chartData" :options="chartOptions" />
                    </CardContent>
                </Card>

                <div class="flex flex-col gap-6">
                    <Card>
                <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                    <CardTitle class="text-sm font-medium">Invoices Payments</CardTitle>
                </CardHeader>
                <CardContent class="flex flex-col items-center justify-center h-full">
                    <div class="relative w-24 h-24 flex items-center justify-center">
                        <svg class="absolute top-0 left-0" width="96" height="96" viewBox="0 0 96 96">
                            <circle cx="48" cy="48" r="40" stroke="#d1fae5" stroke-width="10" fill="none" />
                            <circle cx="48" cy="48" r="40" :stroke="'#22c55e'" stroke-width="10" fill="none"
                                :stroke-dasharray="2 * Math.PI * 40"
                                :stroke-dashoffset="2 * Math.PI * 40 * (1 - (props.paidInvoicesCount ?? 0) / (props.totalInvoicesCount ?? 1))"
                                stroke-linecap="round"
                                style="transition: stroke-dashoffset 0.6s;" />
                        </svg>
                        <span class="absolute text-2xl font-bold text-green-700">{{ Math.round((props.paidInvoicesCount ?? 0) / (props.totalInvoicesCount ?? 1) * 100) }}%</span>
                    </div>
                    <div class="flex flex-col items-center w-full mt-2">
                        <span class="text-green-700 font-semibold">{{ props.paidInvoicesCount ?? 0 }} of {{ props.totalInvoicesCount ?? 1 }} invoices paid on time</span>
                    </div>
                </CardContent>
                    </Card>
                    <Card>
                        <CardHeader class="flex flex-row items-center justify-between">
                            <CardTitle>Reminder</CardTitle>
                            <Button variant="outline" size="sm" @click="addReminder">Add Reminder</Button>
                        </CardHeader>
                        <CardContent class="flex flex-col items-center justify-center h-full">
                            <div class="text-center">
                                <div v-if="reminder" class="mb-2">
                                    <div class="flex items-center gap-2">
                                        <div class="font-semibold text-lg text-green-700 ml-15">{{ reminder.title }}</div>
                                        <template v-if="reminder.finished">
                                            <Check class="w-5 h-5 text-green-600" />
                                        </template>
                                    </div>
                                    <div class="text-sm text-muted-foreground mb-2">Time: {{ reminder.time }}</div>
                                    <div class="flex gap-2 mt-2 justify-start">
                                        <Button variant="secondary" class="px-4" @click="finishReminder" :disabled="reminder.finished">Finish</Button>
                                        <Button variant="destructive" class="px-4" @click="deleteReminder">Delete</Button>
                                    </div>
                                </div>
                                <div v-else class="text-muted-foreground">No reminder set.</div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>

            <div class="grid grid-cols-1">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Alerts</CardTitle>
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
                        <p v-else class="text-sm text-center text-muted-foreground py-4">No overdue invoices</p>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>