<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, Head, useForm, usePage } from '@inertiajs/vue3';
import PageTitleBox from '@/Components/PageTitleBox.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import Dropdown from 'primevue/dropdown';


import { ref } from 'vue';
import Swal from 'sweetalert2'; 

const page = usePage();  
const ObjSubtitle = ref([
    { message: 'รายการข้อมูลสิทธิ์ผู้ใช้งาน' },
    { message: 'แก้ไขข้อมูลสิทธิ์ผู้ใช้งาน' },
]);

const props = defineProps({
    role: {
        type: Object
    },
    rolePermissions: {
        type: Array
    },
    permissionArr: {
        type: Array
    },
});

const permissionArr = props.permissionArr;
const rolePermissions = Object.values(JSON.parse(JSON.stringify(props.rolePermissions)));
const date = new Date();
const currentDate = date.toDateString();

const form = useForm({
    name: props.role.name,
    permission: ref(rolePermissions)
});

const submit = () => {
    form.put(route('roles.update', props.role.id), {
        onFinish: () => { 
            Swal.fire({
                title: "Successfully.",
                text: page.props.flash.success,
                icon: "success",
                showConfirmButton: false,
                timer: 1500
            });
        }
    });
};
</script>

<template>
    <Head title="Roles Create" />

    <AuthenticatedLayout>
        <PageTitleBox titlemain="แดชบอร์ด" :subtitles="ObjSubtitle" />
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-1">
                            <div class="col-md-6 responsive-mobile-title ">
                                <h4 class="header-title"> แก้ไขข้อมูลสิทธิ์ผู้ใช้งาน (Roles) </h4>
                                <p class="sub-header"> ข้อมูลประจำวันที่ : {{ currentDate }}</p>
                            </div>
                        </div>

                        <form @submit.prevent="submit" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <InputLabel for="name" value="ชื่อสิทธิ์การใช้งานระบบ : " required />
                                        <TextInput id="name" type="text" v-model="form.name" required />
                                        <InputError class="mt-2" :message="form.errors.name" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4" v-for="(permission, index) in permissionArr" :key="index">
                                            <div class="box-title-roles">{{ permission.name }}</div>
                                            <div class="box-content-roles">
                                                <div class="custom-control custom-checkbox" v-for="(permissionRoles, index) in permission.list" :key="index">
                                                    <input type="checkbox" class="custom-control-input"
                                                        v-model="form.permission" :id="permissionRoles.id" :value="permissionRoles.id">
                                                    <label class="custom-control-label" :for="permissionRoles.id">
                                                        <div>{{ permissionRoles.name }}</div>
                                                        <div class="text-danger">({{ permissionRoles.description }})</div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 text-right">
                                    <hr>
                                    <Link :href="route('roles.index')" class="btn btn-dark waves-effect waves-light"> <i
                                        class="fe-chevron-left"></i> ย้อนกลับ </Link>
                                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                        <i class="fe-save"></i> บันทึก
                                    </PrimaryButton>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
<style scoped>
.box-title-roles {
    text-transform: uppercase;
    background: #8c9396;
    color: #fff;
    padding: 0.5rem;
    border-top-right-radius: 5px;
    border-top-left-radius: 5px;
    font-weight: bold;
}

.box-content-roles{
    text-transform: uppercase;
    border: 1px solid #8c9396;
    padding: 0.5rem;
    border-bottom-right-radius: 5px;
    border-bottom-left-radius: 5px;
    font-weight: bold;
    max-height: 172px;
    overflow: auto;
 }
</style>