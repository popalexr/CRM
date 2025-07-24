<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { LayoutGrid, Settings, Users, UserCog, Package, FileText} from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { onBeforeMount } from 'vue';
import { hasPermission } from '@/composables/hasPermission';

let mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: route('home'),
        icon: LayoutGrid,
    }
];

let footerNavItems: NavItem[] = [];

const getAllowedMainNavItems = () => {
    if (hasPermission('users-view')) {
        mainNavItems.push({
            title: 'Users',
            href: route('users.index'),
            icon: UserCog,
        });
    }

    if (hasPermission('clients-view')) {
        mainNavItems.push({
            title: 'Clients',
            href: route('clients.index'),
            icon: Users,
        });
    }

    if (hasPermission('products-view')) {
        mainNavItems.push({
            title: 'Products',
            href: route('products.index'),
            icon: Package,
        });
    }
    if (hasPermission('invoices-view')) {
    mainNavItems.push({
        title: 'Invoices',
        href: route('invoices.index'),
        icon: FileText, // vom importa iconul imediat
        });
    }
};

const getAllowedFooterNavItems = () => {
    if (hasPermission('settings')) {
        footerNavItems.push({
            title: 'Settings',
            href: route('settings.index'),
            icon: Settings,
        });
    }
};

onBeforeMount(() => {
    getAllowedMainNavItems();
    getAllowedFooterNavItems();
});
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('home')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
