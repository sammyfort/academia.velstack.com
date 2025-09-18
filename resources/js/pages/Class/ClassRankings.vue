<script setup lang="ts">

import {
    CheckCircle,
    ChevronDown,
    ChevronUp,
    Clock,
    Download,
    Edit3,
    Eye, Medal,
    Minus,
    Plus,
    Trophy,
    XCircle
} from "lucide-vue-next";
import {ClassroomI} from "@/types";
import {computed, ref} from "vue";

const props = defineProps<{
    classroom: ClassroomI
}>()
const searchQuery = ref('');
const selectedFilter = ref('all');
const students = ref([
    { id: 1, name: 'Alice Johnson', avatar: 'AJ', status: 'present', grade: 'A', attendance: 95 },
    { id: 2, name: 'Bob Smith', avatar: 'BS', status: 'present', grade: 'B+', attendance: 88 },
    { id: 3, name: 'Carol Davis', avatar: 'CD', status: 'absent', grade: 'A-', attendance: 92 },
    { id: 4, name: 'David Wilson', avatar: 'DW', status: 'late', grade: 'B', attendance: 85 },
    { id: 5, name: 'Eva Brown', avatar: 'EB', status: 'present', grade: 'A+', attendance: 98 },
    { id: 6, name: 'Frank Miller', avatar: 'FM', status: 'present', grade: 'B-', attendance: 82 }
]);

const subjects = ref([
    { id: 1, name: 'Mathematics', teacher: 'Dr. Smith', schedule: 'Mon, Wed, Fri - 9:00 AM', progress: 75 },
    { id: 2, name: 'Science', teacher: 'Prof. Johnson', schedule: 'Tue, Thu - 10:30 AM', progress: 82 },
    { id: 3, name: 'English', teacher: 'Ms. Davis', schedule: 'Mon, Wed, Fri - 2:00 PM', progress: 68 }
]);

const recentExams = ref([
    { id: 1, subject: 'Mathematics', date: '2024-03-15', type: 'Midterm', avgScore: 85, status: 'completed' },
    { id: 2, subject: 'Science', date: '2024-03-20', type: 'Quiz', avgScore: 78, status: 'graded' },
    { id: 3, subject: 'English', date: '2024-03-25', type: 'Essay', avgScore: null, status: 'pending' }
]);

// Exam results data for ranking table
const examResults = ref([
    {
        studentId: 1,
        name: 'Alice Johnson',
        avatar: 'AJ',
        mathematics: 92,
        science: 88,
        english: 85,
        average: 88.3,
        rank: 2,
        trend: 'up'
    },
    {
        studentId: 2,
        name: 'Bob Smith',
        avatar: 'BS',
        mathematics: 78,
        science: 82,
        english: 80,
        average: 80.0,
        rank: 4,
        trend: 'stable'
    },
    {
        studentId: 3,
        name: 'Carol Davis',
        avatar: 'CD',
        mathematics: 88,
        science: 90,
        english: 87,
        average: 88.3,
        rank: 3,
        trend: 'up'
    },
    {
        studentId: 4,
        name: 'David Wilson',
        avatar: 'DW',
        mathematics: 75,
        science: 79,
        english: 83,
        average: 79.0,
        rank: 5,
        trend: 'down'
    },
    {
        studentId: 5,
        name: 'Eva Brown',
        avatar: 'EB',
        mathematics: 95,
        science: 92,
        english: 89,
        average: 92.0,
        rank: 1,
        trend: 'up'
    },
    {
        studentId: 6,
        name: 'Frank Miller',
        avatar: 'FM',
        mathematics: 82,
        science: 85,
        english: 78,
        average: 81.7,
        rank: 6,
        trend: 'stable'
    }
]);

const sortedExamResults = computed(() => {
    return [...examResults.value].sort((a, b) => b.average - a.average);
});

const attendanceStats = computed(() => {
    const total = students.value.length;
    const present = students.value.filter(s => s.status === 'present').length;
    const absent = students.value.filter(s => s.status === 'absent').length;
    const late = students.value.filter(s => s.status === 'late').length;

    return { total, present, absent, late };
});

const filteredStudents = computed(() => {
    let filtered = students.value;

    if (searchQuery.value) {
        filtered = filtered.filter(student =>
            student.name.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
    }

    if (selectedFilter.value !== 'all') {
        filtered = filtered.filter(student => student.status === selectedFilter.value);
    }

    return filtered;
});

const updateAttendance = (studentId: number, status: string) => {
    const student = students.value.find(s => s.id === studentId);
    if (student) {
        student.status = status;
    }
};

const getStatusColor = (status: string) => {
    switch (status) {
        case 'present': return 'text-green-600 bg-green-50';
        case 'absent': return 'text-red-600 bg-red-50';
        case 'late': return 'text-orange-600 bg-orange-50';
        default: return 'text-gray-600 bg-gray-50';
    }
};

const getStatusIcon = (status: string) => {
    switch (status) {
        case 'present': return CheckCircle;
        case 'absent': return XCircle;
        case 'late': return Clock;
        default: return Clock;
    }
};

const getRankIcon = (index: number) => {
    if (index === 0) return Trophy;
    if (index === 1 || index === 2) return Medal;
    return null;
};

const getRankColor = (index: number) => {
    if (index === 0) return 'text-yellow-600 bg-yellow-100';
    if (index === 1) return 'text-gray-600 bg-gray-100';
    if (index === 2) return 'text-orange-600 bg-orange-100';
    return 'text-blue-600 bg-blue-100';
};

const getScoreColor = (score: number) => {
    if (score >= 90) return 'text-green-600 bg-green-50';
    if (score >= 80) return 'text-blue-600 bg-blue-50';
    if (score >= 70) return 'text-orange-600 bg-orange-50';
    return 'text-red-600 bg-red-50';
};






</script>


<template>
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0 mb-4">
            <div>
                <h3 class="text-lg font-semibold text-foreground">Student Rankings</h3>
                <p class="text-sm text-muted-foreground">Based on overall academic performance</p>
            </div>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                <button class="bg-primary text-primary-foreground px-4 py-2 rounded-lg hover:bg-primary/90 transition-colors flex items-center justify-center space-x-2 text-sm">
                    <Plus class="w-4 h-4" />
                    <span>Add Results</span>
                </button>
                <button class="border border-muted text-foreground px-4 py-2 rounded-lg hover:bg-muted transition-colors text-sm flex items-center justify-center space-x-2">
                    <Download class="w-4 h-4" />
                    <span>Export</span>
                </button>
            </div>
        </div>

        <!-- Mobile Rankings Cards -->
        <div class="block lg:hidden space-y-4">
            <div
                v-for="(result, index) in sortedExamResults"
                :key="result.studentId"
                class="bg-background rounded-lg p-4 border border-muted shadow-sm"
                :class="index < 3 ? 'border-yellow-200 dark:border-yellow-500/50 bg-gradient-to-r from-yellow-100/50 dark:from-yellow-500/10 to-transparent' : ''"
            >
                <!-- Mobile Header -->
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <div :class="getRankColor(index)" class="w-8 h-8 rounded-full flex items-center justify-center">
                            <component v-if="getRankIcon(index)" :is="getRankIcon(index)" class="w-4 h-4" />
                            <span v-else class="text-sm font-semibold text-foreground">#{{ index + 1 }}</span>
                        </div>
                        <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center">
                            <span class="text-primary font-semibold text-sm">{{ result.avatar }}</span>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-foreground">{{ result.name }}</div>
                            <div class="text-xs text-muted-foreground">ID: {{ result.studentId.toString().padStart(4, '0') }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div
                            :class="result.average >= 90 ? 'text-green-500 dark:text-green-400' :
                                result.average >= 80 ? 'text-primary' :
                                result.average >= 70 ? 'text-orange-500 dark:text-orange-400' :
                                'text-red-500 dark:text-red-400'"
                            class="text-lg font-bold"
                        >
                            {{ result.average.toFixed(1) }}%
                        </div>
                        <div class="text-xs text-muted-foreground">Average</div>
                    </div>
                </div>

                <!-- Mobile Scores -->
                <div class="grid grid-cols-3 gap-3 mb-3">
                    <div class="text-center">
                        <div class="text-xs text-muted-foreground mb-1">Math</div>
                        <span :class="getScoreColor(result.mathematics)" class="px-2 py-1 rounded-full text-xs font-medium">
                        {{ result.mathematics }}%
                      </span>
                    </div>
                    <div class="text-center">
                        <div class="text-xs text-muted-foreground mb-1">Science</div>
                        <span :class="getScoreColor(result.science)" class="px-2 py-1 rounded-full text-xs font-medium">
                        {{ result.science }}%
                      </span>
                    </div>
                    <div class="text-center">
                        <div class="text-xs text-muted-foreground mb-1">English</div>
                        <span :class="getScoreColor(result.english)" class="px-2 py-1 rounded-full text-xs font-medium">
                        {{ result.english }}%
                      </span>
                    </div>
                </div>

                <!-- Mobile Trend and Actions -->
                <div class="flex items-center justify-between pt-2 border-t border-muted">
                    <div class="flex items-center">
                        <div
                            v-if="result.trend === 'up'"
                            class="flex items-center space-x-1 text-green-500 dark:text-green-400 bg-green-500/10 dark:bg-green-400/10 px-2 py-1 rounded-full"
                        >
                            <ChevronUp class="w-3 h-3" />
                            <span class="text-xs font-medium">Up</span>
                        </div>
                        <div
                            v-else-if="result.trend === 'down'"
                            class="flex items-center space-x-1 text-red-500 dark:text-red-400 bg-red-500/10 dark:bg-red-400/10 px-2 py-1 rounded-full"
                        >
                            <ChevronDown class="w-3 h-3" />
                            <span class="text-xs font-medium">Down</span>
                        </div>
                        <div
                            v-else
                            class="flex items-center space-x-1 text-muted-foreground bg-muted px-2 py-1 rounded-full"
                        >
                            <Minus class="w-3 h-3" />
                            <span class="text-xs font-medium">Stable</span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button class="text-primary hover:text-primary/80 text-xs font-medium flex items-center space-x-1">
                            <Edit3 class="w-3 h-3" />
                            <span>Edit</span>
                        </button>
                        <button class="text-muted-foreground hover:text-foreground text-xs font-medium flex items-center space-x-1">
                            <Eye class="w-3 h-3" />
                            <span>View</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop Rankings Table -->
        <div class="hidden lg:block bg-background rounded-xl border border-muted overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-muted/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Rank</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Student</th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-muted-foreground uppercase tracking-wider">Mathematics</th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-muted-foreground uppercase tracking-wider">Science</th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-muted-foreground uppercase tracking-wider">English</th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-muted-foreground uppercase tracking-wider">Average</th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-muted-foreground uppercase tracking-wider">Trend</th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-muted-foreground uppercase tracking-wider">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-background divide-y divide-muted">
                    <tr
                        v-for="(result, index) in sortedExamResults"
                        :key="result.studentId"
                        class="hover:bg-muted/50 transition-colors"
                        :class="index < 3 ? 'bg-gradient-to-r from-yellow-100/50 dark:from-yellow-500/10 to-transparent' : ''"
                    >
                        <!-- Rank Column -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <div :class="getRankColor(index)" class="w-8 h-8 rounded-full flex items-center justify-center">
                                    <component v-if="getRankIcon(index)" :is="getRankIcon(index)" class="w-4 h-4" />
                                    <span v-else class="text-sm font-semibold text-foreground">#{{ index + 1 }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Student Column -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center">
                                    <span class="text-primary font-semibold text-sm">{{ result.avatar }}</span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-foreground">{{ result.name }}</div>
                                    <div class="text-sm text-muted-foreground">ID: {{ result.studentId.toString().padStart(4, '0') }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Subject Scores -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                          <span :class="getScoreColor(result.mathematics)" class="px-2 py-1 rounded-full text-sm font-medium">
                            {{ result.mathematics }}%
                          </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-center">
                          <span :class="getScoreColor(result.science)" class="px-2 py-1 rounded-full text-sm font-medium">
                            {{ result.science }}%
                          </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-center">
                          <span :class="getScoreColor(result.english)" class="px-2 py-1 rounded-full text-sm font-medium">
                            {{ result.english }}%
                          </span>
                        </td>

                        <!-- Average Column -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex flex-col items-center">
                            <span
                                :class="result.average >= 90 ? 'text-green-500 dark:text-green-400' :
                                      result.average >= 80 ? 'text-primary' :
                                      result.average >= 70 ? 'text-orange-500 dark:text-orange-400' :
                                      'text-red-500 dark:text-red-400'"
                                class="text-lg font-bold"
                            >
                              {{ result.average.toFixed(1) }}%
                            </span>
                                <div class="text-xs text-muted-foreground">Overall</div>
                            </div>
                        </td>

                        <!-- Trend Column -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center">
                                <div
                                    v-if="result.trend === 'up'"
                                    class="flex items-center space-x-1 text-green-500 dark:text-green-400 bg-green-500/10 dark:bg-green-400/10 px-2 py-1 rounded-full"
                                >
                                    <ChevronUp class="w-3 h-3" />
                                    <span class="text-xs font-medium">Up</span>
                                </div>
                                <div
                                    v-else-if="result.trend === 'down'"
                                    class="flex items-center space-x-1 text-red-500 dark:text-red-400 bg-red-500/10 dark:bg-red-400/10 px-2 py-1 rounded-full"
                                >
                                    <ChevronDown class="w-3 h-3" />
                                    <span class="text-xs font-medium">Down</span>
                                </div>
                                <div
                                    v-else
                                    class="flex items-center space-x-1 text-muted-foreground bg-muted px-2 py-1 rounded-full"
                                >
                                    <Minus class="w-3 h-3" />
                                    <span class="text-xs font-medium">Stable</span>
                                </div>
                            </div>
                        </td>

                        <!-- Actions Column -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <button class="text-primary hover:text-primary/80 text-sm font-medium">
                                    Edit
                                </button>
                                <span class="text-muted">|</span>
                                <button class="text-muted-foreground hover:text-foreground text-sm font-medium">
                                    View
                                </button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!-- Table Summary -->
            <div class="bg-muted/50 px-6 py-4 border-t border-muted">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-2 sm:space-y-0">
                    <div class="flex flex-wrap items-center gap-4 text-sm text-muted-foreground">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-green-500/20 dark:bg-green-400/20 rounded-full"></div>
                            <span>90%+ ({{ sortedExamResults.filter(r => r.average >= 90).length }} students)</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-primary/20 rounded-full"></div>
                            <span>80-89% ({{ sortedExamResults.filter(r => r.average >= 80 && r.average < 90).length }} students)</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-orange-500/20 dark:bg-orange-400/20 rounded-full"></div>
                            <span>70-79% ({{ sortedExamResults.filter(r => r.average >= 70 && r.average < 80).length }} students)</span>
                        </div>
                    </div>
                    <div class="text-sm text-muted-foreground">
                        Class Average: <span class="font-semibold text-foreground">{{ (sortedExamResults.reduce((sum, r) => sum + r.average, 0) / sortedExamResults.length).toFixed(1) }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
