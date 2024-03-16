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
    { message: 'รายการข้อมูลผู้ใช้งาน' },
    { message: 'แก้ไขข้อมูลผู้ใช้งาน' },
]);

const props = defineProps({
    plants: {
        type: Array
    },
    roles: {
        type: Array
    },
    user: {
        type: Object,
        default: () => ({}),
    },
    roleID: {
        type: Number, 
    }
});

const plants = props.plants;
const roles = props.roles;
const user = props.user
const date = new Date();
const currentDate = date.toDateString();
 
const form = useForm({
    code: user.empolyee_code,
    password: '',
    plant_id: plants.find(item => item.id === user.plant_id),
    email: user.email,
    phone: user.phone,
    username: user.username,
    password: '',
    confirmPassword: '',
    status: user.status,
    roles: roles.find(item => item.id === props.roleID),
    filename: (user.images_name!=null)? '/storage/images/users/'+user.images_name : null
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
    form.put(route('users.update', user.id), {
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
    <Head title="Users Edit" />

    <AuthenticatedLayout>
        <PageTitleBox titlemain="แดชบอร์ด" :subtitles="ObjSubtitle" />
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-1">
                            <div class="col-md-6 responsive-mobile-title ">
                                <h4 class="header-title"> แก้ไขข้อมูลผู้ใช้งาน (Users) </h4>
                                <p class="sub-header"> ข้อมูลประจำวันที่ : {{ currentDate }}</p>
                            </div>
                        </div>

                        <form @submit.prevent="submit" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <InputLabel for="plant_id" value="Plants" />
                                        <Dropdown v-model="form.plant_id" :options="plants" filter optionValue="plant_id"
                                            placeholder="Select a Plants" class="w-100">
                                            <template #value="slotProps">
                                                <div v-if="slotProps.value" class="flex align-items-center">
                                                    <div>{{ slotProps.value.plant }} </div>
                                                </div>
                                                <span v-else>
                                                    {{ slotProps.placeholder }}
                                                </span>
                                            </template>
                                        </Dropdown>
                                        <InputError class="mt-2" :message="form.errors.plant_id" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <InputLabel for="code" value="รหัสพนักงาน : " required />
                                        <TextInput id="code" type="text" v-model="form.code" required />
                                        <InputError class="mt-2" :message="form.errors.code" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <InputLabel for="email" value="อีเมล (Email) : " required />
                                        <TextInput id="email" type="email" v-model="form.email" required />
                                        <InputError class="mt-2" :message="form.errors.email" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <InputLabel for="phone" value="เบอร์โทร (Phone) : " />
                                        <TextInput id="phone" type="text" v-model="form.phone" />
                                        <InputError class="mt-2" :message="form.errors.phone" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <InputLabel for="username" value="Username : " required />
                                        <TextInput id="username" type="text" v-model="form.username" required />
                                        <InputError class="mt-2" :message="form.errors.username" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <InputLabel for="password" value="รหัสผ่าน (Password) : "  />
                                        <TextInput id="password" type="password" v-model="form.password"  />
                                        <InputError class="mt-2" :message="form.errors.password" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <InputLabel for="confirm-password"
                                            value="ยืนยันรหัสผ่าน (Confirm confirm-password) : "  />
                                        <TextInput id="confirm-password" type="password" v-model="form.confirmPassword"
                                             />
                                        <InputError class="mt-2" :message="form.errors.confirmPassword" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <InputLabel for="images_name" value="รูปพนักงานขาย : " />
                                        <div class="mt-2 img-file-upload-1">
                                            <div class="box-image-no">
                                                <img v-if="form.filename" :src="form.filename" alt="Selected Image">
                                            </div>
                                        </div>
                                        <div class="mt-1 mb-1"> Size image 690 × 690 px 2MB. </div>
                                        <input type="file" id="images_name" @change="handleFileInputChange" />
                                    </div>
                                    <InputError class="mt-2" :message="form.errors.filename" />
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status-1"> สถานะการแสดงผล <span class="text-danger">*</span></label>
                                        <div class="mt-2">
                                            <div class="radio radio-success form-check-inline">
                                                <input type="radio" id="status-1" value="1" v-model="form.status">
                                                <label for="status-1"> <span class="badge badge-success p-1"> Enable
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="radio radio-secondary form-check-inline">
                                                <input type="radio" id="status-0" value="0" v-model="form.status">
                                                <label for="status-0"> <span class="badge badge-secondary p-1"> Disable
                                                    </span>
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <InputLabel for="roles" value="สิทธิ์ผู้ใช้งาน : " required/>
                                        <Dropdown v-model="form.roles" :options="roles" filter optionLabel="name"
                                            placeholder="Select a Roles" class="w-100">
                                            <template #value="slotProps">
                                                <div v-if="slotProps.value" class="flex align-items-center">
                                                    <div>{{ slotProps.value.name }} </div>
                                                </div>
                                                <span v-else>
                                                    {{ slotProps.placeholder }}
                                                </span>
                                            </template>
                                        </Dropdown>
                                        <InputError class="mt-2" :message="form.errors.roles" />
                                    </div>
                                </div>

                                <div class="col-md-12 text-right">
                                    <hr>
                                    <Link :href="route('users.index')" class="btn btn-dark waves-effect waves-light"> <i
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
.box-image-no {
    background: #ddd;
    border-radius: 50%;
    width: 100px;
    height: 100px;
}

img {
    border-radius: 50%;
    width: 100px;
    height: 100px;
}
</style>