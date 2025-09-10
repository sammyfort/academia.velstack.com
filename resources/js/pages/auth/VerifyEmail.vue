<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import Layout from '@/layouts/GuestLayout.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { toastSuccess } from '@/lib/helpers';

defineProps<{
    status?: string;
}>();

if (status){
    toastSuccess("A new verification link has been sent to the email address you provided during registration.")
}

const form = useForm({});
const page = usePage()
const user = page.props.auth.user

const openInbox = ()=>{
    window.open("https://mail.google.com/mail/u/0/#inbox", '_blank')
}

const submit = () => {
    form.post(route('verification.send'), {
        onFinish: ()=>{
            toastSuccess("A new verification link has been sent to the email address you provided during registration.")
        }
    });
};
</script>

<template>
    <Layout :center-x="true">
        <Head title="Email verification" />
        <Card class="border-primary" :class="{'border-green-500': status}">
            <CardHeader class="text-center">
                <CardTitle class="text-2xl">Verify Your Account</CardTitle>
            </CardHeader>
            <CardContent>
                <div class="text-center">
                    Hey {{ user.lastname }}, let's get you set up! <br/>
                    Before we move forward, please verify your account. 🚀 <br/>
                    We've sent a verification link to your email! 📩 <br/>
                    <span class="underline underline-offset-4 cursor-pointer" @click="openInbox">
                            Check your inbox
                        </span> and click the link to verify your account. 🚀
                </div>
                <div class="text-center mt-3 mb-1">
                    If you did not receive it, no worries! Click the link below to request a new one. 🔄📩
                </div>
                <form @submit.prevent="submit" class="text-center">
                    <Button :disabled="form.processing" variant="secondary" :processing="form.processing">
                        Resend verification email
                    </Button>
                </form>
            </CardContent>
        </Card>
    </Layout>
</template>
