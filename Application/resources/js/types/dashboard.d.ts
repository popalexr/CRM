export interface DashboardProps {
    stats: StatCard[];
    topClients: InfoListItem[];
    overdueInvoices: InfoListItem[];
    revenueData: { labels: string[]; data: number[] };
    currentFilter: string;
    dashboardLang: Record<string, string>;
    paidInvoicesCount?: number;
    totalInvoicesCount?: number;
    reminderDashboard?: string | null;
    endedInvoicesCount?: number;
}