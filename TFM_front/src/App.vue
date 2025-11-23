<script setup>
import AppNavbar from './components/AppNavbar.vue'
</script>

<template>
  <AppNavbar />
  <main>
    <router-view />
  </main>
</template>

<style scoped>
main {
  padding: 1rem;
}
</style>
