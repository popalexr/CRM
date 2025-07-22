import { useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import type { ProductFormData, Product, ProductFormConfig } from '@/types/products';

export function useProductForm(product?: Product, mode: 'create' | 'edit' = 'edit') {
    const page = usePage();
    const formConfig = page.props.formConfig as ProductFormConfig;

    const getFormData = (): ProductFormData => {
        if (formConfig?.form_fields?.[mode]) {
            const configFields = formConfig.form_fields[mode];
            
            if (mode === 'create') {
                return {
                    id: configFields.id || null,
                    name: configFields.name || '',
                    type: configFields.type || '',
                    price: configFields.price || '',
                    unit: configFields.unit || '',
                    currency: configFields.currency || '',
                    quantity: configFields.quantity || '',
                    image_file_id: configFields.image_file_id || '',
                    description: configFields.description || '',
                };
            }

            return {
                id: product?.id || null,
                name: product?.name || '',
                type: product?.type || '',
                price: product?.price || '',
                unit: product?.unit || '',
                currency: product?.currency || '',
                quantity: product?.quantity || '',
                image_file_id: '',
                description: product?.description || '',
            };
        }

        if (mode === 'create') {
            return {
                id: null,
                name: '',
                type: '',
                price: '',
                unit: '',
                currency: '',
                quantity: '',
                image_file_id: '',
                description: '',
            };
        }

        return {
            id: product?.id || null,
            name: product?.name || '',
            type: product?.type || '',
            price: product?.price || '',
            unit: product?.unit || '',
            currency: product?.currency || '',
            quantity: product?.quantity || '',
            image_file_id: '',
            description: product?.description || '',
        };
    };

    const form = useForm<ProductFormData>(getFormData());

    const isProductType = computed(() => form.type === 'product');
    const showQuantityField = computed(() => isProductType.value);

    const formFields = computed({
        get: () => ({
            name: (form.name || '') as string,
            type: (form.type || '') as string,
            price: (form.price || '') as string | number,
            unit: (form.unit || '') as string,
            currency: (form.currency || '') as string,
            quantity: (form.quantity || '') as string | number,
            description: (form.description || '') as string,
        }),
        set: (value) => {
        }
    });

    const nameField = computed({
        get: () => (form.name || '') as string,
        set: (value: string) => { form.name = value; }
    });

    const typeField = computed({
        get: () => (form.type || '') as string,
        set: (value: string) => { form.type = value; }
    });

    const priceField = computed({
        get: () => (form.price || '') as string | number,
        set: (value: string | number) => { form.price = value; }
    });

    const unitField = computed({
        get: () => (form.unit || '') as string,
        set: (value: string) => { form.unit = value; }
    });

    const currencyField = computed({
        get: () => (form.currency || '') as string,
        set: (value: string) => { form.currency = value; }
    });

    const quantityField = computed({
        get: () => (form.quantity || '') as string | number,
        set: (value: string | number) => { form.quantity = value; }
    });

    const descriptionField = computed({
        get: () => (form.description || '') as string,
        set: (value: string) => { form.description = value; }
    });

    const handleTypeChange = (value: string) => {
        form.type = value;
        if (value === 'service') {
            form.quantity = '';
        }
    };

    const getLabel = (field: string): string => {
        return formConfig?.form_labels?.labels?.[field] || field.charAt(0).toUpperCase() + field.slice(1);
    };

    const getPlaceholder = (field: string): string => {
        return formConfig?.form_labels?.placeholders?.[field] || '';
    };

    const getButtonText = (action: string): string => {
        return formConfig?.form_labels?.buttons?.[action] || action.charAt(0).toUpperCase() + action.slice(1);
    };

    const getProductTypes = () => {
        return formConfig?.product_types || { product: 'Product', service: 'Service' };
    };

    return {
        form,
        isProductType,
        showQuantityField,
        handleTypeChange,
        getLabel,
        getPlaceholder,
        getButtonText,
        getProductTypes,
        formConfig,
    };
}