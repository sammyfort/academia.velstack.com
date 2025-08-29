<script setup lang="ts">

import { Button } from '@/components/ui/button';
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { LogOut } from 'lucide-vue-next';
import { UserPlus2, Milestone, Building2, Menu, ChevronDown } from 'lucide-vue-next';
import { Popover, PopoverTrigger, PopoverContent } from '@/components/ui/popover';
import { HandHelping, UserRoundCog, LayoutDashboard } from 'lucide-vue-next';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import MobileNav from '@/layouts/MobileNav.vue';
import CompanyNavPopover from '@/components/Nav/CompanyNavPopover.vue';
import SignboardNavPopover from '@/components/Nav/SignboardNavPopover.vue';
import ServiceNavPopover from '@/components/Nav/ServiceNavPopover.vue';


const page = usePage()
const user = computed(() => page.props.auth.user)


</script>

<template>
    <nav class="sticky top-4 md:top-0 z-50 w-full flex justify-center">
        <div class="w-full">
            <div class="flex items-center justify-between gap-2 bg-background/50 lg:py-4 backdrop-blur-md lg:mt-4 lg:rounded-2xl lg:border lg:px-4 px-3">
                <div class="flex items-center gap-5">
                    <div class="flex items-center gap-12">
                        <Link class="hidden lg:flex items-center gap-2.5" :href="route('home')">
                            <img src="/images/logo.png" class="size-15 rounded-md" alt="Signboard Logo" />
                            <h2 class="text-2xl font-bold text-center">{{ page.props.name }}</h2>
                        </Link>
                        <MobileNav>
                            <Menu class="lg:hidden" :size="35"/>
                        </MobileNav>
                        <div class="hidden items-center gap-5 text-sm font-medium text-muted-foreground lg:flex">
                            <Link
                                :class="{ 'active-nav': $page.component === 'Home' }"
                                :href="route('home')"
                                class="hover:text-primary py-4 text-center"
                            >Home</Link>
                            <Link
                                :class="{ 'active-nav': $page.component === 'Services/Services' }"
                                :href="route('services.index')"
                                class="hover:text-primary py-4 text-center"
                            >Service Providers</Link>
                            <Link
                                :class="{ 'active-nav': $page.component === 'Signboards/Signboards' }"
                                :href="route('signboards.index')"
                                class="hover:text-primary py-4 text-center"
                            >Signboards</Link>
                            <Link
                                :class="{ 'active-nav': $page.component === 'Jobs/Jobs' }"
                                :href="route('jobs.index')"
                                class="hover:text-primary py-4 text-center"
                            >Jobs</Link>
                            <Link
                                :class="{ 'active-nav': $page.component === 'Products/Products' }"
                                :href="route('products.index')"
                                class="hover:text-primary py-4 text-center"
                            >Shop</Link>

                            <CompanyNavPopover>
                                <span
                                    :class="{ 'active-nav': $page.component === 'FAQ' || $page.component === 'AboutUs' }"
                                    class="hover:text-primary py-4 text-center flex gap-0.5 items-center"
                                >
                                    <span>Company</span>
                                    <ChevronDown :size="22"/>
                                </span>
                            </CompanyNavPopover>
                        </div>
                    </div>
                </div>
                <Link class="flex lg:hidden items-center gap-3 mx-auto py-2" :href="route('home')">
                    <img src="/images/logo.png" class="size-14 rounded-md" alt="Signboard Logo" />
                    <h2 class="text-lg font-bold">{{ page.props.name }}</h2>
                </Link>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-4">
                        <div v-if="user" class="hidden items-center gap-5 text-sm font-medium text-muted-foreground lg:flex">
                            <Link
                                :class="{ 'active-nav': $page.component === 'Dashboard/Dashboard' }"
                                :href="route('dashboard')"
                                class="hover:text-primary flex items-center gap-1 py-4 text-center"
                            >
                                <LayoutDashboard  :size="15" class="text-secondary"/> Dashboard
                            </Link>

<!--                            <SignboardNavPopover>-->
<!--                                 <span :class="{ 'active-nav':-->
<!--                                 $page.component === 'Signboards/MySignboards'-->
<!--                                || $page.component === 'Signboards/SignboardShow'-->
<!--                                || $page.component === 'Signboards/SignboardCreate'-->
<!--                                || $page.component === 'Signboards/SignboardEdit'-->

<!--                                || $page.component === 'Businesses/BusinessesShow'-->
<!--                                || $page.component === 'Businesses/BusinessesCreate'-->
<!--                                || $page.component === 'Businesses/BusinessesEdit'-->
<!--                                || $page.component === 'Businesses/MyBusinesses'-->
<!--                                 }"-->
<!--                                     class="hover:text-primary py-4 text-center flex gap-0.5 items-center"-->
<!--                                 ><Milestone :size="15" class="text-secondary"/> My Signboards<ChevronDown :size="22"/></span>-->
<!--                            </SignboardNavPopover>-->

                            <Link
                                :class="{ 'active-nav': $page.component ===
                                'Signboards/SignboardCreate' || 'Signboards/SignboardEdit' || 'Signboards/SignboardShow' || 'Signboards/MySignboards' }"
                                :href="route('my-signboards.index')"
                                class="hover:text-primary flex items-center gap-1 py-4 text-center"
                            >
                                <Milestone  :size="15" class="text-secondary"/> My Signboards
                            </Link>

                            <ServiceNavPopover>
                                 <span :class="{ 'active-nav':
                                 $page.component === 'Services/MyServices'
                                || $page.component === 'Services/ServiceShow'
                                || $page.component === 'Services/ServiceCreate'
                                || $page.component === 'Services/ServiceEdit'
                                 }"
                                       class="hover:text-primary py-4 text-center flex gap-0.5 items-center"
                                 ><Building2 :size="15" class="text-secondary"/> My Services<ChevronDown :size="22"/></span>
                            </ServiceNavPopover>
                        </div>

                        <div v-if="!user" class="hidden lg:block gap-1">
                            <Link :href="route('login')" variant="secondary" class="px-[1rem] gap-2 items-center flex font-black text-secondary hover:text-primary">
                                Login
                            </Link>
                        </div>
                        <div v-if="!user" class="hidden lg:flex flex-col items-center space-x-1">
                            <Button  @click="router.visit(route('register'))" class="px-[1rem] cursor-pointer hover:bg-secondary">
                                <div><UserPlus2 /></div>
                                Join Now
                            </Button>
                        </div>
                        <div v-else class="ms-4 flex gap-4 items-center space-x-1">
                            <Popover>
                                <PopoverTrigger as-child>
                                    <div class="lex flex items-center cursor-pointer gap-3">
                                        <Avatar class="h-12 w-12 border">
                                            <AvatarImage :src="user.avatar?.original_url ?? ''" />
                                            <AvatarFallback class="bg-gray-300 uppercase">{{ user.initials }}</AvatarFallback>
                                        </Avatar>
                                    </div>
                                </PopoverTrigger>
                                <PopoverContent class="w-60" align="end">
                                    <div class="text-fade mb-3" style="line-height: 16px">
                                        <div class="font-bold flex gap-1 flex-wrap">
                                            {{ user?.fullname }}
                                        </div>
                                        <div class="text-sm break-all">{{ user.email }}</div>
                                    </div>
                                    <div class="grid">
                                        <Link
                                            :href="route('profile.show')"
                                            class="p-1.5 hover:bg-secondary items-center hover:text-white flex gap-2"
                                        >
                                            <span>Account Settings</span>
                                            <UserRoundCog class="ms-auto" :size="16"/>
                                        </Link>
                                        <Link href="#" class="p-1.5 hover:bg-secondary items-center hover:text-white flex gap-2">
                                            <span>Help</span>
                                            <HandHelping class="ms-auto" :size="16"/>
                                        </Link>
                                        <Link
                                            :href="route('logout')"
                                            method="post"
                                            class="p-1.5 border-0 text-destructive cursor-pointer hover:bg-destructive items-center hover:text-white flex gap-2"
                                        >
                                            <span>Logout</span>
                                            <LogOut class="ms-auto" :size="16"/>
                                        </Link>
                                    </div>
                                </PopoverContent>
                            </Popover>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>

<style scoped>

</style>
