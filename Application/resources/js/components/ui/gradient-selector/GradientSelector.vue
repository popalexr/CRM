<template>
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" @click="handleBackdropClick">
        <div class="bg-white dark:bg-black border border-gray-200 dark:border-gray-700 rounded-2xl shadow-2xl max-w-2xl w-full mx-4 max-h-[80vh] overflow-hidden" @click.stop>
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Select Gradient</h2>
                <button 
                    @click="$emit('close')"
                    class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition-colors"
                >
                    <svg class="w-5 h-5 text-black dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="p-6 max-h-[60vh] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100 dark:scrollbar-thumb-gray-600 dark:scrollbar-track-gray-800">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                    Choose a gradient theme for your profile. Click on a gradient for live preview.
                </p>

                <!-- Gradient Options Grid -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div 
                        v-for="(gradient, index) in gradients" 
                        :key="index"
                        class="relative group cursor-pointer"
                        @click="selectGradient(index)"
                    >
                        <!-- Preview Card -->
                        <div class="relative overflow-hidden rounded-xl border-2 transition-all duration-200"
                             :class="selectedIndex === index 
                                ? 'border-blue-500 ring-2 ring-blue-500/20 shadow-lg scale-105' 
                                : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600'"
                        >
                            <!-- Gradient Background -->
                            <div :class="gradient.class" class="h-24 relative">
                                <!-- Mock Avatar -->
                                <div class="absolute -bottom-3 left-3">
                                    <div class="w-8 h-8 bg-white rounded-full shadow-md flex items-center justify-center">
                                        <span class="text-xs font-semibold text-gray-700">{{ mockUserInitial }}</span>
                                    </div>
                                </div>
                                
                                <!-- Selection Indicator -->
                                <div v-if="selectedIndex === index" 
                                     class="absolute top-2 right-2 bg-white rounded-full p-1 shadow-lg">
                                    <svg class="w-3 h-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Mock Content Area -->
                            <div class="bg-white dark:bg-gray-800 p-3 space-y-2">
                                <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded w-3/4"></div>
                                <div class="h-1.5 bg-gray-100 dark:bg-gray-600 rounded w-1/2"></div>
                            </div>
                        </div>

                        <!-- Gradient Name -->
                        <div class="mt-2 text-center">
                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                {{ gradient.name }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions - Fixed at bottom -->
            <div class="flex justify-end p-6 pt-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-black">
                <div class="flex space-x-3">
                    <button 
                        @click="$emit('close')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors"
                    >
                        Cancel
                    </button>
                    <button 
                        @click="applySelection"
                        class="px-6 py-2 bg-black dark:bg-white text-white dark:text-black text-sm font-medium rounded-lg transition-colors shadow-sm hover:bg-gray-800 dark:hover:bg-gray-200"
                    >
                        Apply
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import type { GradientOption } from '@/constants/gradients';

interface Props {
    isOpen: boolean;
    currentGradient: number;
    gradients: GradientOption[];
    userInitial?: string;
}

const props = defineProps<Props>();

const emit = defineEmits<{
    close: [];
    select: [index: number];
    preview: [index: number];
    'apply-and-close': [];
}>();

const selectedIndex = ref(props.currentGradient);

const mockUserInitial = computed(() => props.userInitial || 'U');

const selectGradient = (index: number) => {
    selectedIndex.value = index;
    emit('preview', index);
};

const applySelection = () => {
    emit('select', selectedIndex.value);
    emit('apply-and-close');
};

const handleBackdropClick = (event: MouseEvent) => {
    if (event.target === event.currentTarget) {
        emit('close');
    }
};

watch(() => props.isOpen, (isOpen: boolean) => {
    if (isOpen) {
        selectedIndex.value = props.currentGradient;
    }
});

watch(() => props.currentGradient, (newGradient: number) => {
    selectedIndex.value = newGradient;
});
</script>
