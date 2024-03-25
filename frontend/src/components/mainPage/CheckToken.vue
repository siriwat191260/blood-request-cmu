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
                //for test
                // const response = await axios.get(this.baseURL + "getUserLogin");
                // localStorage.setItem('userProfile', JSON.stringify(response.data));
                // this.$router.push(`/mainBloodChecklist`);

                //check token 
                const response = await axios.get(this.baseURL + "getCheckToken?Token=" + this.$route.params.id);
                //if the token is valid
                if (response.data.s == true) {
                    //set localStorage
                    localStorage.setItem('userProfile', JSON.stringify(response.data.v));
                    //change page
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
  
  