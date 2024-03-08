<script>
import { defineComponent } from "vue";
import BloodBankList from "./BloodBankList.vue";
import MedList from "./MedList.vue";
import axios from "axios";

export default defineComponent({
  name: "MainPage",
  data() {
    return {
      userInfo: [],
      baseURL: import.meta.env.VITE_BASE_URL,
      listBloodTranf: [],
      listReaction: [],
      userApprove: [],
    };
  },
  async mounted() {
    await this.fetchUser();
    await this.fetchListBloodTransf();
    await this.fetchListReaction();
    await this.fetchUserApprove();
  },
  methods: {
    async fetchUser() {
      try {
        const response = await axios.get(this.baseURL + "getUserLogin");
        console.log(" response.data", response.data)
        this.userInfo = response.data;
      } catch (error) {
        console.error("Error fetching List Blood Transfusion data:", error);
      }
    },
    async fetchListBloodTransf() {
      try {
        const response = await axios.get(this.baseURL + "getListBloodTransf");
        console.log(" response.data", response.data)
        this.listBloodTranf = response.data;
      } catch (error) {
        console.error("Error fetching List Blood Transfusion data:", error);
      }
    },
    async fetchListReaction() {
      try {
        const response = await axios.get(this.baseURL + "getListReaction");
        console.log(" response.data", response.data)
        this.listReaction = response.data;
      } catch (error) {
        console.error("Error fetching List Blood Transfusion data:", error);
      }
    },
    async fetchUserApprove() {
      try {
        const response = await axios.get(this.baseURL + "trasfusion-form/getUserApprove");
        this.userApprove = response.data;
      } catch (error) {
        console.error("Error fetching Signs and Symptoms data:", error);
      }
    },
  },
  components: {
    BloodBankList,
    MedList,
  },
});
</script>

<template>
  <div>
    <blood-bank-list v-if="userInfo.role === 'bloodbank'" :userInfo="userInfo" :tableData="listReaction" :userApprove="userApprove" />
    <med-list v-else :userInfo="userInfo" :tableData="listBloodTranf" :userApprove="userApprove"/>
    <!-- <template-not-found v-else /> -->
  </div>
</template>
  
  