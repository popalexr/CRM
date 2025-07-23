<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';

interface Entity {
    id: number;
    name: string;
    email?: string;
    phone?: string;
    avatar?: string;
}

defineProps<{
    entity: Entity;
}>();

const getInitials = (name: string) => {
    if (!name) return '';
    const words = name.split(' ');
    if (words.length > 1) {
        return (words[0][0] + words[words.length - 1][0]).toUpperCase();
    }
    return name.substring(0, 2).toUpperCase();
};
</script>

<template>
    <div class="bg-white dark:bg-black rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm flex items-center space-x-6">
        <Avatar class="h-20 w-20 text-2xl">
            <AvatarImage v-if="entity.avatar" :src="entity.avatar" :alt="entity.name" />
            <AvatarFallback>{{ getInitials(entity.name) }}</AvatarFallback>
        </Avatar>
        <div class="flex-1">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ entity.name }}</h2>
            <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-2 sm:space-x-6">
                <div v-if="entity.email" class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400">
                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M3 4a2 2 0 00-2 2v1.161l8.441 4.221a1.25 1.25 0 001.118 0L19 7.162V6a2 2 0 00-2-2H3z" />
                        <path d="M19 8.839l-7.77 3.885a2.75 2.75 0 01-2.46 0L1 8.839V14a2 2 0 002 2h14a2 2 0 002-2V8.839z" />
                    </svg>
                    {{ entity.email }}
                </div>
                <div v-if="entity.phone" class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400">
                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M2 3.5A1.5 1.5 0 013.5 2h1.148a1.5 1.5 0 011.465 1.175l.716 3.223a1.5 1.5 0 01-1.052 1.767l-.933.267c-.41.117-.643.555-.48.95a11.542 11.542 0 006.254 6.254c.395.163.833-.07.95-.48l.267-.933a1.5 1.5 0 011.767-1.052l3.223.716A1.5 1.5 0 0118 15.352V16.5a1.5 1.5 0 01-1.5 1.5h-1.528a1.5 1.5 0 01-1.491-1.336l-.533-2.667a11.5 11.5 0 01-8.66-8.66L3.336 3.991A1.5 1.5 0 014.828 2.5H6.5A1.5 1.5 0 018 4v1.5a1.5 1.5 0 01-1.5 1.5H5.379a.75.75 0 00-.729.838l.533 2.667a9.5 9.5 0 007.18 7.18l2.667.533a.75.75 0 00.838-.729V14.5A1.5 1.5 0 0115 13h1.5a1.5 1.5 0 011.5 1.5v.648a1.5 1.5 0 01-1.175 1.465l-3.223.716a1.5 1.5 0 01-1.767-1.052l-.267-.933a.75.75 0 00-.95-.48 9.542 9.542 0 01-6.254-6.254.75.75 0 00-.48-.95l-.933-.267a1.5 1.5 0 01-1.052-1.767l.716-3.223A1.5 1.5 0 013.5 2H4.852A1.5 1.5 0 016.343 3.5V5.5a1.5 1.5 0 01-1.5 1.5H3.5A1.5 1.5 0 012 5.5v-2z" clip-rule="evenodd" />
                    </svg>
                    {{ entity.phone }}
                </div>
            </div>
        </div>
    </div>
</template>