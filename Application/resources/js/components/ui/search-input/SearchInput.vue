<template>
    <div class="relative">
        <Input 
            :placeholder="placeholder"
            :model-value="modelValue"
            @update:model-value="(value) => $emit('update:modelValue', String(value))"
            class="pl-10"
        />
        <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Input } from '@/components/ui/input';

interface Props {
    modelValue?: string;
    placeholder?: string;
}

withDefaults(defineProps<Props>(), {
    placeholder: 'Search...',
    modelValue: ''
});

defineEmits<{
    'update:modelValue': [value: string];
}>();
</script>
