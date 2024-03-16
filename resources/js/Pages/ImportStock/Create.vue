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
    { message: 'เพิ่มการนำเข้าข้อมูลผลิตภัณฑ์ยา' },
]);

const props = defineProps({
    CheckPlant: {
        type: String
    },
    products: {
        type: Array
    }
});


const date = new Date();
const currentDate = date.toDateString();
const products = Object.values(JSON.parse(JSON.stringify(props.products))); 
 
const form = useForm({
    transaction_date: null,
    description: null,
    product_items: [], 
}); 
 
const submit = () => {
    form.post(route('import-stock.store'), {
        onFinish: (e) => {   
            if(form.errors.plant_id){
                Swal.fire({
                    title: "Warning !",
                    text: form.errors.plant_id,
                    icon: "warning", 
                    confirmButtonColor: "#3085d6",
                });
            } else {
                Swal.fire({
                    title: "Successfully.",
                    text: page.props.flash.success,
                    icon: "success",
                    showConfirmButton: false,
                    timer: 1500
                });
            } 
        }
    });
};

// =================== //
const isDropdown  = ref(false);
const isInpupData = ref(1);
const seletedProductID = ref(null);
const uniqueArray      = [];
const barcode          = ref(null);
 
const OpenDropdown = () => {
    isDropdown.value = !isDropdown.value; 
};
 
const deleteDataRows = (index) => {   
    form.product_items.splice(index, 1);
    uniqueArray.splice(index, 1);  
};

function changeInpupData(index) {  
    isInpupData.value = index;
    isDropdown.value = false;  
};

const addProductItems = () => {   
    if(seletedProductID.value!=null){ 
        uniqueArray.push(JSON.parse(JSON.stringify(seletedProductID.value)));
        seletedProductID.value = null; 
        uniqueArray.forEach(obj => {
            if (!form.product_items.some(item => item.id === obj.id)) {
                form.product_items.push(obj);   
            }
        });  
    }  
}; 

const handleBarcodeInput = (event) => { 
    if(event.target.value!=null){ 
        const searchProductCode = event.target.value;
        const filteredProducts = products.filter(product => product.product_code === searchProductCode);   
        filteredProducts.forEach(obj => {
            if (!form.product_items.some(item => item.id === obj.id)) { 
                form.product_items.push(obj); 
                barcode.value = null;
            } else {
                barcode.value = null;
            }
        }); 
    }  
};  
    
</script>

<template>

    <Head title="Import Stock Create" />

    <AuthenticatedLayout>
        <PageTitleBox titlemain="แดชบอร์ด" :subtitles="ObjSubtitle" />
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-1">
                            <div class="col-md-6 responsive-mobile-title ">
                                <h4 class="header-title"> เพิ่มการนำเข้าข้อมูลผลิตภัณฑ์ยา (Importing Medicine) </h4>
                                <p class="sub-header"> ข้อมูลประจำวันที่ : {{ currentDate }}</p>
                            </div>
                            <div class="col-md-6 text-right responsive-mobile-btn"> 
                                 <Link :href="route('import-stock.index')"
                                    class="btn btn-dark waves-effect waves-light mb-2">
                                <i class="fe-file-text"></i> ดูรายการประวัติการนำเข้า
                                </Link> 
                            </div>
                        </div> 
                         
                        <div class="alert alert-icon alert-warning text-warning alert-dismissible show" role="alert" v-show="props.CheckPlant">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <i class="mdi mdi-alert mr-2"></i>
                            <strong>มีข้อผิดผลาด !</strong> {{ props.CheckPlant }}
                        </div>

                        <form @submit.prevent="submit" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group"> 
                                                <InputLabel for="transaction_date" value="วันที่นำเข้า : " required />
                                                <input type="date" class="form-control" v-model="form.transaction_date" required>
                                                <InputError class="mt-2" :message="form.errors.transaction_date" />
                                            </div>
                                        </div>  
                                        <div class="col-md-12 form-group">
                                            <label for="description"> รายละเอียดการนำเข้า </label>
                                            <textarea class="form-control" rows="3" v-model="form.description" placeholder="Description."> </textarea> 
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <PrimaryButton class="w-100" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                                <i class="fe-save"></i> บันทึก
                                            </PrimaryButton>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="d-flex" style="margin-top: 26px;">  
                                        <div style="width: 70%;">
                                            <Dropdown v-model="seletedProductID" :options="products" filter optionLabel="product_name" 
                                            placeholder="Select a Products" class="w-100" required v-if="isInpupData==1">
                                                <template #value="slotProps">
                                                    <div v-if="slotProps.value" class="flex align-items-center">
                                                        <div>{{ slotProps.value.product_name }}</div>
                                                    </div>
                                                    <span v-else>
                                                        {{ slotProps.placeholder }}
                                                    </span>
                                                </template>
                                            </Dropdown>
                                            <div class="input-group" v-if="isInpupData==2">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="mdi mdi-barcode-scan"></i></span> 
                                                </div>
                                                <input class="form-control" v-model="barcode" @keyup="handleBarcodeInput" type="text" placeholder="Scan Barcode">
                                            </div>  
                                        </div>
                                        <div class="text-right" style="width: 30%;"> 
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary waves-effect waves-light" @click="addProductItems">
                                                    <i class="fe-plus-circle"></i> เพิ่ม
                                                </button> 
                                                <div class="ml-1">
                                                    <button type="button" class="btn btn-secondary waves-effect waves-light" @click="OpenDropdown">
                                                        <span id="text-bottom-dropdown" class="mr-1" v-if="isInpupData==1">ค้นหาผลิตภัณฑ์ยา</span> 
                                                        <span id="text-bottom-dropdown" class="mr-1" v-if="isInpupData==2">Scan Code</span> 
                                                        <i class="mdi mdi-chevron-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu show" v-show="isDropdown" style="right: 0;">
                                                        <button class="dropdown-item btn-seleted-type" @click="changeInpupData(1)">ค้นหาผลิตภัณฑ์ยา</button>
                                                        <button class="dropdown-item btn-seleted-type" @click="changeInpupData(2)">Scan Code</button>
                                                    </div>
                                                </div>
                                            </div> 
                                        </div> 
                                    </div>
                                    <hr> 
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive" style="max-height: 255px; border: 1px solid #000;"> 
                                                <table class="table table-hover mb-0 table-sm">
                                                    <thead class="thead-dark" style="position: sticky; top: 0; z-index: 99;">
                                                        <tr>
                                                            <th class="text-white">#รายการผลิตภัณฑ์ยา</th>
                                                            <th class="text-white" width="55%">จำนวน | หน่วย | วันหมดอายุของ (Products) ที่นำเข้า</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody v-if="form.product_items.length > 0">
                                                        <tr v-for="(item, index) in form.product_items" :key="index">
                                                            <td style="vertical-align: middle;">
                                                                <div class="d-flex">
                                                                    <img :src="`/storage/images/products/${item.product_images}`" alt="" width="50" height="50">
                                                                    <div class="ml-2">
                                                                        <div class="white-space-1"> <b class="text-danger">{{ item.product_code }}</b> | <b>{{ item.product_name }}</b></div>
                                                                        <div class="white-space-1">{{ item.product_description }}</div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td style="vertical-align: middle;">
                                                                <div class="d-flex"> 
                                                                    <div class="m-05" style="width: 20%;">
                                                                        <input type="number" v-model="form.product_items[index].quantity" class="form-control" required> 
                                                                    </div>
                                                                    <div class="m-05" style="width: 40%;">
                                                                        <select v-model="form.product_items[index].units" class="form-control" required>  
                                                                            <option v-if="item.unit_id!=null" :value="'1,'+item.unit_id">{{ item.unit_name }}</option> 
                                                                            <option v-if="item.subunit_id!=null" :value="'2,'+item.subunit_id">{{ item.subunit_name }}</option>
                                                                        </select> 
                                                                    </div>
                                                                    <div class="m-05" style="width: 40%;">
                                                                        <input type="date" v-model="form.product_items[index].expires_at" class="form-control" required>
                                                                    </div>
                                                                    <div class="m-05">  
                                                                        <button class="btn btn-danger waves-effect waves-light" type="button" @click="deleteDataRows(index)"><i class="fe-trash-2"></i></button>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tbody v-if="form.product_items.length == 0">
                                                        <tr>
                                                            <td colspan="2" class="text-center"> โปรดระบุข้อมูล </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
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
.m-05 {
    margin: 0 0.15rem;
}

.white-space-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: normal;
}
</style>