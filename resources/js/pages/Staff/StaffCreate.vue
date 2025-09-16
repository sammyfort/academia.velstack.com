<script setup lang="ts">
import AppLayout from "@/layouts/app/AppLayout.vue";
import InputText from "@/components/InputText.vue";
import {Link, useForm} from "@inertiajs/vue3";
import {ref} from "vue";
import FeaturedFilePond from "@/components/FeaturedFilePond.vue";
import {LoaderCircle, PlusIcon, User, UserCheck2} from "lucide-vue-next";
import {Button} from "@/components/ui/button";
import PageHeader from "@/components/PageHeader.vue";
import {InputSelectOption, PaginatedDataI, StudentI} from "@/types";
import SelectOption from "@/components/forms/SelectOption.vue";
import Datepicker from "@/components/forms/Datepicker.vue";
import ParentCreate from "@/pages/Parents/ParentCreate.vue";
import {toastError, toastSuccess} from "@/lib/helpers";

const props = defineProps<{
    classes: InputSelectOption[];
    semesters: InputSelectOption[];
    regions: InputSelectOption[];
    religions: InputSelectOption[];
    gender: InputSelectOption[];
    parents: InputSelectOption[];
}>();

const form = useForm({
    image: null,
    first_name: "",
    middle_name: "",
    last_name: "",
    email: "",
    phone: "",
    class_id: "",
    dob: "",
    gender: "",
    address: "",
    city: "",
    region: "",
    parents: [],
    religion: "",
    bio: "",
    allergies: "",

});

const createStudent = () => {
    form.post(route("students.store"), {
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
                    <!-- Left side: Icon + Text -->
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-primary rounded-lg">
                            <UserCheck2 class="h-5 w-5 text-white"/>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-foreground">Add New Staff</h1>
                            <p class="text-sm text-muted-foreground">Add existing or new staff</p>
                        </div>
                    </div>

                    <!-- Right side: Action button -->
                    <Link
                        :href="route('staff.index')"
                        class="px-4 py-2 bg-primary text-white rounded-lg shadow hover:bg-primary/90 transition"
                    >
                        Staff List
                    </Link>
                </div>
            </div>
        </div>

        <div class="min-h-screen bg-background py-8">
            <div class="max-w-7xl mx-auto px-6">
                <!-- Header Section -->

                <!-- Main Form Card -->
                <div class="bg-background rounded-2xl shadow-xl overflow-hidden">
                    <form @submit.prevent="createStudent" id="add-student" class="p-8">
                        <div class="mb-8">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="p-2 bg-primary rounded-lg gap-2">
                                    <User class="text-white"/>
                                </div>
                                <h3 class="text-lg font-semibold text-foreground">
                                    Personal Information
                                </h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <InputText
                                    :form="form"
                                    model="first_name"
                                    label="First Name *"
                                    required
                                />
                                <InputText :form="form" model="middle_name" label="Middle Name"/>
                                <InputText :form="form" model="last_name" label="Last Name *" required/>
                                <InputText
                                    :form="form"
                                    model="email"
                                    label="Email Address *"
                                    type="email"
                                    required
                                />
                                <InputText :form="form" model="phone" label="Phone Number" type="tel"/>
                                <Datepicker :form="form" model="dob" label="Date of Birth" required/>
                                <SelectOption
                                    :form="form"
                                    :options="gender"
                                    model="gender"
                                    label="Gender"
                                />

                                <SelectOption
                                    :form="form"
                                    :options="religions"
                                    model="religion"
                                    label="Religion"
                                />
                                <SelectOption
                                    :form="form"
                                    :options="classes"
                                    model="class_id"
                                    label="Class"
                                />
                            </div>
                        </div>

                        <div class="mb-8">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="p-2 bg-primary rounded-lg gap-2">
                                    <User class="text-white"/>
                                </div>
                                <h3 class="text-lg font-semibold text-foreground">Other Information</h3>
                            </div>

                            <div class="grid grid-cols-1 gap-6">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <SelectOption
                                        :form="form"
                                        :options="regions"
                                        model="region"
                                        label="Region"
                                    />
                                    <InputText :form="form" model="city" label="Hometown/City" required/>
                                    <InputText :form="form" model="allergies" label="Alergies"/>
                                    <InputText :form="form" model="bio" label="Additional Information"/>
                                </div>
                            </div>
                        </div>

                        <!-- Guardian Information Section -->
                        <div class="mb-8">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="p-2 bg-primary rounded-lg gap-2">
                                    <User class="text-white"/>
                                </div>
                                <h3 class="text-lg font-semibold text-foreground">
                                    Guardian Information
                                </h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <SelectOption
                                    :form="form"
                                    :options="parents"
                                    model="parents"
                                    label="Parents"
                                    :multiple="true"
                                />
                            </div>

                            <!-- Add new parent link -->
                            <div class="mt-3">
                                <ParentCreate @created="$inertia.reload({ only: ['parents'] })">
                                    <button type="button" class="text-sm text-primary hover:underline">
                                        + Add Parent
                                    </button>
                                </ParentCreate>
                            </div>
                        </div>

                        <div class="mb-8">
                            <div class="flex flex-col sm:flex-row items-start gap-6">
                                <div class="relative">
                                    <FeaturedFilePond
                                        :form="form"
                                        model-value="image"
                                        title="Student Image"
                                        v-model="form.image"
                                        modelName="image"
                                        :error="form.errors.image"
                                    />
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-medium text-foreground mb-2">Photo Requirements:</h3>
                                    <ul class="text-sm text-muted-foreground space-y-1">
                                        <li>• Recent passport-style photograph</li>
                                        <li>• Clear, well-lit image</li>
                                        <li>• File formats: JPG, PNG (Max 5MB)</li>
                                        <li>• Recommended size: 300x300 pixels</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- Submit Button -->
                        <div class="pt-6 border-t border-gray-200">
                            <div class="flex flex-col sm:flex-row gap-4 justify-end">
                                <Button form="add-parent" variant="outline"> Cancel</Button>
                                <Button :disabled="form.processing" type="submit" form="add-student">
                                    <LoaderCircle
                                        v-if="form.processing"
                                        class="mr-2 h-4 w-4 animate-spin"
                                    />
                                    {{ form.processing ? "Please wait..." : "Add Student" }}
                                </Button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="text-center mt-8 text-muted-foreground">
                    <p>
                        Need help? Contact our admissions office at
                        <span class="text-primary font-medium">admissions@school.edu</span> or
                        <span class="text-primary font-medium">(555) 123-4567</span>
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped></style>
