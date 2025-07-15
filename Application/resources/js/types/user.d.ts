// User-related interfaces and types

export interface User {
    id: number;
    name: string;
    email: string;
    phone: string;
    birth_date: string;
    address: string;
    city: string;
    county: string;
    country: string;
    permissions: string[];
    created_at: string;
    updated_at: string;
    avatar?: string;
    is_admin?: boolean;
}

export interface Permission {
    id: string;
    label: string;
    category: string;
}

export interface UserFormData {
    id?: number;
    name: string;
    email: string;
    phone: string;
    birth_date: string;
    address: string;
    city: string;
    county: string;
    country: string;
    password: string;
    password_confirmation: string;
    avatar_file_id: string;
    permissions: string[];
}

export interface UserCreateProps {
    availablePermissions: Permission[];
}

export interface UserEditProps {
    user: User;
    availablePermissions: Permission[];
}

export interface UserIndexProps {
    users: User[];
}

export interface UserTableColumn {
    label: string;
    sortable?: boolean;
    type?: 'text' | 'badge' | 'date';
    nullable?: boolean;
    fallback?: string;
    format?: string;
    suffix?: string;
}

export interface UserFormField {
    type: string;
    label: string;
    placeholder: string;
    required: boolean;
    validation?: string;
    edit_label?: string;
    edit_placeholder?: string;
    edit_validation?: string;
    accepted_files?: string;
}

export interface UserFormTab {
    key: string;
    label: string;
    title: string;
    fields?: string[];
}

export interface FileUploadResponse {
    file_id: string;
    response?: {
        file_id: string;
    };
}

export interface FileUploadError {
    errorMessage: any;
}

export interface UserFormConstants {
    labels: Record<string, string>;
    placeholders: Record<string, string>;
    edit_labels: Record<string, string>;
    tabs: Record<string, UserFormTab>;
    buttons: Record<string, string>;
    titles: Record<string, string>;
    descriptions: Record<string, string>;
    breadcrumbs: Record<string, string>;
    messages: Record<string, string>;
    table_headers: Record<string, string>;
    actions: Record<string, string>;
    file_upload: Record<string, string>;
    validation_messages: Record<string, string>;
}

export interface FormDataStructure {
    labels: Record<string, string>;
    placeholders: Record<string, string>;
    tabs: Record<string, UserFormTab>;
    buttons: Record<string, string>;
    messages: Record<string, string>;
    config: any;
}

export type UserFormSubmitHandler = () => void;
export type UserNavigationHandler = () => void;
export type UserEditHandler = (userId: number) => void;
export type UserDeleteHandler = (userId: number) => void;
export type FileUploadSuccessHandler = (response: FileUploadResponse) => void;
export type FileUploadErrorHandler = (error: FileUploadError) => void;
export type FileUploadRemovedHandler = () => void;
export type PermissionToggleHandler = (permissionId: string) => void;

export interface UserFormValidation {
    name: string[];
    email: string[];
    phone?: string[];
    birth_date?: string[];
    address?: string[];
    city?: string[];
    county?: string[];
    country?: string[];
    password: string[];
    password_confirmation: string[];
    avatar_file_id?: string[];
    permissions?: string[];
}

export type DateFormatHandler = (dateString: string) => string;
export type PermissionCountHandler = (permissions: string[]) => number;
