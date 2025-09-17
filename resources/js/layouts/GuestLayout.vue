<script setup lang="ts">
import { Card, CardContent } from "@/components/ui/card";
import { Quote } from "lucide-vue-next";
import { ref, onMounted } from "vue";
import { getRandomAuthImage } from "@/lib/helpers";

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
    currentQuoteIndex.value =
      (currentQuoteIndex.value + 1) % quotes.length;
  }, 4000);
});
</script>

<template>
     <div
            class="min-h-screen bg-gradient-to-br from-teal-400 via-purple-500 to-purple-600 flex items-center justify-center p-4 lg:p-4"
        >
            <div class="w-full max-w-6xl">
                
                    <CardContent class="p-0">
                        <!-- Equal-height grid with flexible height -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[400px]">
                            <!-- COVER -->
                            <div class="relative flex flex-col justify-center min-h-[300px] lg:min-h-[400px]">
                                <div
                                    class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                                    :style="{ backgroundImage: `url('/images/auth-one-bg.jpg')` }"
                                ></div>
                                <div class="absolute inset-0 bg-gradient-to-br from-purple-600/80 via-purple-500/70 to-teal-400/60"></div>
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
                                <div class="relative z-20 flex flex-col justify-center p-6 lg:p-8 text-white">
                                    <div class="mx-auto lg:mx-0 max-w-md">
                                        <!-- Logo -->
                                        <div class="mb-6 lg:mb-8">
                                            <div
                                                class="h-10 w-36 lg:h-12 lg:w-40 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center text-white font-bold text-base lg:text-lg tracking-wide border border-white/30 mx-auto lg:mx-0"
                                            >
                                                OpenPortal
                                            </div>
                                        </div>

                                        <!-- Quote Icon -->
                                        <div class="mb-4 lg:mb-6">
                                            <div
                                                class="inline-flex items-center justify-center w-12 h-12 lg:w-14 lg:h-14 bg-white/20 rounded-full backdrop-blur-sm border border-white/30 mx-auto lg:mx-0"
                                            >
                                                <Quote class="h-6 w-6 lg:h-7 lg:w-7 text-white" />
                                            </div>
                                        </div>

                                        <!-- Quote text -->
                                        <div
                                            class="min-h-[60px] lg:min-h-[80px] flex items-center justify-center lg:justify-start mb-6 lg:mb-8"
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
                                                    class="text-sm lg:text-base font-light leading-relaxed max-w-sm lg:max-w-md text-white mx-auto lg:mx-0 text-center lg:text-left"
                                                >
                                                    "{{ quotes[currentQuoteIndex] }}"
                                                </p>
                                            </transition>
                                        </div>

                                        <!-- Indicators -->
                                        <div class="flex justify-center lg:justify-start space-x-2">
                                            <button
                                                v-for="(quote, index) in quotes"
                                                :key="index"
                                                @click="currentQuoteIndex = index"
                                                :class="[
                        'rounded-full transition-all duration-300',
                        currentQuoteIndex === index
                          ? 'bg-white w-8 lg:w-10 h-1.5 lg:h-2'
                          : 'bg-white/50 w-1.5 lg:w-2 h-1.5 lg:h-2 hover:bg-white/80',
                      ]"
                                                type="button"
                                                aria-label="carousel indicator"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- FORM -->
                         

                            <slot name="content"/>

                        </div>
                    </CardContent>
                

                <!-- Terms -->
                <div class="mt-6 text-center text-sm text-white/90">
                    By clicking continue, you agree to our
                    <a
                        href="#"
                        class="text-white hover:text-white/80 underline underline-offset-4 font-medium"
                    >Terms of Service</a
                    >
                    and
                    <a
                        href="#"
                        class="text-white hover:text-white/80 underline underline-offset-4 font-medium"
                    >Privacy Policy</a
                    >.
                </div>

            </div>
        </div>
</template>
