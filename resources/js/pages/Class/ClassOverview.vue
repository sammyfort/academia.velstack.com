<script setup lang="ts">

import {Award, FileText} from "lucide-vue-next";
</script>

<template>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Quick Stats -->
        <div class="bg-muted/50 rounded-lg p-4 sm:p-6">
            <h3 class="text-lg font-semibold text-foreground mb-4">Today's Attendance</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-muted-foreground">Present</span>
                    <span class="font-semibold text-green-500 dark:text-green-400">5/2</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-muted-foreground">Absent</span>
                    <span class="font-semibold text-red-500 dark:text-red-400">45</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-muted-foreground">Late</span>
                    <span class="font-semibold text-orange-500 dark:text-orange-400">5</span>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-muted/50 rounded-lg p-4 sm:p-6">
            <h3 class="text-lg font-semibold text-foreground mb-4">Recent Activity</h3>
            <div class="space-y-3">
                <div class="flex items-center space-x-3">
                    <div class="bg-primary/10 p-2 rounded-full">
                        <FileText class="w-4 h-4 text-primary" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-foreground">Math exam scheduled</p>
                        <p class="text-xs text-muted-foreground">Tomorrow at 9:00 AM</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="bg-green-500/10 dark:bg-green-400/10 p-2 rounded-full">
                        <Award class="w-4 h-4 text-green-500 dark:text-green-400" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-foreground">Science quiz graded</p>
                        <p class="text-xs text-muted-foreground">Average score: 78%</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
