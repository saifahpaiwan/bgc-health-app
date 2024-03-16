<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link, usePage } from '@inertiajs/vue3';
import PageTitleBox from '@/Components/PageTitleBox.vue'; 
import Dropdown from "primevue/dropdown";
import PrimaryButton from '@/Components/PrimaryButton.vue';

import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue'; 


import { ref } from 'vue';
import Swal from 'sweetalert2'; 

const page = usePage(); 
const ObjSubtitle = ref([
    { message: 'รายการการนำเข้าข้อมูลผลิตภัณฑ์ยา' },
    { message: 'ตรวจสอบรายการนำเข้าข้อมูลผลิตภัณฑ์ยา' },
]); 

const props = defineProps({
    stock_transactions: {
        type: Object
    },
    stock_transactions_items: {
        type: Object
    }
});

const form = useForm({
    transaction_id: props.stock_transactions.id,  
});

const date = new Date();
const currentDate = date.toDateString(); 
const stock_transactions_items = Object.values(JSON.parse(JSON.stringify(props.stock_transactions_items))); 

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
            form.delete(route("import-stock.destroy", id), {
                onFinish: () => {
                    Swal.fire({
                        title: "Deleted!",
                        text: "Your file has been deleted.",
                        icon: "success",
                        showConfirmButton: false,
                         timer: 1500
                    });
                }
            }); 
        }
    }); 
}
</script>

<template>

    <Head title="Import Stock Create" />

    <AuthenticatedLayout>
        <PageTitleBox titlemain="แดชบอร์ด" :subtitles="ObjSubtitle" />
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 responsive-mobile-title ">
                                <h4 class="header-title"> ตรวจสอบรายการนำเข้าข้อมูลผลิตภัณฑ์ยา </h4>
                                <p class="m-0"> ข้อมูลประจำวันที่ : {{ currentDate }}</p>
                            </div>
                            <div class="col-md-6 text-right responsive-mobile-btn"> 
                                 <Link :href="route('import-stock.index')"
                                    class="btn btn-primary waves-effect waves-light mb-2">
                                    <i class="fe-chevron-left"></i> ย้อนกลับ
                                </Link> 
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <h5 class="header-title"> วันเวลาที่นำเข้าข้อมูล </h5>
                                <p>{{ props.stock_transactions.transaction_date }}</p>
                            </div>
                            <div class="col-md-4">
                                <h5 class="header-title"> ผู้ทำรายการ </h5>
                                <p>
                                    <span class="badge badge-danger p-1">{{ props.stock_transactions.plant }}</span>
                                    <span class="ml-1">{{ stock_transactions.create }}</span>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <h5 class="header-title"> จำนวนรายการ </h5>
                                <p> {{ stock_transactions_items.length }} รายการ</p>
                            </div>
                             <div class="col-md-12">
                                <h5 class="header-title"> รายละเอียดเพิ่มเติม </h5>
                                <p>{{ props.stock_transactions.description }}</p>
                            </div> 
                        </div> 
                        <div class="table-responsive"> 
                            <table class="table table-sm m-0 table-colored-bordered table-bordered-dark">
                                <thead>
                                    <tr>
                                        <th class="text-white">#รายการผลิตภัณฑ์ยา</th>
                                        <th class="text-white" width="10%">จำนวน</th>
                                        <th class="text-white" width="15%">หน่วย</th>
                                        <th class="text-white" width="20%">วันหมดอายุของยา</th> 
                                    </tr>
                                </thead> 
                                <tbody>
                                    <tr v-for="(item, index) in stock_transactions_items" :key="index">
                                        <td style="vertical-align: middle;">
                                            <div class="d-flex">
                                                <img :src="`/storage/images/products/${item.product_images}`" alt="" width="50" height="50">
                                                <div class="ml-2">
                                                    <div class="white-space-1"> <b class="text-danger">{{ item.product_code }}</b> | <b>{{ item.product_name }}</b></div>
                                                    <div class="white-space-1">{{ item.product_description }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="vertical-align: middle;"> {{ item.quantity_changed }} </td>
                                        <td style="vertical-align: middle;"> {{ item.units }} </td>
                                        <td style="vertical-align: middle;"> {{ item.expires_at }} </td>
                                    </tr> 
                                </tbody>
                                <tbody v-if="stock_transactions_items.length == 0">
                                    <tr> 
                                        <td colspan="4" class="text-center"> โปรดระบุข้อมูล </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-danger waves-effect waves-light w-100"
                                    @click.prevent="destroy(props.stock_transactions.id)"
                                    :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    <i class="fe-x-circle"></i> ทำการยกเลิกข้อมูลการนำเข้า
                                </button>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<style scoped>
.white-space-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: normal;
}
</style>