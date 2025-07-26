import type { PeriodOption } from '@/types';

export function getPeriodOptions(t: (key: string) => string): PeriodOption[] {
    return [
        { value: 'last_30_days', label: t('last_30_days') },
        { value: 'this_month', label: t('this_month') },
        { value: 'this_quarter', label: t('this_quarter') },
        { value: 'this_year', label: t('this_year') },
    ];
}
