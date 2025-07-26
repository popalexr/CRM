export interface BreadcrumbItem {
    title: string;
    href: string;
}
export interface PeriodOption {
    value: string;
    label: string;
}
export interface StatCard {
    title: string;
    value: string;
    icon: string;
}

export interface InfoListItem {
    name: string;
    value: string;
}

export interface DashboardProps {
    stats: StatCard[];
    topClients: InfoListItem[];
    overdueInvoices: InfoListItem[];
    revenueData: {
        labels: string[];
        data: number[];
    };
    currentFilter: string;
    dashboardLang: Record<string, string>;
}
