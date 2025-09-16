import { ref, computed } from "vue";

export function useSelectable<T extends { id: number | string }>(items: T[]) {
    const selected = ref<(number | string)[]>([]);

    const allSelected = computed(() =>
        items.length > 0 && selected.value.length === items.length
    );

    const toggleSelect = (id: number | string, val: boolean) => {
        if (val) {
            if (!selected.value.includes(id)) selected.value.push(id);
        } else {
            selected.value = selected.value.filter((sid) => sid !== id);
        }
    };

    const toggleSelectAll = (val: boolean) => {
        selected.value = val ? items.map((item) => item.id) : [];
    };

    const clearSelection = () => {
        selected.value = [];
    };

    return {
        selected,
        allSelected,
        toggleSelect,
        toggleSelectAll,
        clearSelection,
    };
}
