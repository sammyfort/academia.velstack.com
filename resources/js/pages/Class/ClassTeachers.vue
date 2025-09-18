<script setup lang="ts">

import {Calendar, FileText} from "lucide-vue-next";
import {ClassroomI} from "@/types";

const props = defineProps<{
    classroom: ClassroomI
}>()
</script>

<template>
    <div class="space-y-4">
        <div v-for="exam in [1,2,3,4]"  class="bg-muted/50 rounded-lg p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                <div class="flex items-center space-x-4">
                    <div class="bg-purple-500/10 dark:bg-purple-400/10 p-3 rounded-full">
                        <FileText class="w-5 h-5 sm:w-6 sm:h-6 text-purple-500 dark:text-purple-400" />
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-foreground">Sam - Class Teacher</h3>
                        <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 mt-1 space-y-1 sm:space-y-0">
                        <span class="text-sm text-muted-foreground flex items-center space-x-1">
                          <Calendar class="w-4 h-4" />
                          <span>12/23/23</span>
                        </span>
                            <span
                                :class=" 'text-green-500 dark:text-green-400 bg-green-500/10 dark:bg-green-400/10'
                               "
                                class="px-2 py-1 rounded-full text-xs font-medium capitalize inline-block"
                            >
                         Done
                        </span>
                        </div>
                    </div>
                </div>

                <div class="text-center sm:text-right">
                    <div  class="text-xl sm:text-2xl font-bold text-foreground">80%</div>
                    <div class="text-sm text-muted-foreground">Not graded</div>
                    <div class="text-xs sm:text-sm text-muted-foreground">Class Average</div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
