import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

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
    notes?: string;
    created_at?: string;
    updated_at?: string;
    deleted_at?: string;
}

export interface ClientContact {
    id?: number;
    client_id: number;
    contact_name: string;
    contact_email?: string;
    contact_phone?: string;
    contact_position?: string;
    created_at?: string;
    updated_at?: string;
    deleted_at?: string;
}

export type BreadcrumbItemType = BreadcrumbItem;
