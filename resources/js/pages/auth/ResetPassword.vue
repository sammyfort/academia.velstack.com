<script setup lang="ts">
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle
} from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle, Lock, CheckCircle } from 'lucide-vue-next';
import TextLink from '@/components/TextLink.vue';
import Layout from '@/layouts/GuestLayout.vue';

interface Props {
    token: string;
    email: string;
}

const props = defineProps<Props>();

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: ''
});

const submit = () => {
    form.post(route('password.store'), {

        onFinish: () => {
            form.reset('password', 'password_confirmation');
        }
    });
};
</script>

<template>
    <Layout
        title="Reset password"
        description="Please enter your new password below"
        :center-x="true"
        :center-y="true"
    >
        <Head title="Reset password" />

        <Card class="w-100 max-w-md mx-auto mt-0">
            <CardHeader class="text-center">
                <div class="mx-auto w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mb-4">
                    <Lock class="h-6 w-6 text-primary" />
                </div>
                <CardTitle class="text-2xl">Reset Password</CardTitle>
                <CardDescription>Enter a new password to regain access to your account.</CardDescription>
            </CardHeader>

            <CardContent>
                <form @submit.prevent="submit" class="space-y-4">

                    <div class="space-y-2">
                        <Label for="email">Email</Label>
                        <Input
                            id="email" type="email" v-model="form.email" readonly
                            :disabled="form.processing"
                        />
                        <p v-if="form.errors.email" class="text-sm text-destructive">
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <Label for="password">New Password</Label>
                        <Input
                            id="password" type="password" v-model="form.password"
                            autocomplete="new-password" placeholder="Enter new password"
                            :disabled="form.processing"
                        />
                        <p v-if="form.errors.password" class="text-sm text-destructive">
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <Label for="password_confirmation">Confirm Password</Label>
                        <Input
                            id="password_confirmation" type="password" v-model="form.password_confirmation" autocomplete="new-password"
                            placeholder="Re-enter password"
                            :disabled="form.processing"
                        />
                        <p v-if="form.errors.password_confirmation" class="text-sm text-destructive">
                            {{ form.errors.password_confirmation }}
                        </p>
                    </div>

                    <Button class="w-full" :disabled="form.processing">
                        <LoaderCircle v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                        {{ form.processing ? 'Resetting...' : 'Reset Password' }}
                    </Button>

                    <Alert v-if="form.recentlySuccessful" class="border-green-200 bg-green-50">
                        <CheckCircle class="h-4 w-4 text-green-600" />
                        <AlertDescription class="text-green-800">
                            Password successfully reset!
                        </AlertDescription>
                    </Alert>
                </form>
            </CardContent>

            <CardFooter class="flex flex-col space-y-4 text-center text-sm text-muted-foreground">
                <div>
                    Already reset your password?
                    <TextLink :href="route('login')" class="text-primary hover:underline">
                        Sign in
                    </TextLink>
                </div>
            </CardFooter>
        </Card>
    </Layout>
</template>
