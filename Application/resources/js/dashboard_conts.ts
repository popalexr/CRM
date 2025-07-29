export const fallback: Record<string, string> = {
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

export const chartOptions = {
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
            ticks: {
                color: document.documentElement.classList.contains('dark') ? '#fff' : '#111',
            },
        },
        y: {
            beginAtZero: true,
            grid: { color: '#d1fae5' },
            ticks: {
                color: document.documentElement.classList.contains('dark') ? '#fff' : '#111',
            },
        },
    },
};
