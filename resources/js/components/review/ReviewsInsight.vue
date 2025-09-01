<script setup lang="ts">
import { AverageRatingsI, RatableI, RatableTypesI, RatingsDistributionI } from '@/types';
import StarRating from 'vue-star-rating'
import { number_format } from '@/lib/helpers';
import { Button } from '@/components/ui/button';
import { usePage } from '@inertiajs/vue3';
import { computed, HTMLAttributes, ref } from 'vue';
import { cn } from '@/lib/utils';
import RatePopover from '@/components/RatePopover.vue';
import RateDialog from '@/components/RateDialog.vue';

const page = usePage()
const user = computed(()=> page.props.auth.user)

type Props = {
    ratable: RatableI,
    ratings: AverageRatingsI,
    distributions: RatingsDistributionI,
    class?: HTMLAttributes['class'],
    ratable_type: RatableTypesI
}

const props = defineProps<Props>()
const totalAverageRating = ref<number>(props.ratable.total_average_rating)

</script>

<template>
    <div :class="cn('p-4 border-t text-fade', props.class)">
        <div class="flex items-center">
            <div class="font-medium text-lg">Reviews</div>
            <div v-if="user?.id !== ratable.user_id" class="ms-auto md:block hidden">
                <RatePopover :ratable="ratable" :ratable_type="ratable_type" @rated="(value)=>{totalAverageRating = value}">
                    <Button size="sm">{{ props.ratable.reviews?.length ? 'Update review' : 'Write review' }}</Button>
                </RatePopover>
            </div>
            <div class="ms-auto md:hidden" v-if="user?.id != ratable.user_id">
                <RateDialog :ratable="ratable" :ratable_type="ratable_type" @rated="(value)=>{totalAverageRating = value}">
                    <Button size="sm">{{ props.ratable.reviews?.length ? 'Update review' : 'Write review' }}</Button>
                </RateDialog>
            </div>
        </div>
        <div class="my-5 flex flex-col items-start gap-4">
            <div class="flex items-start gap-2 lg:gap-4">
                <div class="text-5xl">{{ number_format(ratable.total_average_rating, 1) }}</div>
                <StarRating
                    :star-size="37"
                    :show-rating="false"
                    :rating="totalAverageRating"
                    read-only
                    active-color="#009689"
                    :padding="3"
                    class="md:w-1/3 w-full"
                    :key="`rating-card-${ratable.id}`"
                    :increment="0.01"
                />
            </div>
            <div class="">
                ({{ ratable.reviews_count }} total reviews)
            </div>
            <div class="w-full">
                <div class="text-xl mb-3 font-medium text-secondary">Insights</div>
                <div class="flex flex-wrap">
                    <div class="w-full md:w-1/2">
                        <div class="font-medium mb-2">Ratings By Category</div>
                        <div class="flex flex-col gap-1 text-sm">
                            <div class="flex gap-3">
                                <div>{{ number_format(ratings.overall, 1) }}</div>
                                <div>Overall</div>
                            </div>
                            <div class="flex gap-3">
                                <div>{{ number_format(ratings.customer_service, 1) }}</div>
                                <div>Customer Service</div>
                            </div>
                            <div class="flex gap-3">
                                <div>{{ number_format(ratings.quality, 1) }}</div>
                                <div>Quality</div>
                            </div>
                            <div class="flex gap-3">
                                <div>{{ number_format(ratings.price, 1) }}</div>
                                <div>Price</div>
                            </div>
                            <div class="flex gap-3">
                                <div>{{ number_format(ratings.communication, 1) }}</div>
                                <div>Communication</div>
                            </div>
                            <div class="flex gap-3">
                                <div>{{ number_format(ratings.speed, 1) }}</div>
                                <div>Speed</div>
                            </div>
                        </div>
                    </div>
                    <div class="w-full md:w-1/2 mt-7 md:mt-0">
                        <div class="font-medium mb-2">Ratings Distribution</div>
                        <div class="flex flex-col gap-1 text-sm">
                            <div class="flex items-center gap-4">
                                <div class="w-[50px]">5 stars</div>
                                <div class="flex bg-gray-200 w-[150px]">
                                    <div class="bg-gray-400 h-2" :style="{width: `${distributions['5']}%`}"></div>
                                </div>
                                <div>{{ distributions['5'] }}%</div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-[50px]">4 stars</div>
                                <div class="flex bg-gray-200 w-[150px]">
                                    <div class="bg-gray-400 h-2" :style="{width: `${distributions['4']}%`}"></div>
                                </div>
                                <div>{{ distributions['4'] }}%</div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-[50px]">3 stars</div>
                                <div class="flex bg-gray-200 w-[150px]">
                                    <div class="bg-gray-400 h-2" :style="{width: `${distributions['3']}%`}"></div>
                                </div>
                                <div>{{ distributions['3'] }}%</div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-[50px]">2 stars</div>
                                <div class="flex bg-gray-200 w-[150px]">
                                    <div class="bg-gray-400 h-2" :style="{width: `${distributions['2']}%`}"></div>
                                </div>
                                <div>{{ distributions['2'] }}%</div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-[50px]">1 star</div>
                                <div class="flex bg-gray-200 w-[150px]">
                                    <div class="bg-gray-400 h-2" :style="{width: `${distributions['1']}%`}"></div>
                                </div>
                                <div>{{ distributions['1'] }}%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full flex flex-col gap-3 mt-3">
        </div>
    </div>
</template>

<style scoped>

</style>
