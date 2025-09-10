<script setup lang="ts">
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from '@/components/ui/accordion'
import Layout from '@/layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';

const intenseC = "font-bold text-secondary"
</script>

<template>
    <Head title="FAQ" />
    <Layout>
        <div class="w-full">
            <div class="flex justify-center w-full">
                <section class="bg-white py-12 px-6 sm:px-12 lg:px-24 text-gray-800 w-full">
                    <div class="max-w-5xl mx-auto">
                        <h1 class="text-4xl font-bold mb-6 text-secondary text-center">Frequently Asked Questions</h1>

                        <Accordion type="single" collapsible class="space-y-4">

                            <AccordionItem value="item-1">
                                <AccordionTrigger class="text-lg font-semibold text-fade">
                                    How do I register on Alexoa?
                                </AccordionTrigger>
                                <AccordionContent>
                                    Click the <span :class="intenseC">"Sign Up"</span> button on the homepage and choose to register
                                    as a <span :class="intenseC">general user</span> or <span :class="intenseC">business owner</span>.
                                    You can sign up using your <span :class="intenseC">email and password</span> or your <span :class="intenseC">Google account</span> (if enabled).
                                </AccordionContent>
                            </AccordionItem>

                            <AccordionItem value="item-2">
                                <AccordionTrigger class="text-lg font-semibold text-fade">
                                    What can I post on Alexoa?
                                </AccordionTrigger>
                                <AccordionContent>
                                    Alexoa allows you to post:
                                    <ul class="list-disc pl-5 mt-2 space-y-1">
                                        <li><span :class="intenseC">Business listings</span> with name, category, location, and signboard image</li>
                                        <li><span :class="intenseC">Service listings</span> (e.g., carpenters, plumbers) with contact info and description</li>
                                        <li><span :class="intenseC">Products for sale</span> with photos, price, and item condition</li>
                                        <li><span :class="intenseC">Job postings</span> including job title, type, and how to apply</li>
                                    </ul>
                                </AccordionContent>
                            </AccordionItem>

                            <AccordionItem value="item-3">
                                <AccordionTrigger class="text-lg font-semibold text-fade">
                                    Can I search for listings by location?
                                </AccordionTrigger>
                                <AccordionContent>
                                    Yes! You can search and filter listings by <span :class="intenseC">location, category, or keywords</span>.
                                    Alexoa also includes a <span :class="intenseC">map view</span> to help you explore businesses and services in specific areas.
                                </AccordionContent>
                            </AccordionItem>

                            <AccordionItem value="item-4">
                                <AccordionTrigger class="text-lg font-semibold text-fade">
                                    How do reviews and ratings work?
                                </AccordionTrigger>
                                <AccordionContent>
                                    Registered users can leave a <span :class="intenseC">star rating</span> and a <span :class="intenseC">text review</span>
                                    on business and service provider profiles. Ratings help others discover reliable services
                                    and businesses through <span :class="intenseC">community feedback</span>.
                                </AccordionContent>
                            </AccordionItem>

                            <AccordionItem value="item-5">
                                <AccordionTrigger class="text-lg font-semibold text-fade">
                                    How are profile views tracked?
                                </AccordionTrigger>
                                <AccordionContent>
                                    Listings show the total number of <span :class="intenseC">views</span> they receive.
                                    Business owners and service providers can see view counts in their dashboards,
                                    including <span :class="intenseC">unique views</span> (if enabled) to understand engagement better.
                                </AccordionContent>
                            </AccordionItem>

                            <AccordionItem value="item-6">
                                <AccordionTrigger class="text-lg font-semibold text-fade">
                                    How do I contact someone on Alexoa?
                                </AccordionTrigger>
                                <AccordionContent>
                                    Each listing provides <span :class="intenseC">contact details</span> such as phone or email.
                                    For services, products, and job posts, communication is handled <span :class="intenseC">outside the platform</span>,
                                    so feel free to call or message the lister directly.
                                </AccordionContent>
                            </AccordionItem>

                            <AccordionItem value="item-7">
                                <AccordionTrigger class="text-lg font-semibold text-fade">
                                    Is there a cost to use Alexoa?
                                </AccordionTrigger>
                                <AccordionContent>
                                    Basic features like creating listings, browsing, and contacting others are <span :class="intenseC">free</span>.
                                    If you'd like to reach more people, you can use our <span :class="intenseC">promotion feature</span> for enhanced visibility.
                                </AccordionContent>
                            </AccordionItem>

                        </Accordion>
                    </div>
                </section>
            </div>
        </div>
    </Layout>
</template>

<style scoped>
</style>
