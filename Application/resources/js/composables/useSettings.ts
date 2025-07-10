import { ref } from 'vue';
import { usePage, useForm } from '@inertiajs/vue3';
import type { 
    CompanyInfo, 
    VatRate, 
    BreadcrumbItem, 
    SettingsFormLabels 
} from '@/types';

export function useSettings() {
    const page = usePage();
    
    const companyInfo = ref(page.props.companyInfo as CompanyInfo);
    const vatRates = ref(page.props.vatRates as VatRate[]);
    const companyTypes = page.props.companyTypes as Array<{ value: string; label: string }>;
    const labels = page.props.formLabels as SettingsFormLabels;

    const companyForm = useForm({
        company_name: companyInfo.value.company_name,
        company_type: companyInfo.value.company_type,
        address: companyInfo.value.address,
        city: companyInfo.value.city,
        county: companyInfo.value.county,
        country: companyInfo.value.country,
        email: companyInfo.value.email,
        phone: companyInfo.value.phone,
        iban: companyInfo.value.iban,
        bank: companyInfo.value.bank,
        swift: companyInfo.value.swift,
        vat_payer: companyInfo.value.vat_payer,
    });

    const vatForm = useForm({
        name: '',
        rate: 0,
    });

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: labels.headings.breadcrumb_settings,
            href: '/settings',
        },
    ];

    const saveCompanyInfo = () => {
        companyForm.post(route('settings.company.update'), {
            onSuccess: () => {
                Object.assign(companyInfo.value, companyForm.data());
            },
            onError: (errors) => {
                console.error('Save failed:', errors);
            },
        });
    };

    const addVatRate = () => {
        vatForm.post(route('settings.vat.store'), {
            onSuccess: () => {
                vatForm.reset();
                window.location.reload();
            },
        });
    };

    const removeVatRate = (rateId: string) => {
        if (confirm(labels.messages.delete_vat_confirm)) {
            const deleteForm = useForm({});
            deleteForm.delete(route('settings.vat.destroy', { id: rateId }), {
                onSuccess: () => {
                    vatRates.value = vatRates.value.filter(rate => rate.id !== rateId);
                },
            });
        }
    };

    return {
        companyInfo,
        vatRates,
        companyTypes,
        labels,
        breadcrumbs,
        
        companyForm,
        vatForm,
        
        saveCompanyInfo,
        addVatRate,
        removeVatRate,
    };
}
