<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const appName = page.props.name || 'App Name';
</script>

<template>
    <div class="flex items-center gap-2">
        <img src="/logo.png" alt="Logo" class="size-8 rounded-md object-contain" />
        <span class="truncate leading-tight font-semibold text-sm">{{ appName }}</span>
    </div>
</template>
