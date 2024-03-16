<script setup>
   const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
</script>

<template>
   <div class="mb-2 text-center">
      <img src="/favicon.ico" alt="" height="60">
      <div>Login {{ appName }}</div>
   </div>
</template>
