<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Separator } from '@/components/ui/separator';
import { computed } from 'vue';

interface UserDetails {
    id: number;
    name: string;
    email: string;
    phone?: string;
    birth_date?: string;
    address?: string;
    city?: string;
    county?: string;
    country?: string;
    avatar?: string;
    permissions?: string[];
    is_admin?: boolean;
    full_address?: string;
    // invoices?: Array<any>; // Comentat anterior, lasa comentat
    created_at: string;
}

interface Props {
    user: UserDetails;
}

const props = defineProps<Props>();

const formattedDateOfBirth = computed(() => {
    if (!props.user.birth_date) return '-';
    const date = new Date(props.user.birth_date);
    return date instanceof Date && !isNaN(date.valueOf()) ? date.toLocaleDateString('ro-RO') : '-';
});

const fullAddress = computed(() => {
    return props.user.full_address || '-';
});

const handleEditUser = (userId: number) => {
    router.visit(route('users.form', { id: userId }));
};

</script>

<template>
    <Head :title="`Detalii utilizator: ${user.name}`" />

    <AppLayout>
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold">Detalii Utilizator: {{ user.name }}</h1>
                <div class="flex space-x-2">
                    <Link :href="route('users.index')">
                        <Button variant="outline">Înapoi la lista utilizatori</Button>
                    </Link>
                    <Button @click="handleEditUser(user.id)">Editează Utilizator</Button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <Card class="lg:col-span-1">
                    <CardHeader>
                        <CardTitle>Informații Generale</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="flex flex-col items-center mb-6">
                            <Avatar class="w-24 h-24 rounded-full mb-4">
                                <AvatarImage
                                    v-if="user.avatar"
                                    :src="user.avatar"
                                    :alt="user.name"
                                />
                                <AvatarFallback class="text-3xl font-medium">
                                    {{ user.name.charAt(0).toUpperCase() }}
                                </AvatarFallback>
                            </Avatar>
                            <h2 class="text-xl font-semibold">{{ user.name }}</h2>
                            <p class="text-muted-foreground">{{ user.email }}</p>
                        </div>

                        <Separator class="my-4" />

                        <div class="space-y-2 text-sm">
                            <p v-if="user.phone"><strong>Telefon:</strong> {{ user.phone }}</p>
                            <p v-if="user.birth_date"><strong>Data Nașterii:</strong> {{ formattedDateOfBirth }}</p>
                            <p v-if="fullAddress"><strong>Adresă:</strong> {{ fullAddress }}</p>
                            <p v-if="user.is_admin"><strong>Rol:</strong> Administrator</p>
                            <p><strong>Înregistrat la:</strong> {{ new Date(user.created_at).toLocaleDateString('ro-RO') }}</p>
                        </div>
                    </CardContent>
                </Card>

                <Card class="lg:col-span-2">
                    <CardHeader>
                        <CardTitle>Permisiuni</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div v-if="user.permissions && user.permissions.length > 0">
                            <div class="flex flex-wrap gap-2">
                                <Badge v-for="permission in user.permissions" :key="permission" variant="secondary">
                                    {{ permission }}
                                </Badge>
                            </div>
                        </div>
                        <div v-else class="text-muted-foreground text-sm">
                            Acest utilizator nu are permisiuni explicite atribuite.
                        </div>
                    </CardContent>
                </Card>

                <Card class="col-span-full">
                    <CardHeader>
                        <CardTitle>Facturi Emise</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-muted-foreground text-sm">
                            Funcționalitatea de afișare a facturilor emise de utilizator va fi implementată ulterior.
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>