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
    class="bg-gradient-to-br from-slate-50 via-slate-100 to-slate-200 flex items-center justify-center p-4 lg:p-4 "
  >
    <div class="w-full max-w-6xl">
      <Card class="overflow-hidden shadow-sm border-0 bg-white backdrop-blur-sm">
        <CardContent class="p-0">
          <!-- Equal-height grid with flexible height -->
          <div class="grid grid-cols-1 lg:grid-cols-2">
            <!-- COVER -->
            <div class="relative flex flex-col justify-center min-h-[400px]">
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
              <div class="relative z-20 flex flex-col justify-center p-6 lg:p-8 text-white">
                <div class="mx-auto lg:mx-0 max-w-md">
                  <!-- Logo -->
                  <div class="mb-4 lg:mb-6">
                    <div
                      class="h-8 w-28 lg:h-10 lg:w-32 bg-white/15 backdrop-blur-sm rounded-lg flex items-center justify-center text-white font-bold text-sm lg:text-base tracking-wide border border-white/20 mx-auto lg:mx-0"
                    >
                      OpenPortal
                    </div>
                  </div>

                  <!-- Quote Icon -->
                  <div class="mb-3 lg:mb-4">
                    <div
                      class="inline-flex items-center justify-center w-10 h-10 lg:w-12 lg:h-12 bg-white/15 rounded-full backdrop-blur-sm border border-white/20 mx-auto lg:mx-0"
                    >
                      <Quote class="h-5 w-5 lg:h-6 lg:w-6 text-white/90" />
                    </div>
                  </div>

                  <!-- Quote text -->
                  <div
                    class="min-h-[50px] lg:min-h-[60px] flex items-center justify-center lg:justify-start mb-4 lg:mb-5"
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
                        class="text-xs lg:text-sm font-light leading-relaxed max-w-xs lg:max-w-sm text-white/95 mx-auto lg:mx-0 text-center lg:text-left"
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
                          ? 'bg-white w-6 lg:w-8 h-1 lg:h-1.5'
                          : 'bg-white/40 w-1 lg:w-1.5 h-1 lg:h-1.5 hover:bg-white/70',
                      ]"
                      type="button"
                      aria-label="carousel indicator"
                    />
                  </div>
                </div>
              </div>
            </div>

            <!-- FORM -->
            <div class="flex items-center justify-center bg-white lg:py-12">
              <div class="px-8 w-full max-w-lg">
                <slot name="content"/>
              </div>
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