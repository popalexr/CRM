import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';
import type { PaginationMeta } from '@/pagination';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Client {
    id: number;
    client_name: string;
    client_logo?: string;
    client_type: string;
    client_email?: string;
    client_phone?: string;
    cui?: string;
    registration_number?: string;
    address?: string;
    city?: string;
    county?: string;
    country?: string;
    bank_name?: string;
    iban?: string;
    swift?: string;
    vat_number?: string;
    tax_code?: string;
    currency: string;
    client_tva?: boolean;
    notes?: string;
    created_at?: string;
    updated_at?: string;
    deleted_at?: string;
}

export interface ClientContact {
    id?: number;
    client_id?: number;
    name: string;
    email?: string;
    phone?: string;
    position?: string;
    created_at?: string;
    updated_at?: string;
    deleted_at?: string;
}

export type BreadcrumbItemType = BreadcrumbItem;

export interface CompanyInfo {
    company_name: string;
    company_type: string;
    address: string;
    city: string;
    county: string;
    country: string;
    email: string;
    phone: string;
    iban: string;
    bank: string;
    swift: string;
    vat_payer: boolean;
}

export interface VatRate {
    id: string;
    name: string;
    rate: number;
}

export interface SettingsPageProps {
    companyInfo: CompanyInfo;
    vatRates: VatRate[];
    companyTypes: Array<{ value: string; label: string }>;
    formLabels: {
        placeholders: Record<string, string>;
        labels: Record<string, string>;
        buttons: Record<string, string>;
        headings: Record<string, string>;
        messages: Record<string, string>;
    };
}

export interface SettingsFormLabels {
    placeholders: Record<string, string>;
    labels: Record<string, string>;
    buttons: Record<string, string>;
    headings: Record<string, string>;
    messages: Record<string, string>;
}

export interface CompanyFormData {
    company_name: string;
    company_type: string;
    address: string;
    city: string;
    county: string;
    country: string;
    email: string;
    phone: string;
    iban: string;
    bank: string;
    swift: string;
    vat_payer: boolean;
    [key: string]: any;
}

export interface VatFormData {
    name: string;
    rate: number;
    [key: string]: any;
}

export interface ClientFormLabels {
    labels: Record<string, string>;
    placeholders: Record<string, string>;
    tabs: {
        general: { label: string; title: string };
        logo: { label: string; title: string };
        contacts: { label: string; title: string};
        notes: { label: string; title: string };
    };
    buttons: Record<string, string>;
    headings: Record<string, string>;
    messages: Record<string, string>;
    client_types: Record<string, string>;
    status: Record<string, string>;
}

export type ClientPageProps = AppPageProps<{
    formLabels: ClientFormLabels;
    clients?: Client[];
    client?: Client;
    clientContacts?: ClientContact[];
    meta?: PaginationMeta;
}>;

export * from './user';
export * from './products';
