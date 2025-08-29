<script setup lang="ts">
import TopNavbar from '@/layouts/TopNavbar.vue';
import { Toaster } from '@/components/ui/sonner'
import { HTMLAttributes, onMounted } from 'vue';
import GLightbox from 'glightbox';
import { cn } from '@/lib/utils';
import Footer from '@/layouts/Footer.vue';
import DownloadAPKPopup from '@/components/DownloadAPKPopup.vue';

type Props = {
    centerX?: boolean,
    centerY?: boolean,
    class?: HTMLAttributes['class'],
    style?: HTMLAttributes['style'],
    isFullWidth?: boolean,
}

const props = withDefaults(defineProps<Props>(), {
    centerX: false,
    centerY: false,
    isFullWidth: false,
})

onMounted(() => {
    GLightbox({
        selector: '.glightbox',
    })
})
</script>

<template>
    <Toaster position="top-right" richColors/>
    <DownloadAPKPopup />
    <div :class="cn(props.class, 'flex min-h-screen flex-col items-center p-3  lg:justify-center lg:p-8 page')" :style="props.style">
        <a class="hidden bg-gradient-to-b from-blue-500 to-blue-700 px-2 py-3 text-center text-sm text-white md:block lg:font-[500] w-full"
           href="/pricing"
           v-if="$page.props.announcement"
           v-html="$page.props.announcement.content"
        >
        </a>
        <TopNavbar :class="{'max-w-[1700px]': props.isFullWidth, 'max-w-[1900px]': !props.isFullWidth}"/>
        <div class="flex w-full opacity-100 mt-5 transition-opacity duration-750 lg:grow starting:opacity-0"
             :class="{'justify-center': props.centerX, 'items-center': props.centerY, 'max-w-[1900px]': !props.isFullWidth}"
        >
            <main v-if="props.centerX">
                <slot />
            </main>
            <slot v-else/>
        </div>
    </div>
    <Footer />
</template>

<style scoped>

</style>
