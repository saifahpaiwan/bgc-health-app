<script setup>
import { ref, onMounted } from 'vue'; 
import { useForm, Link, usePage } from '@inertiajs/vue3'; 
import Swal from 'sweetalert2'; 
import Dropdown from 'primevue/dropdown';

const props = defineProps({
    titlemain: {
        type: String, 
    },
    subtitles: {
        type: Object,
    } 
});   

const plantsall = usePage().props.plantsall;    
const plantsArr = Object.values(JSON.parse(JSON.stringify(plantsall)));   
const seletePlant = usePage().props.seletePlant;   
const PlantID = (plantsArr.find(item => item.id === seletePlant))? plantsArr.find(item => item.id === seletePlant).id : null;
 
const form = useForm({
    selectedOption: plantsArr.find(item => item.id === seletePlant),
}); 

 
const page = usePage();  
const changeSelectPlant = (event) => {  
    form.post(route("set.selected.option"), {
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
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a> <span> {{ props.titlemain }}</span> </a>
                        </li>
                        <li class="breadcrumb-item active" v-for="(item, index) in props.subtitles" :key="index"> {{ item.message }}</li>
                    </ol>
                </div>
                <h5 class="page-title d-flex align-items-center">
                    <span class="plant-title"> PLANT : {{ $page.props.auth.userPlant }} </span>  
                    <Dropdown v-model="form.selectedOption" :options="plantsArr" filter optionLabel="plant" placeholder="Select a Plant" class="ml-2" @change="changeSelectPlant">
                        <template #value="slotProps">
                            <div v-if="slotProps.value" class="flex align-items-center">
                                <div style="position: relative;top: -8px;">{{ slotProps.value.plant }}</div>
                            </div>
                            <span v-else style="position: relative;top: -8px;">
                                {{ slotProps.placeholder }}
                            </span>
                        </template>
                    </Dropdown>
                </h5>
            </div>
        </div>
    </div>
</template>
<style scope>
    .plant-title {
        background: #ff0909;
        color: #fff;
        padding: 0 1rem;
        border-radius: 0.2rem;
    }  
</style>