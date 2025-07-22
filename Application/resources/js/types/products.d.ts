export interface ProductFormData {
    id?: number | null;
    name: string | number;
    type: string | number;
    price: string | number;
    vat_id?: number | null;
    unit: string | number;
    currency: string | number;
    quantity: string | number;
    image_file_id: string | number;
    description: string | number;
    [key: string]: any;
}

export interface Product {
    id?: number;
    name?: string;
    type?: string;
    price?: string | number;
    vat_id?: number | null;
    unit?: string;
    currency?: string;
    quantity?: string | number;
    description?: string;
}

export interface ProductFormConfig {
    form_fields?: {
        create?: Record<string, any>;
        edit?: Record<string, any>;
    };
    form_labels?: {
        labels?: Record<string, string>;
        placeholders?: Record<string, string>;
        buttons?: Record<string, string>;
    };
    product_types?: Record<string, string>;
    form_structure?: {
        create?: any;
        edit?: any;
    };
}