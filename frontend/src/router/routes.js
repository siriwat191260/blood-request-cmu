// router.js
import { createRouter, createWebHistory } from 'vue-router';
import Home from '../components/Home.vue';
import BloodChecklist from '../components/allergy_blood/BloodChecklist.vue';
import TransfusionForm from '../components/allergy_blood/TransfusionForm.vue';
import TransfusionReport from '../components/allergy_blood/TransfusionReport.vue';
import Approve from '../components/allergy_blood/TransfusionReport.vue';
import MainPage from '../components/mainPage/MainPage.vue';

const routes = [
  { path: '/', component: BloodChecklist, name: 'blood-checklist' },
  { path: '/transfusion-form', component: TransfusionForm, name: 'transfusion-form' },
  { path: '/transfusion-form/:id', component: TransfusionForm, name: 'transfusion-form' },
  // { path: 'get-transfusion-form/:id' },
  // { path: 'edit-transfusion-form/:id' },
  { path: '/transfusion-report/:id', component: TransfusionReport, name: 'transfusion-report' },
  // { path: 'get-transfusion-report/:id' },
  // { path: 'edit-transfusion-report/:id' },
  // { path: '/approve/:id', component: Approve, name: 'approve' },
  // { path: '/get-approve/:id' },
  { path: '/mainBloodChecklist', component: MainPage, name: 'MainPage' },


  // Add more routes as needed
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
