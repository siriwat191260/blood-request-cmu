// router.js
import { createRouter, createWebHistory } from 'vue-router';

import TransfusionForm from '../components/allergy_blood/TransfusionForm.vue';
import TransfusionFormEdit from '../components/allergy_blood/TransfusionFormEdit.vue';
import TransfusionFormDisplay from '../components/allergy_blood/TransfusionFormDisplay.vue';
import TransfusionReport from '../components/allergy_blood/TransfusionReport.vue';
import TransfusionReportEdit from '../components/allergy_blood/TransfusionReportEdit.vue';
import TransfusionReportDisplay from '../components/allergy_blood/TransfusionReportDisplay.vue';
import Approve from '../components/allergy_blood/Approve.vue';
import ApproveDisplay from '../components/allergy_blood/ApproveDisplay.vue';
import MainPage from '../components/mainPage/MainPage.vue';
import CheckToken from '../components/mainPage/CheckToken.vue';

const routes = [
  { path: '/transfusion-form', component: TransfusionForm, name: 'transfusion-form' },
  { path: '/transfusion-form/:id', component: TransfusionForm, name: 'transfusion-form' },
  { path: '/get-transfusion-form/:id', component: TransfusionFormDisplay },
  { path: '/edit-transfusion-form/:id', component: TransfusionFormEdit },
  { path: '/transfusion-report/:id', component: TransfusionReport, name: 'transfusion-report' },
  { path: '/get-transfusion-report/:id', component: TransfusionReportDisplay },
  { path: '/edit-transfusion-report/:id', component: TransfusionReportEdit, },
  { path: '/approve/:id', component: Approve, name: 'approve' },
  { path: '/get-approve/:id', component: ApproveDisplay },
  { path: '/mainBloodChecklist', component: MainPage, name: 'MainPage' },
  { path: '/transfusionSystem/:id', component: CheckToken },

  // Add more routes as needed
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
