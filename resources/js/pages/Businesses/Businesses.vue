<script setup lang="ts">
import Layout from '@/layouts/Layout.vue';
import { Head } from '@inertiajs/vue3';
import { Briefcase } from 'lucide-vue-next';
import SignboardCardV1 from '@/components/signboard/SignboardCardV1.vue';

const props = defineProps<{
    businesses: Array<{
        id: number;
        slug: string;
        name: string;
        email: string;
        mobile: string;
        description: string;
        facebook?: string;
        x?: string;
        linkedin?: string;
        instagram?: string;
        verified?: boolean,
    }>;
}>();
</script>

<template>
    <Head title="Businesses" />
    <Layout>
        <div class="w-full">
            <div class="text-gray-900 antialiased">
                <div  v-if="businesses.length"  class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 items-center md:justify-center gap-7">
                    <SignboardCardV1 />
                </div>

                <div v-else class="fixed inset-0 flex flex-col items-center justify-center text-center text-gray-600 px-4">
                    <Briefcase class="w-20 h-20 mb-6 text-orange-500 opacity-80" />

                    <h3 class="text-2xl font-semibold mb-2 text-gray-700">No businesses yet</h3>
                    <p class="text-base text-gray-500 mb-6">
                        No Businesses Available Yet.
                    </p>

                </div>
            </div>
        </div>
    </Layout>
</template>

<style scoped></style>
