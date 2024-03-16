<script setup>
defineProps({
    value: {
        type: String,
    },
    required: {
        type: Boolean
    }
});
 
</script>

<template>
    <label class="">
        <span v-if="value">{{ value }} </span>
        <span v-else><slot /></span>
 
        <span class="text-danger ml-2" v-if="required">*</span>
    </label>
</template>
