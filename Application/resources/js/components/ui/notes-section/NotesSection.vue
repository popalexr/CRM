<template>
    <Card>
        <CardHeader>
            <CardTitle class="text-lg">{{ title }}</CardTitle>
        </CardHeader>
        <CardContent>
            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                {{ content || defaultText }}
            </p>
        </CardContent>
    </Card>
</template>

<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

interface Props {
    title?: string;
    content?: string;
    defaultText?: string;
}

withDefaults(defineProps<Props>(), {
    title: 'Notes',
    defaultText: 'No notes available.'
});
</script>
