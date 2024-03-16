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

const ObjSubtitle = ref([{ message: 'รายการข้อมูลการนำเข้าข้อมูลผลิตภัณฑ์ยา (Importing Medicine)' }]);
const date = new Date();
const currentDate = date.toDateString();

const props = defineProps({
    stock_transactions: {
        type: Array
    }
});

const stock_transactions = Object.values(JSON.parse(JSON.stringify(props.stock_transactions)));
const form = useForm({
    date: ref(),
    keyword: ref(), 
    status: ref()
});

const status = ref([
    { id: '-1', name: 'Disable' },
    { id: '1', name: 'Enable' }
]);
 
const submit = () => {
    form.get(route('import-stock.index'));
};
 
</script>

<template>

    <Head title="Import Stock List" />

    <AuthenticatedLayout>
        <PageTitleBox titlemain="แดชบอร์ด" :subtitles="ObjSubtitle" />
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-1">
                            <div class="col-md-6 responsive-mobile-title ">
                                <h4 class="header-title"> รายการข้อมูลการนำเข้าข้อมูลผลิตภัณฑ์ยา (Importing Medicine) </h4>
                                <p class="sub-header"> ข้อมูลประจำวันที่ : {{ currentDate }}</p>
                            </div>
                            <div class="col-md-6 text-right responsive-mobile-btn">
                                <Link :href="route('import-stock.create')"
                                    class="btn btn-primary waves-effect waves-light float-right mb-2">
                                    <i class="fe-log-in"></i> <span> นำเข้าสต๊อกยา </span>
                                </Link>
                            </div>
                        </div>
                        <form @submit.prevent="submit">
                            <div class="row">
                                <div class="col-md-2 form-group">
                                    <input type="date" class="form-control" v-model="form.date">
                                </div>
                                 <div class="col-md-5 form-group">
                                    <input type="search" class="form-control" v-model="form.keyword"
                                        placeholder="ค้นหาข้อมูลเพิ่มเติม">
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
                                    <button class="btn btn-dark waves-effect waves-light" type="summit"> 
                                        <i class="fe-search"></i> ค้นหา</button>
                                    <Link :href="route('import-stock.index')" class="btn btn-dark waves-effect waves-light">
                                    <i class="fe-rotate-cw"></i>
                                    รีเฟรช </Link>
                                </div>
                            </div>
                        </form> 
                        <div class="table-responsive"> 
                            <DataTable :value="stock_transactions" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" sortMode="multiple" tableStyle="width: 100%"> 
                                 <Column sortable field="transaction_date" header="วันที่นำเข้า">
                                    <template #body="slotProps">
                                        <div>{{ new Date().toLocaleString('th-TH', slotProps.data.transaction_date) }}</div>
                                    </template>
                                </Column>
                                <Column sortable field="description" header="รายละเอียดการนำเข้า"></Column> 
                                <Column sortable field="countitems" header="จำนวนรายการ">
                                    <template #body="slotProps">
                                        <span class="badge badge-secondary p-1">{{ slotProps.data.countitems }} item</span> 
                                    </template>
                                </Column> 
                                <Column sortable field="create_uid" header="โดย">
                                    <template #body="slotProps">
                                        <span class="badge badge-danger p-1">{{  slotProps.data.plant_id }}</span>
                                        <span class="ml-1">{{  slotProps.data.create_uid }}</span>
                                    </template>
                                </Column>  
                                <Column header="#"> 
                                    <template #body="slotProps">
                                        <div class="text-right">
                                            <Link :href="route('import-stock.show', slotProps.data.id)" class="btn btn-dark waves-effect waves-light"> ดูรายการข้อมูล </Link> 
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