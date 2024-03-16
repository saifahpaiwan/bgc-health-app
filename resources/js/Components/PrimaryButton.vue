<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit'
    },
    disabled: {
        type: Boolean,
        default: false
    }
})
</script>

<template>
    <button class="btn btn-primary waves-effect waves-light"
        :type="type"
        :disabled="disabled"
        :class="{ 'opacity-25': disabled }"
    >
        <slot /> 
        <i v-show="disabled" class="mdi mdi-spin mdi-loading ml-1"> </i>
    </button>
</template>