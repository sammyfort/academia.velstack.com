<script setup lang="ts">
import { ref, computed, watch } from "vue";
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from "@/components/ui/collapsible";
import {
    SidebarMenuItem,
    SidebarMenuButton,
    SidebarMenuSub,
    SidebarMenuSubItem,
    SidebarMenuSubButton,
} from "@/components/ui/sidebar";
import { ChevronRight } from "lucide-vue-next";
import { Link, usePage } from '@inertiajs/vue3';
import type { LucideIcon } from "lucide-vue-next";

interface SubItem {
    title: string;
    routeName: string;
    visible?: boolean;
    items?: SubItem[];
}

interface SidebarItemProps {
    title: string;
    routeName?: string;
    icon?: LucideIcon;
    items?: SubItem[];
    visible?: boolean;
}

const props = defineProps<SidebarItemProps>();
const page = usePage();

// Helper function to safely get route URL
const getRouteUrl = (routeName: string): string => {
    try {
        return route(routeName);
    } catch (error) {
        console.warn(`Route ${routeName} not found:`, error);
        return '#';
    }
};

// Check if current route matches
const isCurrentRoute = (routeName: string): boolean => {
    try {
        return route(routeName) === page.url;
    } catch (error) {
        console.warn(`Route ${routeName} not found for comparison:`, error);
        return false;
    }
};

// recursive function to check if any sub-item is active
const isActive = (): boolean => {
    const checkItems = (items?: SubItem[]): boolean => {
        if (!items) return false;
        return items.some(sub => {
            if (sub.items?.length) return checkItems(sub.items);
            return sub?.visible !== false && isCurrentRoute(sub.routeName);
        });
    };

    if (props.routeName && isCurrentRoute(props.routeName)) return true;
    return checkItems(props.items);
};

// Reactive open state - initialize with current active state
const open = ref(isActive());

// Watch for route changes and update open state
watch(() => page.url, () => {
    open.value = isActive();
}, { immediate: true });
</script>

<template>
    <SidebarMenuItem v-if="props.visible ?? true">
        <!-- COLLAPSIBLE ITEM -->
        <Collapsible v-if="props.items?.length" v-model:open="open">
            <CollapsibleTrigger as-child>
                <SidebarMenuButton :tooltip="props.title" :class="{ 'bg-sidebar-accent text-sidebar-accent-foreground': open }">
                    <component :is="props.icon" v-if="props.icon"/>
                    <span>{{ props.title }}</span>
                    <ChevronRight
                        class="ml-auto transition-transform duration-200"
                        :class="{ 'rotate-90': open }"
                    />
                </SidebarMenuButton>
            </CollapsibleTrigger>

            <CollapsibleContent>
                <SidebarMenuSub>
                    <template v-for="sub in props.items ?? []" :key="sub.title">
                        <SidebarMenuSubItem v-if="sub?.visible ?? true">
                            <SidebarMenuSubButton as-child>
                                <Link
                                    v-if="sub.routeName"
                                    :href="getRouteUrl(sub.routeName)"
                                    :class="{
                                        'bg-sidebar-accent text-sidebar-accent-foreground font-medium': isCurrentRoute(sub.routeName)
                                    }"
                                >
                                    {{ sub.title }}
                                </Link>
                                <span v-else>{{ sub.title }}</span>
                            </SidebarMenuSubButton>
                        </SidebarMenuSubItem>
                    </template>
                </SidebarMenuSub>
            </CollapsibleContent>
        </Collapsible>

        <!-- SINGLE LINK ITEM -->
        <SidebarMenuButton v-else as-child :tooltip="props.title">
            <Link
                v-if="props.routeName"
                :href="getRouteUrl(props.routeName)"
                :class="{
                    'bg-sidebar-accent text-sidebar-accent-foreground font-medium': isCurrentRoute(props.routeName)
                }"
            >
                <component :is="props.icon" v-if="props.icon"/>
                <span>{{ props.title }}</span>
            </Link>
            <div v-else>
                <component :is="props.icon" v-if="props.icon"/>
                <span>{{ props.title }}</span>
            </div>
        </SidebarMenuButton>
    </SidebarMenuItem>
</template>
