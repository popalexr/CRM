export interface ProductCreateProps {
    formConfig?: any;
    productTypes?: Record<string, string>;
    formLabels?: any;
}

export interface ProductFormData {
    name: string;
    type: string;
    price: string;
    unit: string;
    currency: string;
    quantity: string;
    image_file_id: string;
    description: string;
    [key: string]: any;
}

export declare const DEFAULT_PRODUCT_TYPES: Record<string, never>;
export declare const DEFAULT_FORM_TABS: Record<string, never>;
export declare const DEFAULT_BREADCRUMBS: never[];

export declare const DEFAULT_PRODUCT_FORM_DATA: {
    readonly name: '';
    readonly type: '';
    readonly price: '';
    readonly unit: '';
    readonly currency: '';
    readonly quantity: '';
    readonly image_file_id: '';
    readonly description: '';
};