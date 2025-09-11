

<script setup lang="ts">
import AppSidebar from "@/layouts/app/AppSidebar.vue"

import {
  SidebarInset,
  SidebarProvider,
  SidebarTrigger,
} from "@/components/ui/sidebar"
import Header from "@/layouts/app/Header.vue"
import { Toaster } from 'vue-sonner'
</script>

<template>
  <SidebarProvider>
   <AppSidebar />
    <SidebarInset>
       <Header />
        <main class="p-6">
            <Toaster richColors position="top-right" />
        <slot />
      </main>
    </SidebarInset>
  </SidebarProvider>
</template>
