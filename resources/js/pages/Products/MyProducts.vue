
<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import Layout from '@/layouts/GuestLayout.vue'
import Paginator from '@/components/helpers/Paginator.vue'
import { PlusIcon, Eye, Hammer, DropletOff } from 'lucide-vue-next'
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue, } from '@/components/ui/select'
import { ref, onMounted} from 'vue'
import { Button } from '@/components/ui/button';
import { PaginatedDataI, ProductI } from '@/types';


const props = defineProps<{
    products: PaginatedDataI<ProductI>
    sort?: string
}>()

onMounted(()=>{
  //  console.log(props.products)
})
const sortOrder = ref(props.sort || 'desc')
const onSortChange = () => {
    router.get(route('my-products.index'), {
        sort: sortOrder.value
    }, {
        preserveScroll: true,
        preserveState: true,
    });
};
const goToPage = (page) => {
    router.get(route('my-products.index'), { page }, {
        preserveScroll: true,
        preserveState: true,
    });
}
</script>

<template>
    <Head title="Artisan Works" />

    <Layout>
        <div class=" md:block fixed bottom-8 left-8 z-50">
            <Link :href="route('my-products.create')">
                <Button class="bg-primary hover:from-primary-700 hover:to-primary-700
                text-white font-semibold px-6 py-4 rounded-full shadow-2xl transform hover:scale-110 transition-all duration-300 ease-out flex items-center gap-3 group">
                    <PlusIcon class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" />
                    <span class=" lg:block">Add Product</span>
                </Button>
            </Link>
        </div>


        <div class="min-h-screen w-full bg-gradient-to-br from-slate-50 via-white to-blue-50">

            <div class="relative overflow-hidden bg-secondary">
                <div class="absolute inset-0 bg-black opacity-20"></div>
                <div class="relative px-6 py-16 text-center">
                    <div class="max-w-4xl mx-auto">
                        <h1 class="text-4xl md:text-6xl font-black text-white mb-4 tracking-tight">
                           My <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-orange-500">Products</span>
                        </h1>
                        <p class="text-xl text-slate-300 max-w-2xl mx-auto leading-relaxed">
                          Your products available to be sold out
                        </p>
                    </div>
                </div>
            </div>


            <div class="md:hidden px-6 py-6 bg-white border-b border-slate-200">
                <Link :href="route('my-products.create')">
                    <Button class="w-full bg-primary
                     hover:to-purple-700 text-white font-semibold px-6 py-4 rounded-xl shadow-lg flex items-center
                     justify-center gap-3 transform hover:scale-[1.02] transition-all duration-200">
                        <PlusIcon class="w-5 h-5" />
                        <span>Add New Product</span>
                    </Button>
                </Link>
            </div>


            <div v-if="products.data?.length" class="px-6 py-12 w-full">
                <div class="max-w-full mx-auto">

                    <div class="mb-8 flex flex-col sm:flex-row gap-4 items-center justify-between">
                        <div class="text-sm text-slate-600">
                            Showing {{products.data?.length }} of {{ products.total }} works
                        </div>
                        <div class="flex gap-3">
                            <Select v-model="sortOrder" @update:modelValue="onSortChange">
                                <SelectTrigger>
                                    <SelectValue placeholder="Sort By" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>

                                        <SelectItem value="desc">Latest</SelectItem>
                                        <SelectItem value="asc">Oldest</SelectItem>
                                    </SelectGroup>

                                </SelectContent>
                            </Select>
                        </div>
                    </div>


                    <div class="grid gap-8 w-full md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        <Link v-for="product in products.data"
                              :key="product.id"
                              :href="route('my-products.show', product.slug)"
                              class="group block">
                            <div class="bg-white rounded-2xl shadow-sm hover:shadow-2xl border
                             border-slate-100 overflow-hidden transform hover:-translate-y-2 transition-all duration-300 ease-out">

                                <div class="relative overflow-hidden h-56 bg-gradient-to-br from-slate-200 to-slate-300">
                                    <div class="relative w-full h-full">
                                        <img :src="product.featured" alt="Service image" class="w-full h-full object-cover" />
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent"></div>
                                    </div>

                                    <div class="absolute top-4 left-4 z-20">
                                        <span class="bg-white/90 backdrop-blur-sm text-slate-800 text-xs font-semibold px-3 py-1.5 rounded-full border border-white/20">
                                            {{ product.name }}
                                        </span>
                                    </div>


                                    <div v-if="product.is_negotiable" class="absolute bottom-4 left-4 z-20">
                                        <span class="bg-yellow-400/90 backdrop-blur-sm text-yellow-900 text-xs font-bold px-2 py-1 rounded-full flex items-center gap-1">
                                            <DropletOff class="w-3 h-3 fill-current" />
                                            Negotiable
                                        </span>
                                    </div>

                                </div>

                                <div class="p-6">

                                    <div class="flex items-center gap-3 mb-4">

                                        <div>
                                            <div class="flex items-center gap-1">
<!--                                                <CurrencyIcon class=" text-slate-400" />-->
                                                GHS
                                                <h3 class="font-bold text-slate-500 text-lg  line-clamp-2
                                                    group-hover:text-primary transition-colors duration-200">
                                                    {{ product.price }}
                                                </h3>
                                            </div>
                                        </div>
                                    </div>

                                    <h3 class="font-bold text-slate-900 text-lg mb-2 line-clamp-2
                                     group-hover:text-primary transition-colors duration-200">
                                        {{ product.name }}
                                    </h3>


                                    <p class="text-slate-600 text-sm leading-relaxed mb-4 truncate">
                                        {{ product.short_description }}
                                    </p>




                                    <Button class="w-full bg-secondary
                                      text-white font-semibold py-3 px-4
                                      rounded-xl transition-all duration-200 transform group-hover:scale-[1.02] flex items-center justify-center gap-2">
                                        <Eye class="w-4 h-4" />
                                        View Details
                                    </Button>
                                </div>
                            </div>
                        </Link>
                    </div>


                    <div class="mt-12 flex justify-center">
                        <Paginator v-if="products.total > products.per_page"
                                   :total="products.total"
                                   :per-page="products.per_page"
                                   :current-page="products.current_page"
                                   @page-change="goToPage"
                                   class="bg-white rounded-xl shadow-sm border border-slate-200 p-2"
                        />
                    </div>
                </div>
            </div>


            <div v-else class="min-h-[60vh] flex flex-col items-center justify-center text-center px-6">
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 w-24 h-24 rounded-full flex items-center justify-center mb-8 shadow-2xl">
                    <Hammer class="w-12 h-12 text-white" />
                </div>
                <h3 class="text-3xl font-bold text-slate-800 mb-4">No Service Listed Yet</h3>
                <p class="text-lg text-slate-600 max-w-md leading-relaxed mb-8">
                    Start showcasing your craftsmanship and connect with clients looking for quality artisan work.
                </p>
                <Link :href="route('my-products.create')">
                    <Button class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold px-8 py-4 rounded-xl shadow-lg transform hover:scale-105 transition-all duration-200 flex items-center gap-3">
                        <PlusIcon class="w-5 h-5" />
                        <span>List Your First Product</span>
                    </Button>
                </Link>
            </div>
        </div>
    </Layout>
</template>

<style scoped>


</style>
