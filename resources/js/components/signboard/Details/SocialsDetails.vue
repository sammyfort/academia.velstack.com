<script setup lang="ts">

import { Facebook, Instagram, Linkedin } from 'lucide-vue-next';
import { SignboardI } from '@/types';


type Props = {
    signboard: SignboardI
}
const props = defineProps<Props>()
const service = props.signboard.service

</script>

<template>
    <div class="flex flex-col gap-3">
        <div class="text-fade font-semibold">
            Socials
        </div>
        <div class="flex items-center gap-3 text-secondary">
            <a v-if="service?.user.facebook" target="_blank" :href="service.user.facebook">
                <Facebook />
            </a>
            <a v-if="service?.user.linkedin" target="_blank" :href="service.user.linkedin">
                <Linkedin />
            </a>
            <a v-if="service?.user.instagram" target="_blank" :href="service.user?.instagram">
                <Instagram />
            </a>
            <a v-if="service?.user.x" target="_blank" :href="service.user.x">
                <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M15.1708 1.875H17.9274L11.9049 8.75833L18.9899 18.125H13.4424L9.09742 12.4442L4.12578 18.125H1.36745L7.80912 10.7625L1.01245 1.875H6.70078L10.6283 7.0675L15.1708 1.875ZM14.2033 16.475H15.7308L5.87078 3.43833H4.23162L14.2033 16.475Z"
                        fill=""
                    ></path>
                </svg>
            </a>

            <div
                v-if="!service.user.facebook && !service.user.linkedin && !service.user.instagram && !service.user.x"
                class="italic text-sm"
            >
                User has no socials
            </div>

        </div>
    </div>
</template>

<style scoped>

</style>
