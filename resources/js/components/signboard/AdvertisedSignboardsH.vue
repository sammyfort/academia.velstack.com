<script setup lang="ts">
import Autoplay from 'embla-carousel-autoplay'
import SignboardCardV1 from '@/components/signboard/SignboardCardV1.vue';
import { Carousel, CarouselItem, CarouselContent } from '@/components/ui/carousel';
import { HTMLAttributes, onMounted, ref } from 'vue';
import { SignboardI } from '@/types';
import { getPromotedSignboards } from '@/lib/api';

import { cn } from '@/lib/utils';
import SignboardCardV1Skeleton from '@/components/skeletons/SignboardCardV1Skeleton.vue';


const plugin = Autoplay({
    delay: 3000,
    stopOnMouseEnter: true,
    stopOnInteraction: false,
})
const props = defineProps<{
    class?: HTMLAttributes['class'],
    containerClass?: HTMLAttributes['class'],
}>()
const signboards = ref<SignboardI[]>([])
const processing = ref<boolean>(false)

onMounted(async ()=>{
    processing.value = true
    signboards.value = (await getPromotedSignboards()).signboards
    processing.value = false
})

const popoverIsOpen = ref(false)

const onPopoverStateChanged = (isOpen: boolean) => {
    popoverIsOpen.value = isOpen
}

const resumeCarouselPlay = ()=>{
    if (popoverIsOpen.value){
        plugin.stop()
    }
    else {
        try {
            plugin.reset()
            plugin.play()
        } catch (err){}
    }
}

</script>

<template>
    <Carousel
        :plugins="[plugin]"
        @mouseenter="plugin.stop"
        @mouseleave="resumeCarouselPlay"
        :opts="{
            loop: true
        }"
        :class="cn('', props.containerClass)"
        class=" overflow-hidden"
        v-if="signboards.length"
    >
        <div class="mb-5">
            <slot />
        </div>
        <CarouselContent class="mb-5 shadow-none max-w-sm md:max-w-md lg:max-w-[100%]">
            <CarouselItem class="md:basis-1/2 lg:basis-1/4" v-for="signboard in signboards" :key="signboard.id">
                <template v-if="processing">
                    <SignboardCardV1Skeleton
                        v-for="x in [1,2,3]"
                        :key="x"
                    />
                </template>
                <template v-else>
                    <SignboardCardV1
                        :is-advertised="true"
                        :class="cn('border border-secondary', props.class)"
                        :signboard="signboard"
                        :carousel-plugin="plugin"
                        @popover-open="onPopoverStateChanged"
                    />
                </template>
            </CarouselItem>
        </CarouselContent>
    </Carousel>
</template>

<style scoped>

</style>
