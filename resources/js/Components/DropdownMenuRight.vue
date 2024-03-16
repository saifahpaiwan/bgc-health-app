<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const isOpen = ref(false);

const isToggleDropdown = () => {
    isOpen.value = !isOpen.value;
}  
</script>

<template>
    <ul class="list-unstyled topnav-menu float-right mb-0 d-flex align-items-center">
        <li class="dropdown notification-list" :class="{ 'show': isOpen, '': !isOpen }">
            <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light d-flex justify-content-center align-items-center"
                data-toggle="dropdown" role="button" aria-haspopup="false" aria-expanded="false" @click="isToggleDropdown"> 
                <span class="pro-user-name ml-1 text-white">
                    {{ $page.props.auth.user.username }}  <i class="mdi mdi-chevron-down"></i>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right profile-dropdown" :class="{ 'show': isOpen, '': !isOpen }">
                <div class="dropdown-header noti-title">
                    <h6 class="text-overflow m-0"> ยินดีต้อนรับเข้าสู่ระบบ </h6>
                </div> 
                <Link class="dropdown-item notify-item" :href="route('profile.edit')">
                    <i class="fe-settings"></i>
                    <span>ตั้งค่าข้อมูลส่วนตัว</span>
                </Link>
                <div class="dropdown-divider"></div>
                <Link class="dropdown-item notify-item" :href="route('logout')" method="post" as="button">
                    <i class="fe-log-out"></i>
                    <span>ออกจากระบบ</span>
                </Link>
            </div>
        </li>
    </ul>
</template>
 