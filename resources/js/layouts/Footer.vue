<script setup lang="ts">

import { Facebook, Instagram, Linkedin } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';
import ContactUsSheet from '@/components/ContactUsSheet.vue';
</script>

<template>
    <footer class="bg-gradient-to-r from-gray-900 via-gray-700 to-gray-900  pt-12 pb-6 px-10 font-sans tracking-wide relative">
        <div class="max-w-screen-xl mx-auto">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                <div>
                    <h2 class="text-primary text-sm uppercase font-medium mb-6">APP</h2>
                    <ul class="space-y-4">
                        <li>
                            <Link :href="route('home')" class="text-gray-400 hover:text-primary text-sm transition-all">Home</Link>
                        </li>
                        <li>
                            <Link :href="route('home')" class="text-gray-400 hover:text-primary text-sm transition-all">Businesses</Link>
                        </li>
                        <li>
                            <Link :href="route('signboards.index')" class="text-gray-400 hover:text-primary text-sm transition-all">Find A Signboard</Link>
                        </li>
                        <li>
                            <Link :href="route('privacy-policy')" class="text-gray-400 hover:text-primary text-sm transition-all">Privacy Policy</Link>
                        </li>
                    </ul>
                </div>

                <div>
                    <h2 class="text-primary text-sm uppercase font-medium mb-6">COMPANY</h2>
                    <ul class="space-y-4">
                        <li>
                            <Link :href="route('about-us')" class="text-gray-400 hover:text-primary text-sm transition-all">About Us</Link>
                        </li>
                        <li>
                            <Link :href="route('faq.index')" class="text-gray-400 hover:text-primary text-sm transition-all">FAQs</Link>
                        </li>
                        <li>
                            <ContactUsSheet>
                                <span class="text-gray-400 hover:text-primary text-sm transition-all cursor-pointer">Contact Us</span>
                            </ContactUsSheet>
                        </li>
                        <li>
                            <ContactUsSheet>
                                <span class="text-gray-400 hover:text-primary text-sm transition-all cursor-pointer">Career</span>
                            </ContactUsSheet>
                        </li>
                    </ul>
                </div>

                <div>
                    <h2 class="text-primary text-sm uppercase font-medium mb-6">Useful Links</h2>
                    <ul class="space-y-4">
                        <li>
                            <Link :href="route('my-signboards.index')" class="text-gray-400 hover:text-primary text-sm transition-all">Advertise</Link>
                        </li>
                        <li>
                            <Link :href="route('dashboard')" class="text-gray-400 hover:text-primary text-sm transition-all">Dashboard</Link>
                        </li>
                        <li>
                            <a :href="route('download.apk')" target="_blank" class="text-gray-400 hover:text-primary text-sm transition-all">Download App</a>
                        </li>
                    </ul>
                </div>

                <div class="flex items-center lg:justify-center max-sm:-order-1">
                    <a href='javascript:void(0)'>
                        <!--                                <img src="https://readymadeui.com/readymadeui-light.svg" alt="logo" class="sm:w-52 w-40" />-->
                        <img src="/images/logo.png" alt="logo" class="w-30" />
                    </a>
                </div>
            </div>

            <hr class="mt-12 mb-6 border-gray-600" />

            <div class="flex sm:justify-between flex-wrap gap-6">
                <div class="flex space-x-5">
                    <a href="javascript:void(0)" class="text-gray-400 hover:text-primary text-sm transition-all">
                        <Facebook />
                    </a>
                    <a href="javascript:void(0)" class="text-gray-400 hover:text-primary text-sm transition-all">
                        <Instagram />
                    </a>
                    <a href="javascript:void(0)" class="text-gray-400 hover:text-primary text-sm transition-all">
                        <Linkedin />
                    </a>
                </div>

                <p class="text-gray-400 text-sm">© Signboard Ghana. All rights reserved.</p>
            </div>
        </div>
    </footer>
</template>

<style scoped>

</style>
