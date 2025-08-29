<script setup lang="ts">
import Autoplay from 'embla-carousel-autoplay'
import SignboardCardV1 from '@/components/signboard/SignboardCardV1.vue';
import { Carousel, CarouselItem, CarouselContent } from '@/components/ui/carousel';
import { cn } from '@/lib/utils';
import { HTMLAttributes, onMounted, ref } from 'vue';
import { SignboardI } from '@/types';
import { getPromotedSignboards } from '@/lib/api';

const plugin = Autoplay({
    delay: 5000,
    stopOnMouseEnter: true,
    stopOnInteraction: false,
})

const props = defineProps<{
    itemsClass?: HTMLAttributes['class']
}>()

const signboards = ref<SignboardI[]>([])

onMounted(async ()=>{
    signboards.value = (await getPromotedSignboards()).signboards
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
            loop: true,
        }"
        orientation="vertical"
        v-if="signboards.length"
    >
        <CarouselContent :class="cn('mb-5', props.itemsClass)">
            <CarouselItem class="basis-1/3" v-for="signboard in signboards" :key="signboard.id">
                <SignboardCardV1
                    class="border border-secondary"
                    :signboard="signboard"
                    :carousel-plugin="plugin"
                    @popover-open="onPopoverStateChanged"
                />
            </CarouselItem>
        </CarouselContent>
    </Carousel>

</template>

<style scoped>

</style>
