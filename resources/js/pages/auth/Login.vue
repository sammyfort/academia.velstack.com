<script setup lang="ts">
import InputError from "@/components/InputError.vue";
import TextLink from "@/components/TextLink.vue";
import { Button } from "@/components/ui/button";
import { Checkbox } from "@/components/ui/checkbox";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { LoaderCircle, Quote } from "lucide-vue-next";
import GuestLayout from "@/layouts/GuestLayout.vue";
import InputText from "@/components/InputText.vue";
import SelectOption from "@/components/forms/SelectOption.vue";
import { Card, CardContent } from "@/components/ui/card";
import { getRandomAuthImage } from "@/lib/helpers";
import { ref, onMounted } from "vue";
const form = useForm({
  email: "",
  password: "",
  remember: false,
  login_as: "staff",
});

const login = () => {
  form.post(route("login"), {
    onFinish: () => form.reset("password"),
  });
};

const options = [
  { label: "Staff", value: "staff" },
  { label: "Parent", value: "parent" },
];

// Random background image
const bgImage = getRandomAuthImage();

// Quotes + cycling
const quotes = [
  "Streamline school operations effortlessly with automated processes and real-time insights.",
  "Enhance communication between students, parents, and staff through our integrated platform.",
  "Save time and resources with our easy-to-use system designed for efficient management.",
];

const currentQuoteIndex = ref(0);

onMounted(() => {
  setInterval(() => {
    currentQuoteIndex.value = (currentQuoteIndex.value + 1) % quotes.length;
  }, 4000);
});
</script>

<template>
  <Head title="Log in" />
  <GuestLayout>
    <template #content>
      <div
        class="flex items-center justify-center bg-background min-h-[300px] lg:min-h-[400px]"
      >
        <div class="px-8 py-4 w-full max-w-lg">
          <form @submit.prevent="login" class="space-y-3">
            <div class="text-center space-y-1 mb-3">
              <h1 class="text-lg lg:text-xl font-bold text-foreground tracking-tight">
                Welcome Back!
              </h1>
              <p class="text-foreground text-xs">Sign in to continue to OpenPortal.</p>
            </div>

            <div
              v-if="$page.props.status"
              class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-center text-sm font-medium"
            >
              {{ $page.props.status }}
            </div>

            <div class="space-y-2">
              <SelectOption
                :form="form"
                model="login_as"
                required
                v-model="form.login_as"
                :options="options"
                placeholder="Select login type"
                label="Login As"
                :error="form.errors.login_as"
              />
            </div>

            <div class="space-y-2">
              <InputText
                :form="form"
                model="email"
                required
                autofocus
                autocomplete="email"
                v-model="form.email"
                placeholder="Enter your email"
                label="Email"
              />
            </div>

            <div class="space-y-2">
              <div class="flex items-center justify-between">
                <Label for="password" class="text-sm font-medium text-foreground"
                  >Password</Label
                >
                <Link
                  :href="route('password.request')"
                  class="text-sm text-primary hover:text-primary/60 font-medium"
                  >Forgot password?</Link
                >
              </div>

              <Input
                id="password"
                type="password"
                required
                autocomplete="current-password"
                v-model="form.password"
                placeholder="Enter your password"
              />
              <InputError :message="form.errors.password" />
            </div>

            <div class="flex items-center space-x-2 pt-2">
              <Checkbox id="remember" v-model:checked="form.remember" />
              <Label for="remember" class="text-sm text-foreground cursor-pointer"
                >Remember me</Label
              >
            </div>

            <Button
              type="submit"
              class="w-full h-9 text-sm font-semibold bg-secondary text-white"
              :disabled="form.processing"
            >
              <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin mr-2" />
              Sign In
            </Button>

            <div class="relative my-3">
              <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-foreground"></div>
              </div>
              <div class="relative flex justify-center text-xs">
                <span class="px-3 bg-background text-foreground">Or continue with</span>
              </div>
            </div>

            <Button
              variant="outline"
              type="button"
              class="w-full h-9 border-2 border-foreground hover:bg-background text-muted-foreground text-sm"
              @click="window.location.href = route('auth.google')"
            >
              <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24">
                <path
                  d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z"
                  fill="currentColor"
                />
              </svg>
              Login with Google
            </Button>

            <div class="text-center text-xs text-foreground">
              Don't have an account?
              <a href="#" class="font-semibold text-primary hover:text-primary/50 ml-1"
                >Sign up</a
              >
            </div>
          </form>
        </div>
      </div>
    </template>
  </GuestLayout>
</template>
