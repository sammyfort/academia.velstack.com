<script setup lang="ts">
import { ref, computed } from "vue";
import { useAppearance } from '@/composables/useAppearance';
import {
  Bell,
  Search,
  Settings,
  User,
  Moon,
  Sun,
  Check,
  X,
  AlertTriangle,
  Info,
  CheckCircle,
} from "lucide-vue-next";
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbPage,
  BreadcrumbSeparator,
} from "@/components/ui/breadcrumb";
import { Separator } from "@/components/ui/separator";
import { SidebarTrigger } from "@/components/ui/sidebar";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { Badge } from "@/components/ui/badge";
import { ScrollArea } from "@/components/ui/scroll-area";
import NavUser from "./NavUser.vue";
// Props
interface BreadcrumbItem {
  title: string;
  href?: string;
  isCurrentPage?: boolean;
}

interface Notification {
  id: string;
  title: string;
  message: string;
  type: "info" | "success" | "warning" | "error";
  timestamp: Date;
  read: boolean;
  action?: {
    label: string;
    handler: () => void;
  };
}
const user = {
  name: "shadcn",
  email: "m@example.com",
  avatar: "/avatars/shadcn.jpg",
};

interface Props {
  breadcrumbs?: BreadcrumbItem[];
  showSearch?: boolean;
  showThemeToggle?: boolean;
  showUserMenu?: boolean;
  user?: {
    name: string;
    email: string;
    avatar?: string;
  };
}

const props = withDefaults(defineProps<Props>(), {
  showSearch: true,
  showThemeToggle: true,
  showUserMenu: true,
  user: () => ({
    name: "John Doe",
    email: "john@example.com",
    avatar: "/avatars/user.jpg",
  }),
});

const { appearance, updateAppearance } = useAppearance()

const isDarkMode = computed(() => {
  if (appearance.value === 'dark') return true
  if (appearance.value === 'light') return false
  // system -> reflect current OS preference
  return typeof window !== 'undefined' &&
    window.matchMedia &&
    window.matchMedia('(prefers-color-scheme: dark)').matches
})

 
const toggleTheme = () => {
  const next = isDarkMode.value ? 'light' : 'dark'
  updateAppearance(next)
};
// Emits
const emit = defineEmits<{
  "notification-click": [notification: Notification];
  "notification-dismiss": [notificationId: string];
  "notification-mark-read": [notificationId: string];
  "notification-mark-all-read": [];
  search: [query: string];
  "theme-toggle": [];
  "user-profile": [];
  "user-settings": [];
  "user-logout": [];
}>();

// Reactive state
const searchQuery = ref("");

 

// Sample notifications data - you can replace this with your actual data source
const notifications = ref<Notification[]>([
  {
    id: "1",
    title: "System Update",
    message: "A new system update is available. Please restart your application.",
    type: "info",
    timestamp: new Date(Date.now() - 5 * 60 * 1000), // 5 minutes ago
    read: false,
    action: {
      label: "Update Now",
      handler: () => console.log("Updating..."),
    },
  },
  {
    id: "2",
    title: "Task Completed",
    message: "Your data export has been completed successfully.",
    type: "success",
    timestamp: new Date(Date.now() - 15 * 60 * 1000), // 15 minutes ago
    read: false,
  },
  {
    id: "3",
    title: "Warning",
    message: "Your storage is almost full. Consider upgrading your plan.",
    type: "warning",
    timestamp: new Date(Date.now() - 2 * 60 * 60 * 1000), // 2 hours ago
    read: true,
  },
  {
    id: "4",
    title: "Connection Error",
    message: "Failed to sync data. Please check your internet connection.",
    type: "error",
    timestamp: new Date(Date.now() - 24 * 60 * 60 * 1000), // 1 day ago
    read: true,
  },
]);

// Computed properties
const unreadCount = computed(() => notifications.value.filter((n) => !n.read).length);

const sortedNotifications = computed(() =>
  [...notifications.value].sort((a, b) => b.timestamp.getTime() - a.timestamp.getTime())
);



const handleNotificationClick = (notification: Notification) => {
  if (!notification.read) {
    markAsRead(notification.id);
  }
  emit("notification-click", notification);
};

const markAsRead = (notificationId: string) => {
  const notification = notifications.value.find((n) => n.id === notificationId);
  if (notification) {
    notification.read = true;
    emit("notification-mark-read", notificationId);
  }
};

const dismissNotification = (notificationId: string) => {
  const index = notifications.value.findIndex((n) => n.id === notificationId);
  if (index > -1) {
    notifications.value.splice(index, 1);
    emit("notification-dismiss", notificationId);
  }
};

const markAllAsRead = () => {
  notifications.value.forEach((n) => (n.read = true));
  emit("notification-mark-all-read");
};

const getNotificationIcon = (type: Notification["type"]) => {
  switch (type) {
    case "success":
      return CheckCircle;
    case "warning":
      return AlertTriangle;
    case "error":
      return AlertTriangle;
    default:
      return Info;
  }
};

const getNotificationColor = (type: Notification["type"]) => {
  switch (type) {
    case "success":
      return "text-green-500";
    case "warning":
      return "text-yellow-500";
    case "error":
      return "text-red-500";
    default:
      return "text-blue-500";
  }
};

const formatTimestamp = (timestamp: Date) => {
  const now = new Date();
  const diff = now.getTime() - timestamp.getTime();
  const minutes = Math.floor(diff / (1000 * 60));
  const hours = Math.floor(diff / (1000 * 60 * 60));
  const days = Math.floor(diff / (1000 * 60 * 60 * 24));

  if (minutes < 1) return "Just now";
  if (minutes < 60) return `${minutes}m ago`;
  if (hours < 24) return `${hours}h ago`;
  return `${days}d ago`;
};

// Expose methods for parent component
defineExpose({
  addNotification: (notification: Omit<Notification, "id">) => {
    notifications.value.unshift({
      ...notification,
      id: Date.now().toString(),
    });
  },
  clearAllNotifications: () => {
    notifications.value = [];
  },
  markAllAsRead,
});
</script>

<template>
  <header
    class="flex h-16 shrink-0 items-center gap-2 transition-[width,height] ease-linear group-has-[[data-collapsible=icon]]/sidebar-wrapper:h-12"
  >
    <div class="flex items-center gap-2 px-4">
      <SidebarTrigger class="-mr-5" />
    </div>

    <!-- Spacer -->
    <div class="flex-1" />

    <!-- Header Actions -->
    <div class="flex items-center gap-2 px-4">
      <!-- Notifications -->
      <DropdownMenu>
        <DropdownMenuTrigger asChild>
          <Button variant="ghost" size="icon" class="relative">
            <Bell class="h-4 w-4" />
            <Badge
              v-if="unreadCount > 0"
              class="absolute -top-2 -right-2 h-5 w-5 rounded-full p-0 text-xs"
              variant="destructive"
            >
              {{ unreadCount > 9 ? "9+" : unreadCount }}
            </Badge>
          </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end" class="w-80">
          <DropdownMenuLabel class="flex items-center justify-between">
            <span>Notifications</span>
            <Button
              v-if="unreadCount > 0"
              variant="ghost"
              size="sm"
              class="h-6 px-2 text-xs"
              @click="markAllAsRead"
            >
              Mark all read
            </Button>
          </DropdownMenuLabel>
          <DropdownMenuSeparator />

          <ScrollArea class="h-[400px]">
            <div
              v-if="notifications.length === 0"
              class="p-4 text-center text-sm text-muted-foreground"
            >
              No notifications
            </div>

            <div v-else class="space-y-1">
              <div
                v-for="notification in sortedNotifications"
                :key="notification.id"
                class="relative flex items-start gap-3 rounded-md p-3 hover:bg-accent cursor-pointer group"
                :class="{ 'bg-accent/50': !notification.read }"
                @click="handleNotificationClick(notification)"
              >
                <component
                  :is="getNotificationIcon(notification.type)"
                  class="h-4 w-4 mt-0.5 shrink-0"
                  :class="getNotificationColor(notification.type)"
                />

                <div class="flex-1 space-y-1">
                  <div class="flex items-start justify-between gap-2">
                    <p class="text-sm font-medium leading-none">
                      {{ notification.title }}
                    </p>
                    <div class="flex items-center gap-1">
                      <span class="text-xs text-muted-foreground">
                        {{ formatTimestamp(notification.timestamp) }}
                      </span>
                      <Button
                        variant="ghost"
                        size="icon"
                        class="h-4 w-4 opacity-0 group-hover:opacity-100 transition-opacity"
                        @click.stop="dismissNotification(notification.id)"
                      >
                        <X class="h-3 w-3" />
                      </Button>
                    </div>
                  </div>

                  <p class="text-sm text-muted-foreground leading-snug">
                    {{ notification.message }}
                  </p>

                  <div v-if="notification.action" class="pt-2">
                    <Button
                      size="sm"
                      variant="outline"
                      class="h-6 px-2 text-xs"
                      @click.stop="notification.action!.handler()"
                    >
                      {{ notification.action.label }}
                    </Button>
                  </div>
                </div>

                <div
                  v-if="!notification.read"
                  class="w-2 h-2 bg-blue-500 rounded-full shrink-0 mt-2"
                />
              </div>
            </div>
          </ScrollArea>
        </DropdownMenuContent>
      </DropdownMenu>

      <!-- Theme Toggle -->
      <Button   variant="ghost" size="icon" @click="toggleTheme">
        <Sun v-if="isDarkMode" class="h-4 w-4" />
        <Moon v-else class="h-4 w-4" />
      </Button>

      <!-- User Menu -->
      <NavUser :user="user" />
    </div>
  </header>
</template>
