<script setup lang="ts">
import Layout from '@/layouts/Layout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { PlusIcon, Briefcase, Eye} from 'lucide-vue-next';
import { Button } from '@/components/ui/button';

import Paginator from '@/components/helpers/Paginator.vue';
import { PaginatedDataI, SignboardI } from '@/types';


const props = defineProps<{
    signboards: PaginatedDataI<SignboardI>
}>();

const goToPage = (page: number) => {
    router.get(route('my-signboards.index'), { page }, {
        preserveScroll: true,
        preserveState: true,
    });
};

</script>
<template>
    <Head title="My Signboard" />

    <Layout>
        <div class=" md:block fixed bottom-8 right-8 z-50">
            <Link :href="route('my-signboards.create')">
                <Button class="bg-primary hover:from-primary-700 hover:to-primary-700
                text-white font-semibold px-6 py-4 rounded-full shadow-2xl transform hover:scale-110 transition-all duration-300 ease-out flex items-center gap-3 group">
                    <PlusIcon class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" />
                    <span class=" lg:block">Add Signboard</span>
                </Button>
            </Link>
        </div>

        <div class="relative min-h-screen px-4 pt-8">

            <div v-if="signboards.data?.length" class="mt-4 w-full">
                <div class="w-full mx-auto px-2">
                    <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">

                        <Link  v-for="signboard in signboards.data"
                               :key="signboard.id"
                               :href="route('my-signboards.show', signboard.slug)"
                               class="w-full">
                            <div
                                class="relative rounded-2xl bg-gradient-to-t from-blue-300 to-white p-0.5 shadow-[0_0px_20px_0px_rgba(0,0,0,0.1)] transition-all duration-300 ease-in-out hover:shadow-[0_0px_25px_0px_rgba(0,0,0,0.2)]">
                                <div class="relative rounded-2xl p-6 bg-white text-center h-full flex flex-col justify-between hover:translate-y-[-5px] transition duration-300 ease-in-out overflow-hidden">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="absolute top-4 left-4 w-48 h-48 text-blue-100 opacity-50 z-0 pointer-events-none transform transition-all duration-500 group-hover:-translate-y-4 group-hover:-translate-x-4"
                                        fill="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5z"
                                        />
                                    </svg>

                                    <div class="relative z-10">
                                        <h3 class="text-lg font-bold text-gray-800 truncate">
                                            {{ signboard.business?.name  }}
                                        </h3>
                                        <p class="text-xs text-blue-600 mb-2">
                                            {{ signboard.region?.name  }}
                                        </p>

                                        <p class="text-xl font-semibold text-primary">
                                            {{ signboard.landmark }}
                                        </p>
                                        <div class="mt-4 text-sm text-gray-700 space-y-1">
                                            <p><strong>Town:</strong> {{ signboard.town }}</p>
                                            <p v-if="signboard.street"><strong>Street:</strong> {{ signboard.street }}</p>
                                            <p v-if="signboard.blk_number"><strong>Block:</strong> {{ signboard.blk_number }}</p>
                                            <p><strong>GPS:</strong> {{ signboard.gps }}</p>
                                        </div>
                                    </div>

                                    <button
                                       class="relative z-10 mt-6 inline-flex items-center justify-center bg-primary text-white py-2.5 px-4 text-sm rounded-md w-full hover:bg-primary transition">
                                         <Eye/>
                                        View Signboard
                                    </button>
                                </div>
                            </div>
                        </Link >
                    </div>

                    <div class="mt-8 flex justify-center w-full">
                        <Paginator v-if="props.signboards.per_page > 10"
                            :total="props.signboards.total"
                            :per-page="props.signboards.per_page"
                            :current-page="props.signboards.current_page"
                            @page-change="goToPage"
                        />
                    </div>
                </div>
            </div>

            <div v-else class="fixed inset-0 flex flex-col items-center justify-center text-center text-gray-600 px-4">
                <Briefcase class="w-20 h-20 mb-6 text-primary opacity-80" />
                <h3 class="text-2xl font-semibold mb-2 text-gray-700">No Signboards yet</h3>
                <p class="text-base text-gray-500 mb-6">
                    You haven't added any businesses yet. Click below to get started.
                </p>
                <Link
                   :href="route('my-signboards.create')"
                    @created="$inertia.reload({ only: ['signboards'] })">
                    <Button
                        class="bg-primary hover:bg-primary text-white font-semibold px-6 py-2 rounded shadow flex items-center gap-2"
                    >
                        <PlusIcon class="w-4 h-4" />
                        <span>Add First Signboard</span>
                    </Button>
                </Link>
            </div>
        </div>

    </Layout>
</template>
