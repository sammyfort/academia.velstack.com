<script setup lang="ts">
import {
    Pagination,
    PaginationContent,
    PaginationItem,
    PaginationNext,
    PaginationPrevious,
    PaginationEllipsis,
} from '@/components/ui/pagination';

import { computed } from 'vue';

const props = defineProps<{
    total: number;
    perPage: number;
    currentPage: number;
    max?: number;
}>();

const emit = defineEmits(['page-change']);

const totalPages = computed(() => Math.ceil(props.total / props.perPage));
const maxVisible = computed(() => props.max ?? 5);

const visiblePages = computed(() => {
    const pages = [];
    const half = Math.floor(maxVisible.value / 2);

    let start = Math.max(props.currentPage - half, 1);
    let end = Math.min(start + maxVisible.value - 1, totalPages.value);

    if (end - start < maxVisible.value - 1) {
        start = Math.max(end - maxVisible.value + 1, 1);
    }

    for (let i = start; i <= end; i++) {
        pages.push(i);
    }

    return pages;
});

const goToPage = (page: number) => {
    if (page !== props.currentPage && page >= 1 && page <= totalPages.value) {
        emit('page-change', page);
    }
};
</script>

<template>
    <Pagination
        :items-per-page="props.perPage"
        :value="props.currentPage"
        @update:page="goToPage"
    >
        <PaginationContent class="flex flex-wrap gap-2 items-center">
            <PaginationItem :value="props.currentPage - 1">
                <PaginationPrevious class="me-12"
                    :disabled="props.currentPage === 1"
                    @click="goToPage(props.currentPage - 1)"
                />
            </PaginationItem>

            <PaginationItem v-if="visiblePages[0] > 1" :value="1">
                <button
                    class="px-3 py-1 rounded hover:bg-background"
                    @click="goToPage(1)"
                >1</button>
            </PaginationItem>

            <PaginationEllipsis v-if="visiblePages[0] > 2" />

            <PaginationItem
                v-for="page in visiblePages"
                :key="page"
                :value="page"
                :is-active="page === props.currentPage"
            >
                <button
                    @click="goToPage(page)"
                    class="px-3 py-1 rounded transition-colors"
                    :class="{
                        'bg-background text-foreground hover:bg-background': page === props.currentPage,
                        'hover:bg-background': page !== props.currentPage
                    }"
                >
                    {{ page }}
                </button>
            </PaginationItem>

            <PaginationEllipsis v-if="visiblePages.at(-1)! < totalPages - 1" />

            <PaginationItem
                v-if="visiblePages.at(-1)! < totalPages"
                :value="totalPages"
            >
                <button
                    class="px-3 py-1 rounded hover:bg-background"
                    @click="goToPage(totalPages)"
                >
                    {{ totalPages }}
                </button>
            </PaginationItem>
            <PaginationItem :value="props.currentPage + 1">
                <PaginationNext
                    class="ml-12"
                    :disabled="props.currentPage === totalPages"
                    @click="goToPage(props.currentPage + 1)"
                />
            </PaginationItem>

        </PaginationContent>
    </Pagination>
</template>
