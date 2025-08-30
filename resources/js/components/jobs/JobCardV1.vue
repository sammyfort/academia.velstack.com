<script setup lang="ts">
import { Card, CardContent } from '@/components/ui/card';
import { MapPin, Clock, Briefcase, Flag } from 'lucide-vue-next';
import VerifiedBadge from '@/components/icons/VerifiedBadge.vue';
import { Button } from '@/components/ui/button';
import { JobI } from '@/types';
import { ucFirst } from '@/lib/helpers';
import { Link, router } from '@inertiajs/vue3';


type Props = {
    job: JobI
}

const props = defineProps<Props>()

</script>

<template>
    <Card @click="router.visit(route('jobs.show', job.slug))" class="shadow-none border-3 rounded-3xl border-gray-300 cursor-pointer hover:border-primary transform transition-all duration-300 hover:scale-99">
        <CardContent class="h-full">
            <div class="flex gap-7 flex-col h-full">

                <div class="flex items-center gap-3">
                    <div class="">
                        <img :src="job.company_logo" alt="" class="min-w-[60px] min-[60px] h-[60px] min-h-[60px] rounded-xl">
                    </div>
                    <div class="flex flex-col">
                        <div class="font-semibold">{{ job.company_name }}</div>
                        <div class="flex gap-1 items-center text-fade">
                            <MapPin :size="13"/> <span class="text-xs">{{ job.region.name }}</span>
                        </div>
                    </div>
<!--                    <div class="ms-auto">-->
<!--                        <VerifiedBadge :size="22"/>-->
<!--                    </div>-->
                </div>

                <div class="flex flex-col gap-2">
                    <div class="font-semibold">{{ job.title }}</div>
                    <div class="flex gap-1 items-center">
                        <Flag :size="13" class="text-primary"/> <span class="text-xs">{{ job.region.country.name }}</span>
                    </div>
                    <div class="flex gap-4 text-fade items-center">
                        <div class="flex gap-1 items-center text-primary">
                            <Briefcase :size="15"/>
                            <span class="text-xs">{{ ucFirst(job.work_mode) }}</span>
                        </div>
                        <div class="flex gap-1 items-center">
                            <Clock :size="15"/>
                            <div class="text-xs">{{ job.created_at }}</div>
                        </div>
                    </div>
                    <div class="text-fade text-sm">{{ job.summary }}</div>
                </div>

                <div class="mt-auto">
                    <Link as-child :href="route('jobs.show', job.slug)">
                        <Button class="w-full rounded-3xl">Apply</Button>
                    </Link>
                </div>
            </div>
        </CardContent>
    </Card>
</template>

<style scoped>

</style>
