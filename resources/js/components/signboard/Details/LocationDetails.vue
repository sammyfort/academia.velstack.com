<script setup lang="ts">
import { MapPin, MapPinned, Pin, MapPinHouse, Landmark, LocateFixed, Map } from 'lucide-vue-next';
import { SignboardI } from '@/types';
import ShareToSocials from '@/components/ShareToSocials.vue';


type Props = {
    signboard: SignboardI
}
defineProps<Props>()

</script>

<template>
    <div class="flex flex-col gap-3">
        <div class="text-fade font-semibold">Location</div>
        <div class="flex flex-col gap-3">
            <div class="flex flex-wrap gap-2">
                <div class="flex gap-1 items-center">
                    <MapPin :size="17" class="text-secondary"/>
                    <span class="font-medium">Region:</span>
                </div>
                <span class="break-all">{{ signboard.region.name }}</span>
            </div>
            <div class="flex flex-wrap gap-2">
                <div class="flex gap-1 items-center">
                    <MapPinned :size="17" class="text-secondary"/>
                    <span class="font-medium">Town:</span>
                </div>
                <span class="break-all">{{ signboard.town }}</span>
            </div>
            <div class="flex flex-wrap gap-2">
                <div class="flex gap-1 items-center">
                    <Pin :size="17" class="text-secondary"/>
                    <span class="font-medium">Street:</span>
                </div>
                <span class="break-all">{{ signboard.street }}</span>
            </div>
            <div class="flex flex-wrap gap-2">
                <div class="flex gap-1 items-center">
                    <Landmark :size="17" class="text-secondary"/>
                    <span class="font-medium">Landmark:</span>
                </div>
                <span class="break-all">{{ signboard.landmark }}</span>
            </div>
            <div class="flex flex-wrap gap-2">
                <div class="flex gap-1 items-center">
                    <MapPinHouse :size="17" class="text-secondary"/>
                    <span class="font-medium">Block:</span>
                </div>
                <span class="break-all">{{ signboard.blk_number }}</span>
            </div>
            <div class="flex flex-wrap gap-2" v-if="signboard.gps_lat && signboard.gps_lon">
                <div class="flex gap-1 items-center">
                    <LocateFixed :size="17" class="text-secondary"/>
                    <span class="font-medium">GPS:</span>
                </div>
                <a
                    :href="`https://maps.google.com/?q=${signboard.gps_lat},${signboard.gps_lon}`"
                    target="_blank"
                    class="flex items-center gap-1 text-primary underline"
                >
                    <Map :size="15" />
                    <span class="font-medium text-sm">View on Google Maps</span>
                </a>
            </div>
            <div class="flex flex-wrap gap-2">
                <ShareToSocials />
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
