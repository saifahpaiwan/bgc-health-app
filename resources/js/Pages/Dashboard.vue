<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template> 
    <AuthenticatedLayout>
        <div class="card"> 
            <div class="card-body">
                <blockquote class="card-bodyquote">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere
                        erat a ante.</p>
                    <footer>Someone famous in <cite title="Source Title">Source Title</cite>
                    </footer>
                </blockquote>
            </div>
        </div> 
    </AuthenticatedLayout>
</template>
