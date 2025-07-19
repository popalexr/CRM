<template>
    <Card class="!rounded-2xl overflow-hidden !py-0 dark:bg-black dark:border-gray-700">
        <div class="h-40 bg-gradient-to-br from-blue-400 via-purple-400 to-pink-400 relative">
            <div class="absolute -bottom-10 left-6">
                <Avatar class="overflow-hidden aspect-square rounded-full h-20 w-20 shadow-lg">
                    <AvatarImage
                        v-if="entity.avatar"
                        :src="entity.avatar"
                        :alt="entity.name"
                    />
                    <AvatarFallback class="text-lg font-medium">
                        {{ entity.name.charAt(0).toUpperCase() }}
                    </AvatarFallback>
                </Avatar>
            </div>
        </div>
        <CardContent class="pt-12 pb-6">
            <div class="space-y-4">
                <div>
                    <h2 class="text-xl font-semibold dark:text-white">{{ entity.name }}</h2>
                </div>
                
                <div class="space-y-3 text-sm">
                    <div class="flex items-center">
                        <span class="font-medium text-gray-500 dark:text-gray-400 mr-3">Email:</span>
                        <span class="text-gray-900 dark:text-white">{{ entity.email }}</span>
                    </div>
                    <div v-if="entity.phone" class="flex items-center">
                        <span class="font-medium text-gray-500 dark:text-gray-400 mr-3">Phone:</span>
                        <span class="text-gray-900 dark:text-white">{{ entity.phone }}</span>
                    </div>
                </div>
            </div>
        </CardContent>
    </Card>
</template>

<script setup lang="ts">
import { Card, CardContent } from '@/components/ui/card';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';

interface Entity {
    id: number;
    name: string;
    email: string;
    phone?: string;
    avatar?: string;
}

interface Props {
    entity: Entity;
}

const props = defineProps<Props>();
</script>
