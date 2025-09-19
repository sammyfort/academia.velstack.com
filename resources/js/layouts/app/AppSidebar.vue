<script setup lang="ts">
import {Bot, Frame, Settings2, SquareTerminal, Trash, Logs, Building2, Subscript, FrameIcon, Users, UserCog} from "lucide-vue-next";

import NavUser from "./NavUser.vue";
import TeamSwitcher from "./TeamSwitcher.vue";

import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarRail,
    SidebarProvider, // Add this import
} from "@/components/ui/sidebar";

import {SidebarGroup, SidebarGroupLabel, SidebarMenu} from "@/components/ui/sidebar";

import TopHeader from "@/layouts/app/Header.vue";
import SidebarItem from "@/layouts/app/SidebarItem.vue";

const props = withDefaults(defineProps(), {
    collapsible: "icon",
});

const sidebarData = [
    {
        label: "Home",
        items: [{title: "Dashboard", routeName: "dashboard", icon: Bot, visible: true}],
    },
    {
        label: "ACADEMICS",
        items: [

            // {
            //     title: "Classes",
            //     icon: Frame,
            //     visible: true,
            //     items: [
            //         {title: "Class List", routeName: "classes.index", visible: true},
            //         {title: "Assign Subjects", routeName: "classes.create", visible: true},
            //         {title: "Assign Staff", routeName: "classes.create", visible: true},
            //     ],
            // },
            {title: "Classes", routeName: "classes.index", icon: SquareTerminal, visible: true,},
            {title: "Subjects", routeName: "subjects.index", icon: SquareTerminal, visible: true,},
            {title: "Semesters", routeName: "semesters.index", icon: SquareTerminal, visible: true},
            {title: "Timetable", routeName: "timetables.index", icon: SquareTerminal, visible: true},

        ],
    },
    {
        label: "Management",
        items: [
            {title: "Students", routeName: "students.index", icon: UserCog, visible: true},
            {title: "Staff", routeName: "staff.index", icon: Users, visible: true},
            {title: "Parents", routeName: "parents.index", icon: FrameIcon, visible: true}

        ],

    },

    {
        label: "Finance",
        items: [
            {title: "Fees", routeName: "fees.index", icon: Bot, visible: true},
            {title: "Billing", routeName: "fees.index", icon: Bot, visible: true},
            {title: "Payments", routeName: "fees.index", icon: Bot, visible: true},
        ],
    },

    {
        label: "System",
        items: [
            {title: "Roles", routeName: "roles.index", icon: Bot, visible: true},
            {title: "Trash", routeName: "roles.index", icon: Trash, visible: true},
            {title: "Logs", routeName: "roles.index", icon: Logs, visible: true},
        ],
    },
];
</script>
<template>
  <Sidebar :collapsible="'icon'" class="h-screen flex flex-col">
    <SidebarHeader>
      <TeamSwitcher />
    </SidebarHeader>

    <!-- This must be the scrollable element -->
    <SidebarContent class="flex-1 overflow-y-auto min-h-0 custom-scrollbar">
      <template v-for="group in sidebarData" :key="group.label">
        <SidebarGroup>
          <SidebarGroupLabel>{{ group.label }}</SidebarGroupLabel>
          <SidebarMenu>
            <SidebarItem
              v-for="item in group.items"
              :key="item.title"
              :title="item.title"
              :route-name="item.routeName"
              :icon="item.icon"
              :items="item.items"
              :visible="item.visible"
            />
          </SidebarMenu>
        </SidebarGroup>
      </template>
    </SidebarContent>

    <SidebarFooter>
      <NavUser/>
    </SidebarFooter>

    <SidebarRail/>
  </Sidebar>
</template>

<style scoped>
/* global CSS (app.css or <style> without scoped) */
.custom-scrollbar {
  /* safety: allow the element to shrink inside flex container */
  min-height: 0;
  scrollbar-width: thin; /* Firefox */
  scrollbar-color: rgba(156,163,175,0.5) transparent; /* Firefox: thumb then track */
}

/* WebKit browsers (Chrome, Edge, Safari) */
.custom-scrollbar::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: rgba(156,163,175,0.5);
  border-radius: 9999px;
  border: 2px solid transparent; /* padding effect */
  background-clip: padding-box;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background-color: rgba(156,163,175,0.8);
}

</style>