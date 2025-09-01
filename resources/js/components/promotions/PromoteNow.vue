<script setup lang="ts">

import { Rocket } from 'lucide-vue-next';
import InputSelect from '@/components/InputSelect.vue';
import { InputSelectOption, ModelI, PromotionPlanI } from '@/types';
import { useForm, usePage } from '@inertiajs/vue3';
import { toastError } from '@/lib/helpers';
import { computed, ref } from 'vue';
import { PromotableE } from '@/lib/enums';
import { Button } from '@/components/ui/button';

const props = defineProps<{
    promotable: ModelI & any,
    plans: PromotionPlanI[],
    promotableType: PromotableE
}>();

const showPlans = ref(false);

const page = usePage()
const user = computed(()=> page.props.auth.user)

const form = useForm({
    plan_id: null,
    promotable_id: props.promotable.id,
    promotable_type: props.promotableType
});

const canPromoteWithPoints = computed(()=>{
    const selectedPlan = props.plans.find(plan => plan.id === form.plan_id)
    if (!selectedPlan) return false
    return selectedPlan.price < user.value.points_in_cedis
})

const promoteUsingFiat = () => {
    form.post(route('promotions.payment.initialize'), {
        replace: true,
        onSuccess: (response) => {
            const authorizationUrl = response.props.data.authorization_url;
            if (authorizationUrl) {
                window.location = authorizationUrl;
            } else {
                toastError('Payment initialization failed, pleased reload the page and try again');
            }
        },
    });
};

const promoteUsingPoints = () => {
    if (!canPromoteWithPoints.value) return

    form.post(route('promotions.payment.points'), {
        onSuccess: (response) => {
            console.log(response.props)
        },
    });
};

</script>

<template>
    <div class="mb-4 border-t border-gray-100 pt-4 p-3 shadow-lg shadow-primary">
        <div class="mb-3 flex items-center justify-between">
            <h4 class="font-black animate-bounce text-secondary text-2xl">Promote Now</h4>
            <button
                @click="showPlans = !showPlans"
                class="text-sm font-medium text-primary transition-colors hover:text-primary/80"
            >
                {{ showPlans ? 'Select Plan' : 'View Plans' }}
            </button>
        </div>
        <div class="mb-3 text-sm italic text-primary font-bold">
            Promote now to reach more audience !
        </div>
        <div v-if="showPlans" class="space-y-2">
            <div v-for="plan in props.plans.slice(0, 3)" :key="plan.id"
                 class="flex items-center justify-between text-sm">
                <span class="text-gray-600">{{ plan.number_of_days }} Days</span>
                <span class="font-semibold text-primary">{{ plan.price }}</span>
            </div>
        </div>

        <div v-else class="relative">
            <InputSelect
                label="Select Plan"
                :form="form"
                model="plan_id"
                :options="plans.map((plan: PromotionPlanI): InputSelectOption => ({
                         label: `${plan.name} - ${plan.number_of_days} Days - ₵${plan.price}`,
                         value: plan.id,
                      }))"
                required
                searchable
            />
        </div>

        <Button
            v-if="form.plan_id"
            @click="promoteUsingPoints"
            :processing="form.processing"
            class="mt-4 w-full"
            variant="secondary"
            :disabled="!canPromoteWithPoints"
        >
            Use My Points ({{ user.points }} points)
        </Button>

        <Button
            v-if="form.plan_id"
            @click="promoteUsingFiat"
            :processing="form.processing"
            class="mt-4 w-full"
        >
            <Rocket class="h-4 w-4" /> Promote Now
        </Button>
    </div>
</template>

<style scoped>

</style>
