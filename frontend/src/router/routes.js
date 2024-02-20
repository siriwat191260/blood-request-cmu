// router.js
import { createRouter, createWebHistory } from 'vue-router';
import Home from '../components/Home.vue';
import BloodChecklist from '../components/allergy_blood/BloodChecklist.vue';
import TransfusionForm from '../components/allergy_blood/TransfusionForm.vue';

const routes = [
  { path: '/', component: BloodChecklist, name: 'blood-checklist' },
  { path: '/transfusion-form', component: TransfusionForm, name: 'TransfusionForm' },
  // Add more routes as needed
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
