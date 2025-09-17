<script setup lang="ts">
import AppLayout from "@/layouts/app/AppLayout.vue";
import InputText from "@/components/InputText.vue";
import {Link, useForm} from "@inertiajs/vue3";
import {ref} from "vue";
import FeaturedFilePond from "@/components/FeaturedFilePond.vue";
import {Check, LoaderCircle, PlusIcon, User, UserCheck2, BadgeInfo} from "lucide-vue-next";
import {Button} from "@/components/ui/button";
import PageHeader from "@/components/PageHeader.vue";
import {InputSelectOption, PaginatedDataI, StudentI} from "@/types";
import SelectOption from "@/components/forms/SelectOption.vue";
import Datepicker from "@/components/forms/Datepicker.vue";
import ParentCreate from "@/pages/Parents/ParentCreate.vue";
import {toastError, toastSuccess} from "@/lib/helpers";

const props = defineProps<{
    student: StudentI;
    classes: InputSelectOption[];
    semesters: InputSelectOption[];
    regions: InputSelectOption[];
    religions: InputSelectOption[];
    gender: InputSelectOption[];
    parents: InputSelectOption[];
}>();

const form = useForm({
    image: null,
    first_name: props.student.first_name,
    middle_name: props.student.middle_name,
    last_name: props.student.last_name,
    email: props.student.email,
    phone: props.student.phone,
    class_id: props.student.class_id,
    dob: props.student.dob,
    gender: props.student.gender,
    region: props.student.region,
    city: props.student.city,

    parents: props.student.parents.map(p => p.id) || [],
    religion: props.student.religion,
    bio: props.student.bio || "",
    allergies: props.student.allergies || "",

});

const updateStudent = () => {
    form.transform((data) => ({
        ...data,
        _method: 'PUT'
    })).post(route("students.update", props.student.id), {
        forceFormData: true,
        onSuccess: (res) => {
            const message = res.props.message;
            if (res.props.success) toastSuccess(message);
            else toastError(message);
            form.reset();
        },
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout>
        <div class="bg-background border-b border-primary shadow-md">
            <div class="max-w-12xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-primary rounded-lg">
                            <UserCheck2 class="h-5 w-5 text-white"/>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-foreground">Add New Student</h1>
                            <p class="text-sm text-muted-foreground">Add existing or admit new student</p>
                        </div>
                    </div>
                    <Link
                        :href="route('students.index')"
                        class="px-4 py-2 bg-primary text-white rounded-lg shadow hover:bg-primary/70 transition"
                    >
                        Students
                    </Link>
                </div>
            </div>
        </div>

        <div class="max-w-full mx-auto px-1 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 lg:gap-8">
                <div class="order-1 lg:order-2 lg:col-span-1 space-y-6">
                    <div class="lg:sticky lg:top-8">
                        <FeaturedFilePond
                            :preview="student.image"
                            :form="form"
                            model-value="image"
                            title="Student Image"
                            v-model="form.image"
                            modelName="image"
                            :error="form.errors.image"
                        />
                    </div>
                </div>

                <!-- Main Form Section - Takes up 3/4 of the width -->
                <div class="order-2 lg:order-1 lg:col-span-3 space-y-6">
                    <form @submit.prevent="updateStudent" id="edit-student" class="p-2 sm:p-8">
                        <div class="mb-8">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="p-2 bg-primary rounded-lg gap-2">
                                    <User class="text-white"/>
                                </div>
                                <h3 class="text-lg font-semibold text-foreground">Personal Information</h3>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                                <InputText :form="form" model="first_name" label="First Name *" required/>
                                <InputText :form="form" model="middle_name" label="Middle Name"/>
                                <InputText :form="form" model="last_name" label="Last Name *" required/>
                                <InputText :form="form" model="email" label="Email Address *" type="email" required/>
                                <InputText :form="form" model="phone" label="Phone Number" type="tel"/>
                                <Datepicker :form="form" model="dob" label="Date of Birth" required/>
                                <SelectOption :form="form" :options="gender" model="gender" label="Gender"/>
                                <SelectOption :form="form" :options="religions" model="religion" label="Religion"/>
                                <SelectOption :form="form" :options="classes" model="class_id" label="Class" searchable/>
                            </div>
                        </div>

                        <div class="mb-8">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="p-2 bg-primary rounded-lg gap-2">
                                    <BadgeInfo class="text-white"/>
                                </div>
                                <h3 class="text-lg font-semibold text-foreground">Other Information</h3>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                                <SelectOption :form="form" :options="regions" model="region" label="Region"/>
                                <InputText :form="form" model="city" label="Hometown/City" required/>
                                <InputText :form="form" model="allergies" label="Alergies"/>
                                <div class="col-span-1">
                                    <SelectOption :form="form" :options="parents"  model="parents" label="Parents" searchable :multiple="true" required/>
                                    <div class="mt-2">
                                        <ParentCreate @created="$inertia.reload({ only: ['parents'] })">
                                            <button type="button" class="text-sm text-primary hover:underline">
                                                + Add Parent
                                            </button>
                                        </ParentCreate>
                                    </div>
                                </div>
                                <InputText :form="form" model="bio" textarea label="Additional Information"/>
                            </div>
                        </div>



                        <div class="bg-background rounded-xl shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center justify-between">
                                <p class="text-sm text-foreground">Review your information and submit when ready</p>
                                <Button :disabled="form.processing" type="submit" class="px-8 py-3 bg-gradient-to-r from-primary to-primary hover:from-primary
                            hover:to-orange-300 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl
                            transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <LoaderCircle v-if="form.processing" class="mr-2 h-5 w-5 animate-spin" />
                                    <Check v-else class="mr-2 h-5 w-5" />
                                    {{ form.processing ? 'Please wait' : 'Update Student' }}
                                </Button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped></style>
