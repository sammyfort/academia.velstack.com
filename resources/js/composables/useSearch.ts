import { ref, computed } from "vue";

export function useSearch<T extends Record<string, any>>(
  data: T[],
  keys: (keyof T)[] // fields to search on
) {
  const query = ref("");

  const results = computed(() => {
    if (!query.value) return data;
    const lowerQuery = query.value.toLowerCase();

    return data.filter((item) =>
      keys.some((key) =>
        String(item[key] ?? "").toLowerCase().includes(lowerQuery)
      )
    );
  });

  const reset = () => {
    query.value = "";
  };

  return { query, results, reset };
}
