<script setup lang="ts">
import AppLayout from "@/layouts/app/AppLayout.vue";
import InputText from "@/components/InputText.vue";
import {Link, useForm} from "@inertiajs/vue3";
import {ref} from "vue";
import FeaturedFilePond from "@/components/FeaturedFilePond.vue";
import {Check, LoaderCircle, Users, User, UserCheck2, MapPin, Briefcase} from "lucide-vue-next";
import {Button} from "@/components/ui/button";
import PageHeader from "@/components/PageHeader.vue";
import {InputSelectOption, PaginatedDataI, StudentI} from "@/types";
import SelectOption from "@/components/forms/SelectOption.vue";
import Datepicker from "@/components/forms/Datepicker.vue";
import ParentCreate from "@/pages/Parents/ParentCreate.vue";
import {toastError, toastSuccess} from "@/lib/helpers";

const props = defineProps<{

    titles: InputSelectOption[]
    semesters: InputSelectOption[];
    regions: InputSelectOption[];
    religions: InputSelectOption[];
    gender: InputSelectOption[];
    staffStatus: InputSelectOption[]
    maritalStatus: InputSelectOption[]
    staffQualifications: InputSelectOption[]
    staffExperiences: InputSelectOption[]
    roles: InputSelectOption[]
}>();

const form = useForm({
    image: null,

    roles: [],

    title: "",
    first_name: "",
    middle_name: "",
    last_name: "",

    email: "",
    phone: "",
    staff_id: "",
    password: "",

    basic_salary: "",
    designation: "",
    national_id: "",

    gender: "",
    bio: "",
    dob: "",
    religion: "",
    region: "",
    city: "",

    marital_status: "",
    licence_no: "",
    qualification: "",
    experience: "",

    status: "",
});


const createStaff = () => {
    form.post(route("staff.store"), {
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
        <div class="bg-background border-b border-border shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <!-- Left: Icon + Title -->
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-primary rounded-lg shadow-sm">
                            <UserCheck2 class="h-5 w-5 text-white" />
                        </div>
                        <div>
                            <h1 class="text-lg sm:text-xl font-semibold text-foreground">
                                Add New Staff
                            </h1>
                            <p class="text-xs sm:text-sm text-muted-foreground">
                                Add new Staff
                            </p>
                        </div>
                    </div>

                    <!-- Right: Action Button -->
                    <Link
                        :href="route('staff.index')"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-primary text-white rounded-lg shadow hover:bg-primary/80 transition"
                    >
                        <Users class="h-4 w-4" />
                        <span>Staff</span>
                    </Link>
                </div>
            </div>
        </div>


        <div class="max-w-full mx-auto px-1 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 lg:gap-8">
                <!-- File Upload Section - Shows first on mobile, far right on desktop -->
                <div class="order-1 lg:order-2 lg:col-span-1 space-y-6">
                    <div class="lg:sticky lg:top-8">
                        <FeaturedFilePond
                            :form="form"

                            model-value="image"
                            title="Staff Image"
                            v-model="form.image"
                            modelName="image"
                            :error="form.errors.image"
                        />
                    </div>
                </div>

                <!-- Main Form Section - Takes up 3/4 of the width -->
                <div class="order-2 lg:order-1 lg:col-span-3 space-y-6">
                    <form @submit.prevent="createStaff" id="add-staff" class="p-2 sm:p-8">
                        <div class="mb-8">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="p-2 bg-primary rounded-lg gap-2">
                                    <User class="text-white"/>
                                </div>
                                <h3 class="text-lg font-semibold text-foreground">Personal Information</h3>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                                <SelectOption :options="titles" :form="form" model="title" label="Title" required/>
                                <InputText :form="form" model="first_name" label="First Name" required/>
                                <InputText :form="form" model="middle_name" label="Middle Name"/>
                                <InputText :form="form" model="last_name" label="Last Name" required/>
                                <InputText :form="form" model="email" label="Email Address" type="email" required/>
                                <InputText :form="form" model="phone" label="Phone Number" type="tel"/>
                                <Datepicker :form="form" model="dob" label="Date of Birth " />
                                <SelectOption :form="form" :options="gender" model="gender" label="Gender "/>
                                <SelectOption :form="form" :options="religions" model="religion" label="Religion "/>
                                <SelectOption :form="form" :options="maritalStatus" model="marital_status" label="Marital Status "/>


                            </div>
                        </div>


                        <div class="mb-8">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="p-2 bg-primary rounded-lg gap-2">
                                    <Briefcase class="text-white"/>
                                </div>
                                <h3 class="text-lg font-semibold text-foreground">Employment Information</h3>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                                <SelectOption :form="form" :options="roles" model="roles" label="Roles" searchable multiple/>
                                <InputText :form="form" model="staff_id" label="Staff ID" />
                                <InputText :form="form" model="basic_salary" label="Basic Salary" type="number"/>
                                <SelectOption :options="staffQualifications" :form="form" model="qualification" label="Qualification" required/>
                                <SelectOption :options="staffExperiences" :form="form" model="experience" label="Years of Experience" required/>
                                <InputText :form="form" model="national_id" label="National ID" required/>
                                <InputText :form="form" model="licence_no" label="Licence Number"/>
                                <SelectOption :form="form" :options="staffStatus" model="status" label="Employment Status" required/>
                            </div>
                        </div>

                        <div class="mb-8">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="p-2 bg-primary rounded-lg gap-2">
                                    <MapPin class="text-white"/>
                                </div>
                                <h3 class="text-lg font-semibold text-foreground">Address & Other Information</h3>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                                <SelectOption :form="form" :options="regions" model="region" label="Region" required/>
                                <InputText :form="form" model="city" label="City/Hometown" required/>
                                < <InputText :form="form" model="bio"   label="Additional Information"/>
                            </div>

                        </div>



                        <div class="bg-background rounded-xl shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center justify-between">
                                <p class="text-sm text-foreground">Review your information and submit when ready</p>
                                <Button
                                    :disabled="form.processing"
                                    type="submit"
                                    class="px-8 py-3 bg-gradient-to-r from-primary to-primary hover:from-primary
                            hover:to-orange-300 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl
                            transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <LoaderCircle v-if="form.processing" class="mr-2 h-5 w-5 animate-spin" />
                                    <Check v-else class="mr-2 h-5 w-5" />
                                    {{ form.processing ? 'Please wait' : 'Add Staff' }}
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
