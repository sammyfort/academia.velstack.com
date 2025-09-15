<script setup lang="ts">
import {Bot, Frame, Settings2, SquareTerminal} from "lucide-vue-next";

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
            {title: "Students", routeName: "students.index", icon: Bot, visible: true},
            {title: "Staff", routeName: "staff.index", icon: Bot, visible: true},
            {title: "Parents", routeName: "parents.index", icon: Bot, visible: true}

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
];
</script>

<template>
    <Sidebar :collapsible="'icon'">
        <SidebarHeader>
            <TeamSwitcher/>
        </SidebarHeader>

        <SidebarContent>
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
