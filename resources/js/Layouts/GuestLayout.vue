<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3'; 
</script>

<template> 
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card mt-5">
                    <div class="card-body">
                        <ApplicationLogo /> 
                        <slot />
                    </div>
                </div> 
            </div>
        </div>
    </div>
</template>
