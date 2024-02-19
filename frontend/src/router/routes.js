// router.js
import { createRouter, createWebHistory } from 'vue-router';
import Home from '../components/Home.vue';
import BloodChecklist from '../components/allergy_blood/BloodChecklist.vue';
import BloodChecklistRS from '../components/allergy_blood/BloodChecklistRS.vue';
import MainPage from '../components/mainPage/MainPage.vue';

const routes = [
  { path: '/', component: BloodChecklist, name: 'blood-checklist' },
  { path: '/blood-checklist-rs', component: BloodChecklistRS, name: 'blood-checklist-rs' },
  { path: '/mainBloodChecklist', component: MainPage, name: 'MainPage' },
  // Add more routes as needed
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
