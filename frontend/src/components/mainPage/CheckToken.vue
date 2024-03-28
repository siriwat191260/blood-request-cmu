<script>
import { defineComponent } from "vue";
import axios from "axios";

export default defineComponent({
    name: "CheckToken",
    data() {
        return {
            userInfo: [],
            baseURL: import.meta.env.VITE_BASE_URL,
        };
    },
    async mounted() {
        await this.fetchUser();
    },
    methods: {
        async fetchUser() {
            try {

                const response = await axios.get(this.baseURL + "getCheckToken?Token=" + this.$route.params.id);
                if (response.data.s == true) {
                    localStorage.setItem('userProfile', JSON.stringify(response.data.v));
                    this.$router.push(`/mainBloodChecklist`);
                }else{
                    this.$router.push(`/NotFound`);
                }

            } catch (error) {
                console.error("Error fetching List Blood Transfusion data:", error);
            }
        },
    },
});
</script>

<template>

</template>
  
  