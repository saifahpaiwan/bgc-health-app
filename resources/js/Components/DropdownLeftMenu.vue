<script setup>
import { onMounted, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
  
const isDropdownActive = ref(false);
const props = defineProps({
    name: {
        type: String,
    },
    icon: {
        type: String,
    },
    items: {
        type: Object,
    },
});

const OpenDropdown = () => {
    isDropdownActive.value = !isDropdownActive.value;
};
   
const routeCurrent = route().current();  
onMounted(() => {  
    for (let index = 0; index < props.items.length; index++) {
        const element = props.items[index].active; 
        if (route().current() == element) { 
            isDropdownActive.value = ref(true);
        }
    } 
});
</script>

<template>
    <li :class="{ 'mm-active': isDropdownActive, '': !isDropdownActive }">
        <a @click="OpenDropdown(1)" :class="{ 'active': isDropdownActive, '': !isDropdownActive }">
            <i :class="props.icon"></i>
            <span> {{ props.name }} </span>
            <span class="menu-arrow"></span>
        </a>
        <ul class="nav-second-level" :class="{ 'mm-collapse mm-show': isDropdownActive, 'mm-collapse': !isDropdownActive }"
            aria-expanded="false">
            <li v-for="(item, index) in props.items" :key="index">
                <a :href="item.url" target="_blank" v-if="item.active=='log-viewer'">{{ item.menuname }}</a> 
                <Link v-else :href="item.url" :class="{ 'sub-active': routeCurrent == item.active, '': routeCurrent != item.active }"> {{ item.menuname }} </Link> 
            </li> 
        </ul>
    </li>
</template>

<style scoped>
.sub-active {
    color: #FFF;
}
</style>
