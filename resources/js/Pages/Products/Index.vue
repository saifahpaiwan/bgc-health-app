<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PageTitleBox from '@/Components/PageTitleBox.vue';
import { ref, onMounted } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Dropdown from 'primevue/dropdown';
import Swal from 'sweetalert2'; 

const ObjSubtitle = ref([{ message: 'รายการข้อมูลผลิตภัณฑ์ยา' }]);
const date = new Date();
const currentDate = date.toDateString();

const props = defineProps({
    products: {
        type: Array
    },
    categcts: {
        type: Array
    }
});

const form = useForm({
    keyword: ref(), 
    status: ref(),
    categcts: ref(),
});

const status = ref([
    { id: '-1', name: 'Disable' },
    { id: '1', name: 'Enable' }
]);

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
            form.delete(route("products.destroy", id), {
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
 

const products = Object.values(JSON.parse(JSON.stringify(props.products)));

const submit = () => {
    form.get(route('products.index'));
};
 
</script>

<template>

    <Head title="Products List" />

    <AuthenticatedLayout>
        <PageTitleBox titlemain="แดชบอร์ด" :subtitles="ObjSubtitle" />
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-1">
                            <div class="col-md-6 responsive-mobile-title ">
                                <h4 class="header-title"> รายการข้อมูลผลิตภัณฑ์ยา (Drug product information) </h4>
                                <p class="sub-header"> ข้อมูลประจำวันที่ : {{ currentDate }}</p>
                            </div>
                            <div class="col-md-6 text-right responsive-mobile-btn">
                                <Link :href="route('products.create')"
                                    class="btn btn-primary waves-effect waves-light float-right mb-2">
                                <i class="fe-plus-square"></i> <span>เพิ่มข้อมูล</span>
                                </Link>
                            </div>
                        </div>
                        <form @submit.prevent="submit">
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <input type="search" class="form-control" v-model="form.keyword"
                                        placeholder="ค้นหาข้อมูลเพิ่มเติม">
                                </div>
                                <div class="col-md-3 form-group">
                                    <Dropdown v-model="form.categcts" :options="categcts" filter optionLabel="category_name"
                                        placeholder="Select a Categcts" class="w-100">
                                        <template #value="slotProps">
                                            <div v-if="slotProps.value" class="flex align-items-center">
                                                <div>{{ slotProps.value.category_name }}</div>
                                            </div>
                                            <span v-else>
                                                {{ slotProps.placeholder }}
                                            </span>
                                        </template>
                                    </Dropdown>
                                </div>
                                <div class="col-md-2 form-group">
                                    <Dropdown v-model="form.status" :options="status" filter optionLabel="name"
                                        placeholder="Select a Status" class="w-100">
                                        <template #value="slotProps">
                                            <div v-if="slotProps.value" class="flex align-items-center">
                                                <div>{{ slotProps.value.name }}</div>
                                            </div>
                                            <span v-else>
                                                {{ slotProps.placeholder }}
                                            </span>
                                        </template>
                                    </Dropdown>
                                </div>
                                <div class="col-md-3 form-group text-right">
                                    <button class="btn btn-dark waves-effect waves-light" type="summit"> <i
                                            class="fe-search"></i> ค้นหา</button>
                                    <Link :href="route('products.index')" class="btn btn-dark waves-effect waves-light">
                                    <i class="fe-rotate-cw"></i>
                                    รีเฟรช </Link>
                                </div>
                            </div>
                        </form> 
                        <div class="table-responsive">
                            <DataTable :value="products" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]"
                                sortMode="multiple" tableStyle="width: 100%">
                                <Column field="product_images" header="รูป">
                                    <template #body="slotProps">
                                        <img :src="`/favicon.ico`" :alt="slotProps.data.product_images"
                                            class="rounded-circle" height="50" width="50"
                                            v-if="slotProps.data.product_images == null" />
                                        <img :src="`storage/images/products/${slotProps.data.product_images}`"
                                            :alt="slotProps.data.product_images" class="rounded-circle" height="50"
                                            width="50" v-else />
                                    </template>
                                </Column>
                                <Column sortable field="product_code" header="Medicine Code"></Column>
                                <Column sortable field="product_name" header="Medicine Name"></Column> 
                                <Column sortable field="status" header="สถานะ"> 
                                    <template #body="slotProps">
                                        <span class="badge badge-success p-1"
                                            v-if="slotProps.data.status == 1">Enable</span>
                                        <span class="badge badge-secondary p-1"
                                            v-else-if="slotProps.data.status == 0">Disabled</span>
                                    </template>
                                </Column>
                                <Column sortable field="updated_at" header="เมื่อ"> 
                                    <template #body="slotProps">
                                        <div>{{ new Date().toLocaleString('th-TH', slotProps.data.updated_at) }}</div>
                                    </template>
                                </Column>
                                <Column header="#">

                                    <template #body="slotProps">
                                        <div class="text-right">
                                            <Link :href="route('products.edit', slotProps.data.id)"
                                                class="btn btn-dark waves-effect waves-light"> จัดการข้อมูล </Link>
                                            <button type="button" class="btn btn-danger waves-effect waves-light ml-1"
                                                @click.prevent="destroy(slotProps.data.id)"
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