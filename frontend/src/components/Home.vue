<!-- Home.vue -->
<template>
    <div>
      <router-link to="/allergyblood">
        <button @click="goToAnotherPage">Go to Another Page</button>
      </router-link>
    </div>
  </template>
  
  <script>
  import { defineComponent } from 'vue';
  import { useRouter } from 'vue-router';
  
  export default defineComponent({
    name: 'Home',
    methods: {
      goToAnotherPage() {
        const router = useRouter();
        router.push('/allergyblood');
      }
    }
  });
  </script>
  
  <style>
  /* Your styles for the home component */
  </style>
  