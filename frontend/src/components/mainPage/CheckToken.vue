<script>
import { defineComponent } from "vue";
import axios from "axios";

export default defineComponent({
    name: "MainPage",
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
                console.log(this.$route.params.id)
                const response = await axios.get(this.baseURL + "getCheckToken?Token=" + this.$route.params.id);
                console.log(" response.data", response.data)
                if (response.data.s == true) {
                    localStorage.setItem('userProfile', JSON.stringify(response.data.v));
                    this.$router.push(`/mainBloodChecklist`);
                }

            } catch (error) {
                console.error("Error fetching List Blood Transfusion data:", error);
            }
        },
    },
});
</script>

<template>
    <div>
        404
    </div>
</template>
  
  