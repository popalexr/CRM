<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { onMounted } from 'vue';

import 'vue-sonner/style.css'
import { toast, Toaster } from 'vue-sonner';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const page = usePage();

const error = page.props.flash.error || null;
const success = page.props.flash.success || null;
const info = page.props.flash.info || null;

onMounted(() => {
    if (error) {
        toast.error(error, {
            duration: 5000
        });
    }

    if (success) {
        toast.success(success, {
            duration: 5000
        });
    }

    if (info) {
        toast.info(info, {
            duration: 5000
        });
    }
})

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
        <Toaster  />
    </AppLayout>
</template>
