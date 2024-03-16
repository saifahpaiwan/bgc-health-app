<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, Link, usePage} from "@inertiajs/vue3";
import PageTitleBox from "@/Components/PageTitleBox.vue";
import PrimaryButton from '@/Components/PrimaryButton.vue';
 
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import Dropdown from "primevue/dropdown";

import { ref } from "vue";
import Swal from 'sweetalert2'; 

const page = usePage();  
const ObjSubtitle = ref([
    { message: "รายการข้อมูลผลิตภัณฑ์ยา" },
    { message: "แก้ไขข้อมูลผลิตภัณฑ์ยา" },
]);
  
const props = defineProps({
    product : {
        type: Array,
    },
    productItems: {
        type: Array,
    },
    categcts: {
        type: Array,
    },
    units: {
        type: Array,
    },
    plants: {
        type: Array,
    },
});
 
const date = new Date();
const currentDate = date.toDateString();
const categcts = props.categcts;  
const plants = Object.values(JSON.parse(JSON.stringify(props.plants))); 
const units = Object.values(JSON.parse(JSON.stringify(props.units))); 
const productItems = Object.values(JSON.parse(JSON.stringify(props.productItems))); 
units.push({ id: 0, unit_name: 'ไม่ระบบข้อมูล.' });  
   
const form = useForm({
    product_code: props.product.product_code,
    product_name: props.product.product_name,
    category_id: categcts.find(item => item.id === props.product.category_id),
    description: props.product.description, 
    status: props.product.status,
    filename: (props.product.filename!=null)? '/storage/images/products/'+props.product.filename : null,
    default_price: props.product.default_price,
    unit_id: units.find(item => item.id === props.product.unit_id),
    subunit_id: units.find(item => item.id === props.product.subunit_id),
    quantity_per_unit: props.product.quantity_per_unit,
    quantity_per_subunit: props.product.quantity_per_subunit,
    weight: props.product.weight,
    weight_unit_id: units.find(item => item.id === props.product.weight_unit_id),
    data_plants: props.productItems,
}); 
 
const addDataPlants = (event) => {
    form.data_plants.push({"plant_id": null, "quantity": null, "unit_id": null });
}
   
function deleteDataPlants(index) { 
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
            form.data_plants.splice(index, 1);
            form.put(route('products-items.update', props.product.id), { 
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
    form.put(route('products.update', props.product.id), { 
        onFinish: () => {
            Swal.fire({
                title: "Successfully.",
                text: page.props.flash.success,
                icon: "success",
                showConfirmButton: false,
                timer: 1500
            });
        },
    });
};
</script>

<template>
    <Head title="Products Create" />

    <AuthenticatedLayout>
        <PageTitleBox titlemain="แดชบอร์ด" :subtitles="ObjSubtitle" />
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-1">
                            <div class="col-md-6 responsive-mobile-title">
                                <h4 class="header-title"> แก้ไขข้อมูลผลิตภัณฑ์ยา (Drug product information) </h4>
                                <p class="sub-header"> ข้อมูลประจำวันที่ : {{ currentDate }} </p>
                            </div>
                        </div> 
                       
                        <form @submit.prevent="submit" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <InputLabel for="product_code" value="Medicine Code : " required />
                                                <TextInput id="product_code" type="text" v-model="form.product_code" placeholder="Medicine Code." required />
                                                <InputError class="mt-2" :message="form.errors.product_code" /> 
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <InputLabel  for="product_name" value="ชื่อผลิตภัณฑ์ยา : " required />
                                                <TextInput id="product_name" type="text" v-model="form.product_name" placeholder="Medicine Name." required />
                                                <InputError class="mt-2" :message="form.errors.product_name" />
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group"> 
                                                <InputLabel  for="category_id" value="หมวดหมู่ผลิตภัณฑ์ยา : " required />
                                                <Dropdown v-model="form.category_id" :options="categcts" filter optionLabel="category_name" placeholder="Select a Categcts" class="w-100">
                                                    <template #value="slotProps">
                                                        <div v-if="slotProps.value" class="flex align-items-center">
                                                            <div>{{ slotProps.value.category_name }}</div>
                                                        </div>
                                                        <span v-else>
                                                            {{ slotProps.placeholder }}
                                                        </span>
                                                    </template>
                                                </Dropdown>
                                                 <InputError class="mt-2" :message="form.errors.category_id" />
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <InputLabel for="description" value="รายละเอียดเพิ่มเติม : " />
                                                <textarea class="form-control" rows="3" v-model="form.description" placeholder="Description."></textarea>
                                                <InputError class="mt-2" :message="form.errors.description" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <InputLabel for="images_name" value="รูปผลิตภัณฑ์ยา : " />
                                                <div class="mt-2 img-file-upload-1">
                                                    <div class="box-image-no">
                                                        <img v-if="form.filename" :src="form.filename" alt="Selected Image" />
                                                    </div>
                                                </div>
                                                <div class="mt-1 mb-1"> Size image 500 × 500 px 2MB. </div>
                                                <input type="file" id="images_name" @change="handleFileInputChange"/>
                                            </div>
                                            <InputError class="mt-2" :message="form.errors.filename"/>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <InputLabel for="status-1" value="สถานะการแสดงผล : " required/> 
                                                <div class="mt-2">
                                                    <div class="radio radio-success form-check-inline">
                                                        <input type="radio" id="status-1" value="1" v-model="form.status" checked />
                                                        <label for="status-1">
                                                            <span class="badge badge-success p-1"> Enable </span>
                                                        </label>
                                                    </div>
                                                    <div class="radio radio-secondary form-check-inline">
                                                        <input type="radio" id="status-0" value="0" v-model="form.status" />
                                                        <label for="status-0">
                                                            <span class="badge badge-secondary p-1"> Disable </span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <InputLabel for="default_price" value="ราคา Default : " />
                                                <input type="number" class="form-control" id="default_price" v-model="form.default_price" placeholder="Default Price." required> 
                                                <InputError class="mt-2" :message="form.errors.default_price" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <InputLabel for="unit_id" value="เลือกหน่วยสินค้า : " required />
                                                <Dropdown v-model="form.unit_id" :options="props.units" filter optionLabel="unit_name" placeholder="Select a Unit" class="w-100">
                                                    <template #value="slotProps">
                                                        <div v-if="slotProps.value" class="flex align-items-center">
                                                            <div>{{ slotProps.value.unit_name }}</div>
                                                        </div>
                                                        <span v-else> {{ slotProps.placeholder }} </span>
                                                    </template>
                                                </Dropdown>
                                                <InputError class="mt-2" :message="form.errors.unit_id" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <InputLabel for="subunit_id" value="เลือกหน่วยย่อยสินค้า : " />
                                                <Dropdown v-model="form.subunit_id" :options="units" filter optionLabel="unit_name" placeholder="Select a Subunit" class="w-100">
                                                    <template #value="slotProps">
                                                        <div v-if="slotProps.value" class="flex align-items-center">
                                                            <div>{{ slotProps.value.unit_name }}</div>
                                                        </div>
                                                        <span v-else>
                                                            {{ slotProps.placeholder }}
                                                        </span>
                                                    </template>
                                                </Dropdown>
                                                <InputError class="mt-2" :message="form.errors.subunit_id" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <InputLabel for="quantity_per_unit" value="จำนวนต่อหน่วยต่อการนำเข้า : "  required /> 
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="quantity_per_unit" v-model="form.quantity_per_unit" placeholder="Quantity per Unit." required> 
                                                    <input type="number" class="form-control" id="quantity_per_subunit" v-model="form.quantity_per_subunit" placeholder="Quantity per Unit." required>
                                                </div>  
                                                <code> ตัวอย่างเช่น 1:10, 1:1 </code>
                                                <InputError class="mt-2" :message="form.errors.quantity_per_unit" />
                                                <InputError class="mt-2" :message="form.errors.quantity_per_subunit" />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <InputLabel for="weight" value="ปริมาณ/น้ำหนัก : " />
                                                <input type="number" class="form-control" id="weight" v-model="form.weight" placeholder="Volume/Weight."> 
                                                <InputError class="mt-2" :message="form.errors.weight" />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <InputLabel for="weight_unit_id" value="หน่วยน้ำหนัก : " />
                                                <Dropdown v-model="form.weight_unit_id" :options="units" filter optionLabel="unit_name" placeholder="Select a Unit" class="w-100">
                                                    <template #value="slotProps">
                                                        <div v-if="slotProps.value" class="flex align-items-center">
                                                            <div>{{ slotProps.value.unit_name }}</div>
                                                        </div>
                                                        <span v-else>
                                                            {{ slotProps.placeholder }}
                                                        </span> 
                                                    </template>
                                                </Dropdown>
                                                <InputError class="mt-2" :message="form.errors.subunit_id" />
                                            </div>
                                        </div>  
                                        <div class="col-md-6 form-group"> 
                                            <b class="p-1" style="border: 1px solid #6c757d;"> 
                                                จำนวนต่อหน่วยต่อการ 
                                                <span class="text-blue">นำเข้า</span>/
                                                <span class="text-danger">นำออก</span>
                                            </b> 
                                        </div>
                                        <div class="col-md-6 form-group text-right"> 
                                            <b class="h4 m-0"> 
                                                <span class="ml-1"  v-if="form.unit_id">{{ form.quantity_per_unit }}</span>  
                                                <span class="text-danger ml-1" v-if="form.unit_id"> {{ form.unit_id.unit_name }} : </span>
                                            </b> 
                                            <b class="h4 m-0">  
                                                <span class="ml-1" v-if="form.subunit_id && form.subunit_id.id != 0">{{ form.quantity_per_subunit }}</span>  
                                                <span class="text-danger ml-1" v-if="form.subunit_id && form.subunit_id.id != 0"> {{ form.subunit_id.unit_name }} : </span>
                                            </b>
                                            <b class="h4 m-0">  
                                                <span class="ml-1" v-if="form.weight && form.weight_unit_id.id != 0">{{ form.weight }}</span>  
                                                <span class="text-danger ml-1" v-if="form.weight_unit_id && form.weight_unit_id.id"> {{ form.weight_unit_id.unit_name }} </span>
                                            </b>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 form-group">   
                                            <div class="table-responsive" style="max-height: 255px; border: 1px solid #000;">
                                                <table class="table table-hover mb-0 table-sm">
                                                    <thead class="thead-dark" style="position: sticky; top: 0; z-index: 99;">
                                                        <tr>
                                                            <th class="text-white vertical-align-middle"> #ระบุ Plant ที่จัดการ </th>
                                                            <th class="text-white vertical-align-middle" width="40%"> ระบุปริมาณขั้นต่ำ (Safety Stock) </th>
                                                            <th class="text-white vertical-align-middle" width="5%"> 
                                                                <button type="button" class="btn btn-primary" style="border: 0;" @click="addDataPlants">
                                                                    <i class="fe-plus-square"></i>
                                                                </button>
                                                             </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody v-if="form.data_plants.length!=0">
                                                        <tr v-for="(item, index) in form.data_plants" :key="index">
                                                            <td>
                                                                <select class="form-control" v-model="item.plant_id" :id="item.id">
                                                                    <option v-for="item2 in plants" :key="item2.id" :value="item2.id"> {{ item2.plant }} </option>  
                                                                </select>
                                                            </td>  
                                                            <td>  
                                                                <div class="input-group">
                                                                    <input type="number" v-model="item.quantity" :id="item.id" class="form-control" placeholder="ระบุจำนวน" style="width: 55%" />
                                                                    <select class="form-control" v-model="item.unit_id" :id="item.id" style="width: 45%"> 
                                                                        <option :value="form.unit_id.id" v-if="form.unit_id && form.unit_id.id">{{ form.unit_id.unit_name }}</option>
                                                                        <option :value="form.subunit_id.id" v-if="form.subunit_id && form.subunit_id.id">{{ form.subunit_id.unit_name }}</option>
                                                                        <option :value="form.weight_unit_id.id" v-if="form.weight_unit_id && form.weight_unit_id.id">{{ form.weight_unit_id.unit_name }}</option>
                                                                    </select>
                                                                </div>
                                                            </td>
                                                            <td>  
                                                                 <button type="button" class="btn btn-danger" @click="deleteDataPlants(index)"><i class="fe-trash-2"></i></button> 
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tbody v-else>
                                                        <tr>
                                                            <td colspan="3" class="text-center"> โปรดระบุข้อมูล </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 text-right">
                                    <hr />
                                    <Link :href="route('products.index')" class="btn btn-dark waves-effect waves-light">
                                        <i class="fe-chevron-left"></i> ย้อนกลับ
                                    </Link>
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
.vertical-align-middle {
    vertical-align: middle;
}
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
