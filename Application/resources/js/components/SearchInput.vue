<script setup lang="ts">
defineProps<{
    title?: string;
    content?: string | null;
    defaultText?: string;
}>();
</script>

<template>
    <div class="bg-white dark:bg-black rounded-xl border border-gray-200 dark:border-gray-700 p-8 shadow-sm">
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">{{ title || 'Note' }}</h3>
        <div v-if="content" class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-300 whitespace-pre-wrap">
            {{ content }}
        </div>
        <div v-else>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ defaultText || 'Nu există note adăugate.' }}</p>
        </div>
    </div>
</template>