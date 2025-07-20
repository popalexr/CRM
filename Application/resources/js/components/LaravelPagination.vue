<script setup lang="ts">
import {
  Pagination,
  PaginationContent,
  PaginationEllipsis,
  PaginationItem,
  PaginationNext,
  PaginationPrevious,
} from '@/components/ui/pagination'
import { router } from '@inertiajs/vue3'
import type { PaginationMeta } from '@/pagination'

const props = defineProps<{
    meta: PaginationMeta
    routeName: string
    query?: Record<string, any>
}>();

function go(page: number) {
    if (!props.meta || page < 1 || page > props.meta.last_page) {
        return;
    }    
    router.visit(
        route(props.routeName, { ...props.query, page }),
    );
}
</script>

<template>
    <Pagination
        v-if="meta && meta.total > 0"
        :items-per-page="meta.per_page"
        :total="meta.total"
        :default-page="meta.current_page"
        v-slot="{ page }"
    >
        <PaginationContent v-slot="{ items }">
            <PaginationPrevious :disabled="meta.current_page <= 1" @click="go(meta.current_page - 1)" />

            <template v-for="(item, i) in items" :key="i">
                <PaginationItem
                    v-if="item.type === 'page'"
                    :value="item.value"
                    :is-active="item.value === page"
                    @click="go(item.value)"
                >
                    {{ item.value }}
                </PaginationItem>
            </template>

            <PaginationEllipsis v-if="meta.last_page > 7" />

            <PaginationNext
                :disabled="meta.current_page >= meta.last_page"
                @click="go(meta.current_page + 1)"
            />
        </PaginationContent>
    </Pagination>
</template>