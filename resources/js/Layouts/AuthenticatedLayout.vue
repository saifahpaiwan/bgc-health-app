<script setup>
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import DropdownLeftMenu from '@/Components/DropdownLeftMenu.vue';
import DropdownMenuRight from '@/Components/DropdownMenuRight.vue'; 

const appName = import.meta.env.VITE_APP_NAME || 'Laravel'; 
const isMenuClassActive = ref(true);
const date = new Date();
const currentYear = date.getFullYear();

const toggleClass = () => {
    isMenuClassActive.value = !isMenuClassActive.value;
    updateBodyClass();
};

const updateBodyClass = () => {
    if (isMenuClassActive.value) {
        document.body.classList.add('sidebar-enable');
        document.body.classList.remove('enlarged');
    } else {
        document.body.classList.add('enlarged');
        document.body.classList.remove('sidebar-enable');
    }
};

// ====================Dropdown Left Menu==================== //
const menuReportStock = ref([
    { menuname: 'รายงานการนำเข้าผลิตภัณฑ์ยา', active: '', url: route('users.index') },
    { menuname: 'รายการข้อมูลผู้ใช้งาน', active: '', url: route('users.index') },
    { menuname: 'รายงานจำนวนยอดคงเหลือ', active: '', url: route('users.index') },
]);

const menuUsersSettings = ref([
    { menuname: 'ผู้ใช้งานระบบ', active: 'users.index', url: route('users.index') },
    { menuname: 'กำหนดสิทธิ์', active: 'roles.index', url: route('roles.index') },
    { menuname: 'Logs System', active: 'log-viewer', url: '/log-viewer' },
]);

const menuBasicinformation = ref([
    { menuname: 'หมวดหมู่ผลิตภัณฑ์ยา', active: 'categcts.index', url: route('categcts.index') }, 
    { menuname: 'ข้อมูลหน่วยนับ', active: 'units.index', url: route('units.index') }, 
]); 
</script> 
<template>
    <div>

        <div id="wrapper">
            <div class="navbar-custom">
                <DropdownMenuRight/>
                <div class="logo-box">
                    <Link :href="route('dashboard')" class="logo text-center">
                    <span class="logo-lg">
                        <img src="/favicon.ico" height="30" style="border-radius: 0.1rem;">
                        <small class="ml-2 text-white"> {{ appName }} </small>
                    </span>
                    <span class="logo-sm">
                        <img class="rounded-circle" src="/favicon.ico" width="35" height="35">
                    </span>
                    </Link>
                </div>
                <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                    <li>
                        <button class="button-menu-mobile waves-effect waves-light" @click="toggleClass">
                            <i class="fe-menu"></i>
                        </button>
                    </li>
                </ul>
            </div>
            <div class="left-side-menu">
                <div class="slimscroll-menu">
                    <div id="sidebar-menu">
                        <ul class="metismenu" id="side-menu">
                            <li class="menu-title"> Menu List </li>
                            <li>
                                <Link :href="route('dashboard')"
                                    :class="{ 'active': route().current('dashboard'), '': !route().current('dashboard') }">
                                <i class="fe-airplay"></i>
                                <span> แดชบอร์ด </span>
                                </Link>
                            </li>
                            <li>
                                <Link :href="route('dispensing-medicine.create')">
                                <i class="fe-file-text"></i>
                                <span> การจ่ายยา </span>
                                </Link>
                            </li>
                            <li>
                                <Link :href="route('products.index')">
                                <i class="fe-box"></i>
                                <span> ข้อมูลผลิตภัณฑ์ยา </span>
                                </Link>
                            </li>
                            <li>
                                <Link :href="route('import-stock.create')">
                                <i class="fe-log-in"></i>
                                <span> นำเข้าสต๊อกยา </span>
                                </Link>
                            </li> 
                            <DropdownLeftMenu :name="'รายงานสต๊อก'" :icon="'fe-bar-chart-2'" :items="menuReportStock" />
                            <li class="menu-title"> Management Authenticate </li>
                            <DropdownLeftMenu :name="'ข้อมูลพื้นฐาน'" :icon="'fe-folder'" :items="menuBasicinformation" /> 
                            <DropdownLeftMenu :name="'การตั้งค่าระบบ'" :icon="'fe-settings'" :items="menuUsersSettings" /> 
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="content-page">
                <div class="content">
                    <!-- Page Content -->
                    <main> 
                        <slot />
                    </main>
                </div>
                <footer class="footer">
                    <div class="row">
                        <div class="col-md-12"> 
                            <span v-if="currentYear=='2024'">{{ currentYear }}</span>
                            <span v-else> 2024 - {{ currentYear }}  </span>
                            © {{ appName }}
                        </div>
                    </div>
                </footer>
            </div>


        </div>
    </div>
</template> 

<style scoped>  
</style>