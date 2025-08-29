<script setup lang="ts">
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogDescription,
    DialogFooter,
    DialogClose,
} from "@/components/ui/dialog"
import { ref, onMounted } from "vue"
import { Button } from '@/components/ui/button';


const showPopup = ref(false)

function isInAppBrowser() {
    const ua = navigator.userAgent || navigator.vendor || window.opera
    return /wv|FBAN|FBAV|Instagram|Line|Twitter|OKHttp|WebView/i.test(ua)
}

function isAndroid() {
    return /android/i.test(navigator.userAgent)
}

onMounted(() => {
    const dismissed = sessionStorage.getItem("appDownloadDismissed")
    if (!dismissed && isAndroid() && !isInAppBrowser()) {
        showPopup.value = true
    }
})

function close() {
    showPopup.value = false
    sessionStorage.setItem("appDownloadDismissed", "true")
}
</script>

<template>
    <Dialog v-model:open="showPopup">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle>Get our Mobile App</DialogTitle>
                <DialogDescription>
                    For the best experience, download our mobile app.
                </DialogDescription>
            </DialogHeader>

            <div class="flex justify-center">
                <Button as-child>
                    <a
                        :href="route('download.apk')"
                        target="_blank"
                        class="btn btn-outline-platinum w-full"
                    >
                        Download Now
                    </a>
                </Button>
            </div>

            <DialogFooter>
                <DialogClose as-child>
                    <div class="text-center">
                        <span
                            class="px-2 underline underline-offset-5"
                            @click="close"
                        >Maybe later</span>
                    </div>
                </DialogClose>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>

<style scoped>

</style>
