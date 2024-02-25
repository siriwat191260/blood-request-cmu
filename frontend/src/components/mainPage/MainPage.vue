<script>
import { defineComponent } from "vue";
import BloodBankList from "./BloodBankList.vue"
import MedList from "./MedList.vue";
import axios from "axios";

export default defineComponent({
  name: "MainPage",
  data() {
    return {
      userInfo: {
        firstName: "พิจิตรา",
        lastName: "สุจิตพิริยกิจ",
        title: "นส",
        userRole: "med",
        roleName: "หมอ",
      },
      data: [
        { id: 0, date: "1/1/2024", time: "12:22", bloodBagNum: 1, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 0, reaction: 0, approve: 0, status: 0 },
        { id: 1, date: "1/1/2024", time: "12:22", bloodBagNum: 2, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 1, approve: 0, status: 1 },
        { id: 2, date: "1/1/2024", time: "12:22", bloodBagNum: 3, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 1, status: 1 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 4, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 5, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 6, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 7, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 8, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 9, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 10, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 11, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 12, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 13, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 14, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 15, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 16, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 17, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 18, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 19, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 20, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 21, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 22, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 23, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 24, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 25, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 26, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 27, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 28, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 29, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 30, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 31, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 32, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 33, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 34, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 35, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 36, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 37, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
        { id: 3, date: "1/1/2024", time: "12:22", bloodBagNum: 38, name: "บรรจง ยุทธจักร", HN: "2803589", transfusion: 2, reaction: 2, approve: 2, status: 2 },
      ],
      baseURL: "http://localhost:8000/",
      listBloodTranf: []
    };
  },
  async mounted(){
    await this.fetchListBloodTransf();
  },
  methods:{
        async fetchListBloodTransf() {
        try {
            const response = await axios.get(this.baseURL + "trasfusion-form/getBloodTransfWithTRForm");
            console.log(" response.data", response.data)
            this.listBloodTranf = response.data;
            } catch (error) {
                console.error("Error fetching List Blood Transfusion data:", error);
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
    <med-list v-if="userInfo.userRole === 'med'" :userInfo="userInfo" :tableData="listBloodTranf" />
    <blood-bank-list v-else-if="userInfo.userRole === 'bloodbank'" :userInfo="userInfo" :tableData="listBloodTranf" />
    <!-- <template-not-found v-else /> -->
  </div>
</template>
  
  