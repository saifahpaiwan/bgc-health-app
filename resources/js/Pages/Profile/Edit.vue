<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-body">
                        <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" class="" />
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <UpdatePasswordForm class="" />
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <DeleteUserForm class="" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
