<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import PageTitleBox from '@/Components/PageTitleBox.vue';
import FormLeft from './Partials/FormLeft.vue';
import FormRight from './Partials/FormRight.vue';

import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import Dropdown from 'primevue/dropdown';


import { ref } from 'vue';

const ObjSubtitle = ref([
    { message: 'รายการข้อมูลการจ่ายยา' },
    { message: 'เพิ่มข้อมูลการจ่ายยา' },
]);

const props = defineProps({

});

const date = new Date();
const currentDate = date.toDateString();

const form = useForm({

});

const handleFileInputChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            form.filename = e.target.result;
        };
        reader.readAsDataURL(file);
    } else {
        form.filename = null;
    }
};

const submit = () => {
    form.post(route('users.store'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>

    <Head title="Users Create" />

    <AuthenticatedLayout>
        <PageTitleBox titlemain="แดชบอร์ด" :subtitles="ObjSubtitle" />
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-1">
                            <div class="col-md-6 responsive-mobile-title ">
                                <h4 class="header-title"> เพิ่มข้อมูลการจ่ายยา (Dispensing Medicine) </h4>
                                <p class="sub-header"> ข้อมูลประจำวันที่ : {{ currentDate }}</p>
                            </div>
                            <div class="col-md-6 text-right responsive-mobile-btn">
                                <Link :href="route('dashboard')"
                                    class="btn btn-primary waves-effect waves-light float-right mb-2">
                                <i class="fe-file-text"></i> ดูรายการประวัติการจ่ายยา
                                </Link>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <FormLeft />
                            </div>
                            <div class="col-md-8">
                                <FormRight />
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<style scoped></style>