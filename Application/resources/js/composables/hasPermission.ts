import { usePage } from "@inertiajs/vue3";

export function hasPermission(permission: string): boolean {
    const permissions = usePage().props.permissions as Array<string> || [];
    const isAdmin = usePage().props.isAdmin as boolean || false;

    if (isAdmin) {
        return true;
    }

    return permissions.includes(permission);
};