<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Sheet, SheetContent, SheetDescription, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import InputText from '@/components/InputText.vue';
import { Watch, PhoneIncoming, MapPin } from 'lucide-vue-next';
import { useForm } from '@inertiajs/vue3';
import { toastSuccess } from '@/lib/helpers';


const form = useForm({
    name: "",
    mobile: "",
    email: "",
    message: "",
})

const submitForm = ()=>{
    form.post(route('contact-us'), {
        onSuccess: (response)=>{
            toastSuccess(response.props.message)
            form.reset()
        }
    })
}
</script>

<template>
    <Sheet>
        <SheetTrigger as-child>
            <slot />
        </SheetTrigger>
        <SheetContent side="bottom">
            <SheetDescription />
            <SheetTitle />

            <section>
                <div class="mx-auto max-w-7xl px-4 lg:py-14 py-8 sm:px-6 lg:px-8 overflow-y-scroll lg:overflow-y-auto h-150 lg:h-auto">
                    <div class="mb-4">
                        <div class="mb-6 max-w-3xl text-center sm:text-center md:mx-auto md:mb-12">
                            <p class="text-2xl font-semibold tracking-wide uppercase">Contact</p>
                            <h2 class="text-fade mb-4 text-lg font-bold tracking-tight">Get in Touch</h2>
                        </div>
                    </div>
                    <div class="flex items-stretch justify-center">
                        <div class="grid md:grid-cols-2">
                            <div class="h-full pr-6">
                                <p class="mt-3 mb-12 text-lg">
                                    We’d love to hear from you! Whether you have a question, feedback, or need support,
                                    feel free to reach out. Our team is always ready to assist.
                                </p>
                                <ul class="mb-6 md:mb-0">
                                    <li class="flex">
                                        <div class="flex h-10 w-10 items-center justify-center rounded bg-secondary text-primary">
                                            <MapPin />
                                        </div>
                                        <div class="mb-4 ml-4">
                                            <h3 class="mb-2 text-lg leading-6 font-medium text-gray-900">Our Address</h3>
                                            <p class="text-gray-600">Plot 18 Block KK, Kodua Junction, <br>Pankrono, Old Tafo Municipality</p>
                                            <p class="text-gray-600">Ashanti Ghana</p>
                                            <p class="text-gray-600">GPS: AO-T265-5061</p>
                                        </div>
                                    </li>
                                    <li class="flex">
                                        <div class="flex h-10 w-10 items-center justify-center rounded bg-secondary text-primary">
                                            <PhoneIncoming />
                                        </div>
                                        <div class="mb-4 ml-4">
                                            <h3 class="mb-2 text-lg leading-6 font-medium text-gray-900">Contact</h3>
                                            <p class="text-gray-600">Mobile: +233 200 990 004</p>
                                            <p class="text-gray-600">Mail: support@signboardgh.com</p>
                                        </div>
                                    </li>
                                    <li class="flex">
                                        <div class="flex h-10 w-10 items-center justify-center rounded bg-secondary text-primary">
                                            <Watch />
                                        </div>
                                        <div class="mb-4 ml-4">
                                            <h3 class="mb-2 text-lg leading-6 font-medium text-gray-900">Working hours</h3>
                                            <p class="text-gray-600">Sunday - Friday: 08:00 - 17:00</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="card h-fit max-w-6xl p-5 md:p-12" id="form">
                                <h2 class="mb-4 text-2xl font-semibold text-gray-800">We’d Love to Hear from You</h2>
                                <form @submit.prevent="submitForm">
                                    <div class="mb-6">
                                        <div class="grid grid-cols-1 gap-3 mx-0 mb-1 sm:mb-4">
                                            <InputText
                                                placeholder="Your name"
                                                model="name"
                                                :form="form"
                                                required
                                                label="Your Full Name"
                                            />
                                            <InputText
                                                placeholder="Your Mobile Number"
                                                model="mobile"
                                                :form="form"
                                                required
                                                label="Your Mobile Number"
                                            />
                                            <InputText
                                                placeholder="Your email address"
                                                model="email"
                                                :form="form"
                                                type="email"
                                                label="Your Email Address"
                                            />
                                            <InputText
                                                placeholder="Your message..."
                                                textarea
                                                model="message"
                                                :form="form"
                                                required
                                                label="Your Message"
                                            />
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <Button type="submit" :processing="form.processing" class="w-full">Send Message</Button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </SheetContent>
    </Sheet>
</template>

<style scoped></style>
