<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import Layout from '@/layouts/Layout.vue';
import InputText from '@/components/InputText.vue';
import { onMounted, ref } from 'vue';
import TypeIt from 'typeit';
import { getRandomAuthImage } from '@/lib/helpers';
import CountryRegionSelector from '@/components/CountryRegionSelector.vue';
import { Label } from '@/components/ui/label';

const props = defineProps<{
    rfc: string|null,
}>()

const form = useForm({
    firstname: '',
    lastname: '',
    country_id: '',
    mobile: '',
    email: '',
    password: '',
    password_confirmation: '',
    rfc: props.rfc,
});

const bgImage = getRandomAuthImage()

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

const descriptionRef = ref<HTMLElement | null>(null)

onMounted(()=>{
    if (descriptionRef.value){
        new TypeIt(descriptionRef.value, {
            strings: [
                "Upload your signboard, reach more customers, or discover great businesses near you—it all starts with your account.",
                "Create your Signboard profile, showcase your location and reviews, and start attracting local customers who are ready to connect.",
                "Whether you're a customer looking for reliable businesses, or a business owner wanting more visibility—Signboard is your space."
            ],
            speed: 100,
            breakLines: false,
            loop: true
        }).go()
    }
})

const googleLogin = () => {
    window.location.href = route('auth.google')
}

</script>

<template>
    <Layout :center-y="true">
        <Head title="Register" />
        <div class="flex w-full max-w-[1400px] mx-auto">
            <div class="md:w-2/3 md:flex hidden">
                <div class="flex w-full">
                    <img :src="bgImage" alt="Signup" class="w-[70%]">
                </div>
            </div>
            <form @submit.prevent="submit" class="flex flex-col gap-6 w-full justify-center md:w-1/3">
                <div>
                    <div class="text-3xl font-bold text-fade">Join {{ $page.props.name }}</div>
                    <div class="text-fade min-h-[75px]" ref="descriptionRef"></div>
                </div>
                <div class="grid gap-6">
                    <InputText
                        :form="form"
                        model="firstname"
                        label="First Name"
                        autocomplete="first-name"
                        required
                        autofocus
                        placeholder="John"
                        :errors="form.errors"
                    />
                    <InputText
                        :form="form"
                        model="lastname"
                        label="Last Name"
                        autocomplete="last-name"
                        required
                        autofocus
                        placeholder="Doe"
                    />
                    <div class="flex flex-col gap-2">
                        <Label>Your Country <span class="text-destructive">*</span></Label>
                        <CountryRegionSelector
                            :form="form"
                            country-model="country_id"
                        />
                    </div>
                    <InputText
                        :form="form"
                        model="mobile"
                        label="Mobile Number"
                        autocomplete="mobile"
                        required
                        autofocus
                        placeholder="0542092800"
                    />
                    <InputText
                        :form="form"
                        model="email"
                        label="Email Address"
                        autocomplete="email"
                        required
                        autofocus
                        placeholder="email@mail.com"
                        type="email"
                    />
                    <InputText
                        :form="form"
                        model="password"
                        type="password"
                        required
                        autocomplete="new-password"
                        v-model="form.password"
                        placeholder="Password"
                        label="Password"
                    />
                    <InputText
                        :form="form"
                        model="password_confirmation"
                        label="Confirm Password"
                        required
                        autocomplete="new-password"
                        placeholder="Confirm password"
                        type="password"
                    />
                    <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Create Account
                    </Button>
                </div>
                <div class="w-full">
                    <button @click="googleLogin" type="button" class="w-full inline-flex items-center justify-center gap-3 py-3 text-sm font-normal text-gray-700 transition-colors bg-gray-200 rounded-lg px-7 hover:bg-gray-300 hover:text-gray-800">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.7511 10.1944C18.7511 9.47495 18.6915 8.94995 18.5626 8.40552H10.1797V11.6527H15.1003C15.0011 12.4597 14.4654 13.675 13.2749 14.4916L13.2582 14.6003L15.9087 16.6126L16.0924 16.6305C17.7788 15.1041 18.7511 12.8583 18.7511 10.1944Z" fill="#4285F4"></path>
                            <path d="M10.1788 18.75C12.5895 18.75 14.6133 17.9722 16.0915 16.6305L13.274 14.4916C12.5201 15.0068 11.5081 15.3666 10.1788 15.3666C7.81773 15.3666 5.81379 13.8402 5.09944 11.7305L4.99473 11.7392L2.23868 13.8295L2.20264 13.9277C3.67087 16.786 6.68674 18.75 10.1788 18.75Z" fill="#34A853"></path>
                            <path d="M5.10014 11.7305C4.91165 11.186 4.80257 10.6027 4.80257 9.99992C4.80257 9.3971 4.91165 8.81379 5.09022 8.26935L5.08523 8.1534L2.29464 6.02954L2.20333 6.0721C1.5982 7.25823 1.25098 8.5902 1.25098 9.99992C1.25098 11.4096 1.5982 12.7415 2.20333 13.9277L5.10014 11.7305Z" fill="#FBBC05"></path>
                            <path d="M10.1789 4.63331C11.8554 4.63331 12.9864 5.34303 13.6312 5.93612L16.1511 3.525C14.6035 2.11528 12.5895 1.25 10.1789 1.25C6.68676 1.25 3.67088 3.21387 2.20264 6.07218L5.08953 8.26943C5.81381 6.15972 7.81776 4.63331 10.1789 4.63331Z" fill="#EB4335"></path>
                        </svg>
                        Sign Up With Google
                    </button>
                </div>
                <div class="text-center text-sm text-muted-foreground">
                    Already have an account?
                    <TextLink :href="route('login')" class="underline underline-offset-4" :tabindex="6">Log in</TextLink>
                </div>
            </form>
        </div>
    </Layout>
</template>
