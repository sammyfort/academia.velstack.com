<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card'
import { Alert, AlertDescription } from '@/components/ui/alert'
import TextLink from '@/components/TextLink.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { LoaderCircle, Lock, CheckCircle } from 'lucide-vue-next'
import Layout from '@/layouts/GuestLayout.vue'

defineProps<{
    status?: string
}>()

const form = useForm({
    email: ''
})

const submit = () => {
    form.post(route('password.email'), {
        onSuccess: () => {
            form.reset('email')
        }
    })
}

</script>

<template>
    <Layout
        title="Forgot password"
        description="Enter your email to receive a password reset link"
        :center-x="true"
        :center-y="true"

    >
        <Head title="Forgot password" />

        <Card class="w-120 max-w-md mx-auto mt-0">
            <CardHeader class="text-center">
                <div class="mx-auto w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mb-4">
                    <Lock class="h-6 w-6 text-primary" />
                </div>
                <CardTitle class="text-2xl">Reset Password</CardTitle>
                <CardDescription>
                    Enter your email address and we'll send you a link to reset your password.
                </CardDescription>
            </CardHeader>

            <CardContent>
                <form @submit.prevent="submit" class="space-y-4">

                    <Alert v-if="status" class="border-green-200 bg-green-50">
                        <CheckCircle class="h-4 w-4 text-green-600" />
                        <AlertDescription class="text-green-800">
                            {{status}}
                        </AlertDescription>
                    </Alert>

                    <div class="space-y-2">
                        <Label for="email">Email address</Label>
                        <Input
                            id="email" type="email" v-model="form.email" placeholder="Enter your email address" :disabled="form.processing" autofocus
                        />
                        <p v-if="form.errors.email" class="text-sm text-destructive">
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <Button
                        type="submit"
                        class="w-full"
                        :disabled="form.processing"
                    >
                        <LoaderCircle v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                        {{ form.processing ? 'Sending...' : 'Send Reset Link' }}
                    </Button>


                </form>
            </CardContent>

            <CardFooter class="flex flex-col space-y-4 text-center text-sm text-muted-foreground">
                <div>
                    Remember your password?
                    <TextLink :href="route('login')" class="text-primary hover:underline">
                        Sign in
                    </TextLink>
                </div>

                <div class="flex items-center space-x-4">
                    <TextLink :href="route('register')" class="hover:underline">
                        Create account
                    </TextLink>
                    <span>•</span>
                    <TextLink href="/help" class="hover:underline">
                        Need help?
                    </TextLink>
                </div>
            </CardFooter>
        </Card>
    </Layout>
</template>
