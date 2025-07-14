<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { LayoutGrid, Settings, Users } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { onBeforeMount } from 'vue';
import { hasPermission } from '@/composables/hasPermission';

let mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/',
        icon: LayoutGrid,
    }
];

let footerNavItems: NavItem[] = [];

const getAllowedMainNavItems = () => {
    if (hasPermission('clients-view')) {
        mainNavItems.push({
            title: 'Clients',
            href: route('clients.index'),
            icon: Users,
        });
    }
};

const getAllowedFooterNavItems = () => {
    if (hasPermission('settings-view')) {
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
