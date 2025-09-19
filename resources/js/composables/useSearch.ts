import { ref, computed, type Ref, type ComputedRef } from "vue";

export function useSearch<T extends Record<string, any>, K extends keyof T>(
    source: Ref<T[]> | ComputedRef<T[]>,
    keys: K[]
) {
    const query = ref("");

    const results = computed(() => {
        const list = source.value ?? [];
        if (!query.value) return list;

        const lowerQuery = query.value.toLowerCase();

        return list.filter((item) =>
            keys.some((key) =>
                String(item?.[key] ?? "").toLowerCase().includes(lowerQuery)
            )
        );
    });

    const reset = () => {
        query.value = "";
    };

    return { query, results, reset };
}

