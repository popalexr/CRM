<template>
    <Card>
        <CardHeader v-if="showHeader">
            <div class="flex justify-between items-center">
                <span class="text-sm font-medium">{{ headers.main }}</span>
                <span class="text-sm font-medium">{{ headers.secondary }}</span>
            </div>
        </CardHeader>
        <CardContent>
            <div :class="items.length > 3 ? 'max-h-80 overflow-y-auto' : ''">
                <Accordion type="multiple" class="w-full space-y-3">
                    <AccordionItem 
                        v-for="item in items" 
                        :key="item.id" 
                        :value="item.id.toString()"
                        class="border-0 border-b-0"
                    >
                        <AccordionTrigger class="flex items-center space-x-3 p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors hover:no-underline">
                            <Avatar class="overflow-hidden aspect-square rounded-full h-10 w-10">
                                <AvatarImage 
                                    v-if="item.avatar"
                                    :src="item.avatar"
                                    :alt="item.title"
                                />
                                <AvatarFallback class="text-sm font-medium">
                                    {{ item.title.charAt(0).toUpperCase() }}
                                </AvatarFallback>
                            </Avatar>
                            <div class="flex-1 text-left">
                                <h4 class="font-medium text-sm text-gray-900 dark:text-white">{{ item.title }}</h4>
                                <div class="flex flex-col gap-0.5">
                                    <span v-if="item.contact_email" class="text-xs text-gray-500 dark:text-gray-400">{{ item.contact_email }}</span>
                                </div>
                            </div>
                        </AccordionTrigger>
                        <AccordionContent class="pt-2 pb-2">
                            <div class="pr-4 mt-1 border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-white dark:bg-black">
                                <div class="space-y-3">
                                    <div v-if="item.contact_email" class="flex items-start space-x-3">
                                        <div class="w-4 h-4 mt-0.5 flex items-center justify-center">
                                            <svg class="w-3.5 h-3.5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <span class="text-xs font-medium text-gray-900 dark:text-white">Email</span>
                                            <p class="text-xs text-gray-700 dark:text-gray-300 mt-0.5 break-all">{{ item.contact_email }}</p>
                                        </div>
                                    </div>
                                    
                                    <div v-if="item.contact_phone" class="flex items-start space-x-3">
                                        <div class="w-4 h-4 mt-0.5 flex items-center justify-center">
                                            <svg class="w-3.5 h-3.5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <span class="text-xs font-medium text-gray-900 dark:text-white">Phone</span>
                                            <p class="text-xs text-gray-700 dark:text-gray-300 mt-0.5">{{ item.contact_phone }}</p>
                                        </div>
                                    </div>
                                    
                                    <div v-if="item.contact_position" class="flex items-start space-x-3">
                                        <div class="w-4 h-4 mt-0.5 flex items-center justify-center">
                                            <svg class="w-3.5 h-3.5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <span class="text-xs font-medium text-gray-900 dark:text-white">Position</span>
                                            <p class="text-xs text-gray-700 dark:text-gray-300 mt-0.5">{{ item.contact_position }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </AccordionContent>
                    </AccordionItem>
                </Accordion>
            </div>
        </CardContent>
    </Card>
</template>

<script setup lang="ts">
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from '@/components/ui/accordion';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';

interface DataItem {
    id: number;
    title: string;
    subtitle: string;
    avatar?: string;
    contact_email?: string;
    contact_phone?: string;
    contact_position?: string;
    [key: string]: any;
}

interface Headers {
    main: string;
    secondary: string;
}

interface Props {
    items: DataItem[];
    headers?: Headers;
    showHeader?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    showHeader: false,
    headers: () => ({ main: 'Item', secondary: 'Status' })
});

const formatSubtitle = (subtitle: string) => {
    try {
        const date = new Date(subtitle);
        if (!isNaN(date.getTime())) {
            return date.toLocaleDateString('en-US', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        }
    } catch {
    }
    return subtitle;
};

defineEmits<{
    selectItem: [id: number];
}>();
</script>

<style scoped>
:deep(.border-b) {
    border-bottom: none !important;
}
</style>
