export interface Client {
    id: number;
    client_name: string;
    client_logo?: string;
    cui?: string;
    client_type: 'business' | 'individual';
    address?: string;
    city?: string;
    county?: string;
    country?: string;
    client_tva?: boolean;
}

export interface PageProps {
    name: string;
    quote: { message: string; author: string; };
    auth: any;
    ziggy?: any;
    sidebarOpen?: boolean;
    // Add other global props as needed
}

export interface ClientPageProps extends PageProps {
    clients: Client[];
    meta?: any;
    formLabels: any;
}
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
