<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PageTitleBox from '@/Components/PageTitleBox.vue';
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column'; 

import Swal from 'sweetalert2'; 
const ObjSubtitle = ref([{ message: 'รายการข้อมูลสิทธิ์ผู้ใช้งาน' }]);
const date = new Date();
const currentDate = date.toDateString();

const props = defineProps({
    roles: {
        type: Array
    }
});

function destroy(id) { 
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route("roles.destroy", id), {
                onFinish: () => {
                    Swal.fire({
                        title: "Deleted!",
                        text: "Your file has been deleted.",
                        icon: "success",
                        showConfirmButton: false,
                         timer: 1500
                    }).then((result) => {
                        location.reload();
                    }); 
                }
            }); 
        }
    }); 
}

const form = useForm({
    keyword: ref()
});

const roles = Object.values(JSON.parse(JSON.stringify(props.roles)));

const submit = () => {
    form.get(route('roles.index'));
}; 
</script> 

<template>
    <Head title="Roles List" />

    <AuthenticatedLayout>
        <PageTitleBox titlemain="แดชบอร์ด" :subtitles="ObjSubtitle" />
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-1">
                            <div class="col-md-6 responsive-mobile-title ">
                                <h4 class="header-title"> จัดการสิทธิ์ผู้ใช้งานระบบ (Roles) </h4>
                                <p class="sub-header"> ข้อมูลประจำวันที่ : {{ currentDate }}</p>
                            </div>
                            <div class="col-md-6 text-right responsive-mobile-btn">
                                <Link :href="route('roles.create')"
                                    class="btn btn-primary waves-effect waves-light float-right mb-2">
                                <i class="fe-plus-square"></i> <span>เพิ่มข้อมูล</span>
                                </Link>
                            </div>
                        </div>
                        <form @submit.prevent="submit">
                            <div class="row">
                                <div class="col-md-9 form-group">
                                    <input type="search" class="form-control" v-model="form.keyword"
                                        placeholder="ค้นหาข้อมูลเพิ่มเติม">
                                </div>
                                <div class="col-md-3 form-group text-right">
                                    <button class="btn btn-dark waves-effect waves-light" type="summit"> <i
                                            class="fe-search"></i> ค้นหา</button>
                                    <Link :href="route('roles.index')" class="btn btn-dark waves-effect waves-light"> <i
                                        class="fe-rotate-cw"></i>
                                    รีเฟรช </Link>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <DataTable :value="roles" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]"
                                sortMode="multiple" tableStyle="width: 100%">
                                <Column sortable field="name" header="ชื่อสิทธิ์การใช้งานระบบ"></Column>
                                <Column sortable field="updated_at" header="เมื่อ">
                                    <template #body="slotProps">
                                        <div>{{ new Date().toLocaleString('th-TH', slotProps.data.updated_at) }}</div>
                                    </template> 
                                </Column>
                                <Column header="#">
                                    <template #body="slotProps">
                                        <div class="text-right">
                                            <Link :href="route('roles.edit', slotProps.data.id)"
                                                class="btn btn-dark waves-effect waves-light"> แก้ไข </Link>
                                            <button type="button" class="btn btn-danger waves-effect waves-light ml-1"
                                                @click="destroy(slotProps.data.id)"
                                                :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                                ลบข้อมูล
                                            </button>
                                        </div>
                                    </template>
                                </Column>
                            </DataTable>

                        </div>
                    </div>
                </div>
            </div>

        </div>

    </AuthenticatedLayout>
</template> 