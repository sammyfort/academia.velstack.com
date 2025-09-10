<script setup lang="ts">
import InputError from "@/components/InputError.vue";
import TextLink from "@/components/TextLink.vue";
import { Button } from "@/components/ui/button";
import { Checkbox } from "@/components/ui/checkbox";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Head, useForm } from "@inertiajs/vue3";
import { LoaderCircle, Quote } from "lucide-vue-next";
import Layout from "@/layouts/GuestLayout.vue";
import TypeIt from "typeit";
import { onMounted, ref } from "vue";
import InputText from "@/components/InputText.vue";
import { getRandomAuthImage } from "@/lib/helpers";
import { router } from "@inertiajs/vue3";
import { Card, CardContent } from "@/components/ui/card";
import InputSelect from "@/components/InputSelect.vue";
import SelectOption from "@/components/forms/SelectOption.vue";


const form = useForm({
  email: "",
  password: "",
  remember: false,
  login_as: "",
});

const bgImages = getRandomAuthImage();


const quotesRef = ref<HTMLElement | null>(null);
const currentQuoteIndex = ref(0);

const quotes = [
  "Streamline school operations effortlessly with automated processes and real-time insights.",
  "Enhance communication between students, parents, and staff through our integrated platform.",
  "Save time and resources with our easy-to-use system designed for efficient management.",
];



onMounted(() => {
  // Cycle through quotes
  setInterval(() => {
    currentQuoteIndex.value = (currentQuoteIndex.value + 1) % quotes.length;
  }, 4000);
});

const options = [
  { label: "Staff", value: "staff" },
  { label: "Parent", value: "parent" },
];
</script>


<template>
  <div
    class="bg-gradient-to-br from-slate-50 via-slate-100 to-slate-200 flex items-center justify-center p-4 lg:p-6 min-h-screen"
  >
    <div class="w-full max-w-6xl">
      <Card class="overflow-hidden shadow-sm border-0 bg-white backdrop-blur-sm">
        <CardContent class="p-0">
          <!-- Equal-height grid with fixed height -->
          <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[500px] lg:min-h-[600px]">
            <!-- COVER -->
            <div class="relative h-full min-h-[400px] lg:min-h-[600px]">
              <div
                class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                :style="{ backgroundImage: `url('/images/auth-one-bg.jpg')` }"
              ></div>
              <div class="absolute inset-0 bg-secondary/65"></div>
              <div class="absolute inset-0 opacity-10">
                <div
                  class="absolute inset-0"
                  style="
                    background-image: radial-gradient(
                      circle at 1px 1px,
                      white 1px,
                      transparent 0
                    );
                    background-size: 20px 20px;
                  "
                ></div>
              </div>

              <!-- Content -->
              <div
                class="relative z-20 flex flex-col justify-center h-full p-6 lg:p-10 text-white"
              >
                <div class="mx-auto lg:mx-0 max-w-md">
                  <!-- Logo -->
                  <div class="mb-6 lg:mb-8">
                    <div
                      class="h-10 w-32 lg:w-36 bg-white/15 backdrop-blur-sm rounded-lg flex items-center justify-center text-white font-bold text-base tracking-wide border border-white/20 mx-auto lg:mx-0"
                    >
                      OpenPortal
                    </div>
                  </div>

                  <!-- Quote Icon -->
                  <div class="mb-4 lg:mb-6">
                    <div
                      class="inline-flex items-center justify-center w-12 h-12 lg:w-14 lg:h-14 bg-white/15 rounded-full backdrop-blur-sm border border-white/20 mx-auto lg:mx-0"
                    >
                      <Quote class="h-6 w-6 lg:h-7 lg:w-7 text-white/90" />
                    </div>
                  </div>

                  <!-- Quote text -->
                  <div
                    class="min-h-[60px] lg:min-h-[80px] flex items-center justify-center lg:justify-start mb-6"
                  >
                    <transition
                      mode="out-in"
                      enter-active-class="transition-all duration-500"
                      leave-active-class="transition-all duration-300"
                      enter-from-class="opacity-0 transform translate-y-4"
                      leave-to-class="opacity-0 transform -translate-y-4"
                    >
                      <p
                        :key="currentQuoteIndex"
                        class="text-sm lg:text-base font-light leading-relaxed max-w-sm lg:max-w-md text-white/95 mx-auto lg:mx-0 text-center lg:text-left"
                      >
                        "{{ quotes[currentQuoteIndex] }}"
                      </p>
                    </transition>
                  </div>

                  <!-- Indicators -->
                  <div
                    class="flex justify-center lg:justify-start space-x-2 lg:space-x-3"
                  >
                    <button
                      v-for="(quote, index) in quotes"
                      :key="index"
                      @click="currentQuoteIndex = index"
                      :class="[
                        'rounded-full transition-all duration-300',
                        currentQuoteIndex === index
                          ? 'bg-white w-8 lg:w-10 h-1.5 lg:h-2'
                          : 'bg-white/40 w-1.5 lg:w-2 h-1.5 lg:h-2 hover:bg-white/70',
                      ]"
                      type="button"
                      aria-label="carousel indicator"
                    />
                  </div>
                </div>
              </div>
            </div>

            <!-- FORM -->
            <div class="flex items-center justify-center bg-white">
              <slot />
            </div>
               
          </div>
        </CardContent>
      </Card>

      <!-- Terms -->
      <div class="mt-6 text-center text-sm text-gray-500">
        By clicking continue, you agree to our
        <a
          href="#"
          class="text-primary hover:text-primary/80 underline underline-offset-4 font-medium"
          >Terms of Service</a
        >
        and
        <a
          href="#"
          class="text-primary hover:text-primary/80 underline underline-offset-4 font-medium"
          >Privacy Policy</a
        >.
      </div>
    </div>
  </div>
</template>