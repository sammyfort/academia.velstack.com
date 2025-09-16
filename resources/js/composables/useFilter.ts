import { watch } from "vue";
import { debounce } from "lodash-es";
import { router } from "@inertiajs/vue3";

interface ReloadOptions<T> {
    sources: any[];
    mapData: (values: any[]) => T;
    only?: string[];
    replace?: boolean;
    debounceMs?: number;
    routeName?: string; // optional, defaults to current page
}

export function useFilter<T>({sources, mapData, only = [], replace = true, debounceMs = 1000, routeName,}: ReloadOptions<T>) {
    watch(
        sources,
        debounce((values: any[]) => {
            router.reload({
                data: mapData(values),
                only,
                replace,
            });
        }, debounceMs),
        { deep: true }
    );

    // Go to specific page
    const goToPage = (page: number) => {
        router.get(
            route(routeName || window.location.pathname),
            { ...mapData(sources.map(s => s.value)), page },
            { preserveScroll: true, preserveState: true }
        );
    };

    // Reset sources and reload
    const reset = () => {
        sources.forEach((s: any) => {
            if (s.value !== undefined) s.value = null;
        });

        router.get(
            route(routeName || window.location.pathname),
            { page: 1, ...mapData(sources.map(s => s.value)) },
            { only, replace, preserveScroll: true }
        );
    };

    return { goToPage, reset };
}
