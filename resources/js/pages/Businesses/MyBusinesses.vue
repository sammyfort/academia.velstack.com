<script setup lang="ts">
import Layout from '@/layouts/GuestLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { PlusIcon, Briefcase} from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import CreateBusiness from '@/pages/Businesses/CreateBusiness.vue';
import VerifiedBadge from '@/components/icons/VerifiedBadge.vue';
import Paginator from '@/components/helpers/Paginator.vue';


const props = defineProps<{
    businesses: {
        data: Array<{
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
            verified?: boolean;
        }>;
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
}>();

const goToPage = (page: number) => {
    router.get(route('my-businesses.index'), { page }, {
        preserveScroll: true,
        preserveState: true,
    });
};

</script>
<template>
    <Head title="My Businesses" />

    <Layout>

        <div class=" md:block fixed bottom-8 right-8 z-50">
            <CreateBusiness @created="$inertia.reload({ only: ['businesses'] })">
                <Button
                    class="bg-primary hover:bg-primary text-white font-semibold px-4 py-2 rounded shadow flex items-center gap-2"
                >
                    <PlusIcon class="w-4 h-4" />
                    <span>Add Business</span>
                </Button>
            </CreateBusiness>
        </div>

        <div class="relative min-h-screen px-4 pt-8">
            <div v-if="businesses.data.length" class="mt-4 flex flex-wrap items-center justify-center">
                <Link
                    v-for="business in businesses.data"
                    :key="business.id"
                    :href="route('my-businesses.show', business.slug)"
                    class="w-64 h-80 flex-shrink-0 m-6 relative overflow-hidden bg-primary rounded-lg shadow-lg group transition-transform hover:scale-105"
                >
                    <svg
                        class="absolute bottom-0 left-0 mb-8 scale-150 group-hover:scale-[1.65] transition-transform"
                        viewBox="0 0 375 283"
                        fill="none"
                        style="opacity: 0.1;"
                    >
                        <rect
                            x="159.52"
                            y="175"
                            width="152"
                            height="152"
                            rx="8"
                            transform="rotate(-45 159.52 175)"
                            fill="white"
                        />
                        <rect
                            y="107.48"
                            width="152"
                            height="152"
                            rx="8"
                            transform="rotate(-45 0 107.48)"
                            fill="white"
                        />
                    </svg>

                    <div class="relative pt-10 px-10 flex items-center justify-center">
                        <div
                            class="block absolute w-48 h-48 bottom-0 left-0 -mb-24 ml-3"
                            style="background: radial-gradient(black, transparent 60%); transform: rotate3d(0, 0, 1, 20deg) scale3d(1, 0.6, 1); opacity: 0.2;"
                        ></div>
                        <img
                            class="relative w-32 h-32 object-contain"
                            src="/images/business-icon.png"
                            alt="Business Briefcase"
                        />
                    </div>

                    <div class="relative text-white px-6 pb-6 mt-6 text-center">
                        <span class="block opacity-75 -mb-1 text-sm truncate">{{ business.mobile }}</span>
                        <div class="flex justify-center">
                            <span class="block font-semibold text-lg truncate w-full">{{ business.name }}</span>
                        </div>
                    </div>
                </Link>

                <div class="mt-8 flex justify-center w-full">
                    <Paginator v-if="props.businesses.per_page > 10"
                        :total="props.businesses.total"
                        :per-page="props.businesses.per_page"
                        :current-page="props.businesses.current_page"
                        @page-change="goToPage"
                    />
                </div>
            </div>

            <div v-else class="fixed inset-0 flex flex-col items-center justify-center text-center text-gray-600 px-4">
                <Briefcase class="w-20 h-20 mb-6 text-primary opacity-80" />
                <h3 class="text-2xl font-semibold mb-2 text-gray-700">No businesses yet</h3>
                <p class="text-base text-gray-500 mb-6">
                    You haven't added any businesses yet. Click below to get started.
                </p>
                <CreateBusiness @created="$inertia.reload({ only: ['businesses'] })">
                    <Button
                        class="bg-primary hover:bg-orange-600 text-white font-semibold px-6 py-2 rounded shadow flex items-center gap-2"
                    >
                        <PlusIcon class="w-4 h-4" />
                        <span>Add First Business</span>
                    </Button>
                </CreateBusiness>
            </div>
        </div>

    </Layout>
</template>
