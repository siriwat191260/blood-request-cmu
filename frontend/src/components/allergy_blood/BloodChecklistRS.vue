<script>
import { defineComponent } from "vue";
import { ref, onBeforeUnmount } from "vue";
import DropDownSVGVue from "../general/DropDownSVG.vue";
import { Icon } from "@iconify/vue";
import { useCurrentTime } from "./useCurrentTime";
import axios from "axios";

export default defineComponent({
  name: "BloodChecklistRS",
  data() {
    return {
      formData: {
        // TR_Form Section
        firstName: "",
        lastName: "",
        HN: "",
        TXN: "",
        pt_type: "",
        createdDate: "" || new Date(),
        wardObject: {
          ward: "",
          wardcode: "",
        },
        phoneNumber: "",
        diagnosis: "",
        primaryPhysicianName: "",
        bloodGroup_Patient: "",
        Rh_Patient: "",
        blood_component: "",
        bloodGroup_Donor: "",
        Rh_Donor: "",
        bloodBagNumber: "",
        volume: "",
        medicationHistory: "",
        isReactionHistory: "",
        reactionCategory: "",
        //BloodTransfusionTest
        BloodTransfusionTest: {
          isCorrectPatientName: "",
          isWithin24hrsFever: "",
          isCorrectBloodComponent: "",
          isCorrectBloodTransfusionRec: "",
          isCorrectBloodBagNumber: "",
          isCorrectBloodGroupDonor: "",
          isCorrectBloodGroupPatient: "",
        },
        VitalSigns: {
          beforeReactionTime: "",
          beforeReactionTemp: "",
          beforeReactionBP: "",
          beforeReactionPulse: "",
          afterReactionTime: "",
          afterReactionTemp: "",
          afterReactionBP: "",
          afterReactionPulse: "",
        },
        SignsAndSymptomsObject: {
          SignsAndSymptomsSelected: [],
          Others: "",
        },
        SubmittingTest: {
          isBloodSample: 0,
          isBloodBagReaction: 0,
          nurseName: "",
          nurseDateTime: "",
          physicianName: "",
          physicianDateTime: "",
        },
        //Vital Signs
      },
      placeholderOption: { label: "กรุณาเลือกข้อมูล" },
      selectOptionsWard: [
        { ward: "ward 1", wardcode: "1" },
        { ward: "ward 2", wardcode: "2" },
        { ward: "ward 3", wardcode: "3" },
      ],
      selectOptionsPrimaryPhysicianName: [
        "นพ.สมศักดิ์",
        "พญ.สมศรี",
        "นพ.สมบูรณ์",
      ],
      selectOptionsBloodGroup: ["A", "B", "O", "AB"],
      selectOptionsRh: ["Rh+", "Rh-"],
      selectOptionsBloodComponent: [
        "Red Blood Cell",
        "Platelet ",
        "Plasma ",
        "Cryoprecipitate ",
      ],
      signsAndSymptomsOptions: [],
      reactionCategory: [],
      //config this for change baseURL path
      baseURL: "http://localhost:8000/",
    };
  },
  async mounted() {
    // Fetch Signs and Symptoms data on component mount
    await this.fetchSignsAndSymptoms();
    await this.fetchReactionCategory();
    await this.fetchListBloodTransf();
    /* watch(
      [() => this.signsAndSymptomsOptions, () => this.reactionCategory],
      ([newSigns, newReaction]) => {
        // This block will run whenever signsAndSymptomsOptions or reactionCategory change
        this.fetchData();
      }
    ); */
  },

  methods: {
    async fetchSignsAndSymptoms() {
      try {
        const response = await axios.get(
          this.baseURL + "getAllSignsAndSymptoms"
        );
        this.signsAndSymptomsOptions = response.data;
      } catch (error) {
        console.error("Error fetching Signs and Symptoms data:", error);
      }
    },
    async fetchReactionCategory() {
      try {
        const response = await axios.get(
          this.baseURL + "getAllReactionCategory"
        );
        /* console.log(response.data); */
        this.reactionCategory = response.data;
      } catch (error) {
        console.error("Error fetching Signs and Symptoms data:", error);
      }
    },
    async fetchListBloodTransf() {
      try {
        const response = await fetch("blood_allergy.postman_collection.json");
        const jsonData = await response.json();
        console.log("jsonData:", jsonData);
        const token = jsonData.item[0].request.auth.bearer[0].value;
        console.log("token: ", token);
        const endpoint = jsonData.item[0].request.url.raw;
        console.log("endpoint: ", endpoint);
        const body_blood = JSON.parse(
          JSON.stringify(jsonData.item[0].request.body.raw)
        );
        console.log("body_blood: ", body_blood);

        // Make the request to the API endpoint using the extracted token
        const apiResponse = await fetch(endpoint, {
          mode: "no-cors",
          method: "POST",
          headers: {
            "Access-Control-Allow-Origin": "*",
            "Access-Control-Allow-Methods": "GET, POST, PUT, DELETE",
            "Access-Control-Allow-Headers": "Content-Type",
            "Access-Control-Allow-Credentials": true,
            "Authorization": `Bearer ${token}`,
            "Content-Type": "application/json",
          },
          body: body_blood, // Assuming body_blood is already a JSON string
        });
        if (apiResponse.ok) {
          // Parse the response JSON
          const result = await apiResponse.json();
          console.log("result :", result);
          return result; // Return the result
        } else {
          // If the request failed, throw an error
          throw new Error("API request failed");
        }
      } catch (error) {
        console.log("error: ", error);
        throw error; // Re-throw the error for further handling
      }
    },
    currentDate() {
      const current = new Date();
      const date = current.toLocaleDateString("th-TH", {
        year: "numeric",
        month: "short",
        day: "numeric",
      });
      return date;
    },
    currentTime() {
      /* const currentTime = new Date();
      const time =
        currentTime.getHours() +
        ":" +
        currentTime.getMinutes() +
        ":" +
        currentTime.getSeconds();
      return time; */
      const { currentTime } = useCurrentTime();
      /* console.log(currentTime.value); */
      /* const time =
        currentTime.value.getHours() +
        ":" +
        currentTime.value.getMinutes() +
        ":" +
        currentTime.value.getSeconds();
      return time; */
    },
    handleSubmit() {
      // Handle form submission logic here
      // You can access form data and perform any validation or API calls
      // If there are errors, you can log them or take appropriate actions
      this.formData.SubmittingTest.isBloodSample =
        this.formData.SubmittingTest.isBloodSample === true ? 1 : 0;
      this.formData.SubmittingTest.isBloodBagReaction =
        this.formData.SubmittingTest.isBloodBagReaction === true ? 1 : 0;
      console.log("Form submitted! : ", this.formData);
      /* const cleaningForm = {
        currentTime :
      } */
    },
    handleIconAddClick() {
      console.log("Add");
    },
  },
  components: {
    DropDownSVGVue,
    Icon,
  },
});
</script>
<template>
  <!-- <div>
        <h1>HELLO WORLD</h1>
    </div> -->
  <div class="container-md">
    <form @submit.prevent="handleSubmit">
      <div class="card" style="border: 0px; justify-content: center">
        <!-- header -->
        <div class="row">
          <div class="col-md-6">
            <div style="margin-top: 60px">
              <p class="fontSize_header">
                ฟอร์มนำส่งตรวจการเกิดปฏิกิริยาจากการรับเลือด
              </p>
            </div>
          </div>
          <div class="col-md-3">
            <p class="fontTopicBox">HN</p>
            <div class="card card-box-style">
              <div class="card-body card-box-body-style">
                <!-- HN value -->
                <p class="fontInsideBox">
                  <i class="fa-regular fa-id-card" style="color: #00bfa5"></i>
                  &nbsp;&nbsp; 0 0 0 0 0 0
                </p>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div>
              <p class="fontTopicBox">ชื่อผู้ป่วย</p>
              <div class="card card-box-style">
                <div class="card-body card-box-body-style">
                  <p class="fontInsideBox">
                    <i class="fa-regular fa-id-card" style="color: #00bfa5"></i>
                    &nbsp;Name
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card card-box-info-style">
          <div class="row">
            <!-- row 1 -->
            <!-- วันที่ -->
            <div class="col-md-3 vertical-style-50w">
              <div class="card-box-info-row-component-style">
                <Icon
                  icon="bx:calendar-event"
                  style="
                    width: 14%;
                    height: 60%;
                    margin-left: 16px;
                    margin-top: 18px;
                  "
                />
                <div style="display: inline; position: absolute; width: 80%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    วันที่
                  </p>
                  <div style="position: relative">
                    <div
                      style="display: inline; position: absolute; width: 100%"
                    >
                      <input
                        class="form-control typing-box-style"
                        style="
                          padding-left: 16px;
                          padding-right: 16px;
                          padding-top: 0px;
                          padding-bottom: 0px;
                        "
                        type="text"
                        name=""
                        :value="currentDate()"
                        aria-label="readonly input example"
                        readonly
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- เวลา -->
            <div class="col-md-3 vertical-style-50w">
              <div class="card-box-info-row-component-style">
                <Icon
                  icon="bx:alarm"
                  style="
                    width: 14%;
                    height: 60%;
                    margin-left: 16px;
                    margin-top: 18px;
                  "
                />
                <div style="display: inline; position: absolute; width: 80%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    เวลา
                  </p>
                  <div style="position: relative">
                    <div
                      style="display: inline; position: absolute; width: 100%"
                    >
                      <input
                        class="form-control typing-box-style"
                        style="
                          padding-left: 16px;
                          padding-right: 16px;
                          padding-top: 0px;
                          padding-bottom: 0px;
                        "
                        type="text"
                        :value="currentTime()"
                        aria-label="readonly input example"
                        readonly
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- หอผู้ป่วย -->
            <div class="col-md-3 vertical-style-50w">
              <div class="card-box-info-row-component-style">
                <div style="display: inline; position: absolute; width: 100%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    หอผู้ป่วย
                  </p>
                  <div class="custom-select">
                    <select
                      class="form-select-sm select-box-style"
                      style="
                        padding-left: 16px;
                        padding-right: 16px;
                        padding-top: 0px;
                        padding-bottom: 0px;
                      "
                      aria-label="Small select example"
                      v-model="formData.wardObject"
                    >
                      <option
                        :value="{ ward: '', wardcode: '' }"
                        disabled
                        selected
                      >
                        {{ placeholderOption.label }}
                      </option>
                      <option
                        v-for="option in selectOptionsWard"
                        :key="option.wardcode"
                        :value="option"
                      >
                        {{ option.ward }}
                      </option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <!-- โทร -->
            <div class="col-md-3 vertical-style-50w">
              <div class="card-box-info-row-component-style">
                <div style="display: inline; position: absolute; width: 100%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    โทร
                  </p>
                  <div style="position: relative">
                    <div
                      style="display: inline; position: absolute; width: 100%"
                    >
                      <input
                        class="form-control typing-box-style"
                        style="
                          padding-left: 16px;
                          padding-right: 16px;
                          padding-top: 0px;
                          padding-bottom: 0px;
                        "
                        type="text"
                        value="123-456-759"
                        aria-label="readonly input example"
                        readonly
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- row 2 -->
            <!--การวินิจฉัย-->
            <div class="col-md-8 mt16 mb16 vertical-style-100w">
              <div class="card-box-info-row-component-style">
                <div style="display: inline; position: absolute; width: 100%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    การวินิจฉัย
                  </p>
                  <input
                    class="form-control typing-box-style"
                    style="
                      padding-left: 16px;
                      padding-right: 16px;
                      padding-top: 0px;
                      padding-bottom: 0px;
                    "
                    type="text"
                    v-model="formData.diagnosis"
                    aria-label="default input example"
                    placeholder="กรุณากรอกข้อมูล"
                  />
                </div>
              </div>
            </div>
            <!--แพทย์เจ้าของไข้-->
            <div class="col-md-4 mt16 mb16">
              <div class="card-box-info-row-component-style">
                <div style="display: inline; position: absolute; width: 100%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    แพทย์เจ้าของไข้
                  </p>

                  <div class="custom-select">
                    <select
                      class="form-select-sm select-box-style"
                      style="
                        padding-left: 16px;
                        padding-right: 16px;
                        padding-top: 0px;
                        padding-bottom: 0px;
                      "
                      aria-label="Small select example"
                      v-model="formData.primaryPhysicianName"
                    >
                      <option value="" disabled selected>
                        {{ placeholderOption.label }}
                      </option>
                      <option
                        v-for="option in selectOptionsPrimaryPhysicianName"
                        :key="option"
                        :value="option"
                      >
                        {{ option }}
                      </option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <!-- row 3 -->
            <!-- หมู่เลือดผู้ป่วย -->
            <div class="col-md-3 mt16 size-col-2point5 vertical-style-33w">
              <div class="card-box-info-row-component-style">
                <div style="display: inline; position: absolute; width: 100%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    หมู่เลือดผู้ป่วย
                  </p>

                  <div class="custom-select">
                    <select
                      class="form-select-sm select-box-style"
                      style="
                        margin-left: 0px;
                        margin-right: 0px;
                        padding-left: 16px;
                        padding-top: 0px;
                        padding-bottom: 0px;
                      "
                      aria-label="Small select example"
                      v-model="formData.bloodGroup_Patient"
                    >
                      <option value="" disabled selected>
                        {{ placeholderOption.label }}
                      </option>
                      <option
                        v-for="option in selectOptionsBloodGroup"
                        :key="option"
                        :value="option"
                      >
                        {{ option }}
                      </option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <!-- RH -->
            <div class="col-md-3 mt16 size-col-2point5 vertical-style-33w">
              <div class="card-box-info-row-component-style">
                <div style="display: inline; position: absolute; width: 100%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    RH
                  </p>

                  <div class="custom-select">
                    <select
                      class="form-select-sm select-box-style"
                      style="
                        padding-left: 16px;
                        padding-right: 16px;
                        padding-top: 0px;
                        padding-bottom: 0px;
                      "
                      aria-label="Small select example"
                      v-model="formData.Rh_Patient"
                    >
                      <option value="" disabled selected>
                        {{ placeholderOption.label }}
                      </option>
                      <option
                        v-for="option in selectOptionsRh"
                        :key="option"
                        :value="option"
                      >
                        {{ option }}
                      </option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <!-- ชนิดของเลือดที่ให้ -->
            <div class="col-md-3 mt16 size-col-2point5 vertical-style-33w">
              <div class="card-box-info-row-component-style">
                <div style="display: inline; position: absolute; width: 100%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    ชนิดของเลือดที่ให้
                  </p>

                  <div class="custom-select">
                    <select
                      class="form-select-sm select-box-style"
                      style="
                        padding-left: 16px;
                        padding-right: 16px;
                        padding-top: 0px;
                        padding-bottom: 0px;
                      "
                      aria-label="Small select example"
                      v-model="formData.blood_component"
                    >
                      <option value="" disabled selected>
                        {{ placeholderOption.label }}
                      </option>
                      <option
                        v-for="option in selectOptionsBloodComponent"
                        :key="option"
                        :value="option"
                      >
                        {{ option }}
                      </option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <!-- หมู่เลือดผู้บริจาค -->
            <div class="col-md-3 mt16 size-col-2point5 vertical-style-33w">
              <div class="card-box-info-row-component-style">
                <div style="display: inline; position: absolute; width: 100%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    หมู่เลือดผู้บริจาค
                  </p>

                  <div class="custom-select">
                    <select
                      class="form-select-sm select-box-style"
                      style="
                        padding-left: 16px;
                        padding-right: 16px;
                        padding-top: 0px;
                        padding-bottom: 0px;
                      "
                      aria-label="Small select example"
                      v-model="formData.bloodGroup_Donor"
                    >
                      <option value="" disabled selected>
                        {{ placeholderOption.label }}
                      </option>
                      <option
                        v-for="option in selectOptionsBloodGroup"
                        :key="option"
                        :value="option"
                      >
                        {{ option }}
                      </option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <!-- RH -->
            <div class="col-md-3 mt16 size-col-2point5 vertical-style-33w">
              <div class="card-box-info-row-component-style">
                <div style="display: inline; position: absolute; width: 100%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    RH
                  </p>

                  <div class="custom-select">
                    <select
                      class="form-select-sm select-box-style"
                      style="
                        padding-left: 16px;
                        padding-right: 16px;
                        padding-top: 0px;
                        padding-bottom: 0px;
                      "
                      aria-label="Small select example"
                      v-model="formData.Rh_Patient"
                    >
                      <option value="" disabled selected>
                        {{ placeholderOption.label }}
                      </option>
                      <option
                        v-for="option in selectOptionsRh"
                        :key="option"
                        :value="option"
                      >
                        {{ option }}
                      </option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <!--row 4 -->
            <!-- หมายเลขถุงเลือด -->
            <div class="col-md-3 mt16 vertical-style-50w">
              <div class="card-box-info-row-component-style">
                <div style="display: inline; position: absolute; width: 100%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    หมายเลขถุงเลือด
                  </p>
                  <input
                    class="form-control typing-box-style"
                    style="
                      padding-left: 16px;
                      padding-right: 16px;
                      padding-top: 0px;
                      padding-bottom: 0px;
                    "
                    type="number"
                    pattern="[0-9]*"
                    onkeypress="return event.charCode != 45"
                    min="0"
                    aria-label="default input example"
                    placeholder="กรุณากรอกข้อมูล"
                    v-model="formData.Rh_Patient"
                  />
                </div>
              </div>
            </div>
            <!-- ปริมาตรที่เติม -->
            <div class="col-md-3 mt16 vertical-style-50w">
              <div class="input-group card-box-info-row-component-style">
                <div style="display: inline; position: absolute; width: 100%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    ปริมาตรที่เติม
                  </p>
                  <div style="display: flex; height: 24px">
                    <input
                      class="form-control typing-box-style"
                      style="
                        padding-left: 16px;
                        padding-right: 16px;
                        padding-top: 0px;
                        padding-bottom: 0px;
                      "
                      type="number"
                      pattern="[0-9]*"
                      onkeypress="return event.charCode != 45"
                      min="0"
                      aria-label="default input example"
                      v-model="formData.volume"
                    />
                    <span
                      class="input-group-text"
                      style="
                        border: 0px;
                        width: 10%;
                        padding-left: 0px;
                        margin-right: 16px;
                        margin-bottom: 2px;
                        font-weight: 700;
                        font-size: 0.9rem;
                        color: #202124;
                        font-family: 'IBM Plex Sans Thai';
                      "
                    >
                      มล.</span
                    >
                  </div>
                </div>
              </div>
            </div>
            <!-- ยาที่ใช้ในปัจจุบัน -->
            <div class="col-md-6 mt16 vertical-style-100w">
              <div class="card-box-info-row-component-style">
                <div style="display: inline; position: absolute; width: 100%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    ยาที่ใช้ในปัจจุบัน
                  </p>
                  <input
                    class="form-control typing-box-style"
                    style="
                      padding-left: 16px;
                      padding-right: 16px;
                      padding-top: 0px;
                      padding-bottom: 0px;
                    "
                    type="text"
                    aria-label="default input example"
                    placeholder="กรุณากรอกข้อมูล"
                    v-model="formData.medicationHistory"
                  />
                </div>
              </div>
            </div>
            <!--row 5 -->
            <!-- ประวัติการเกิดปฏิกิริยาการรับเลือด มีหรือไม่ -->
            <div
              class="col-md-3 size-col-4point5 mt16 size-col-43w vertical-style-100w"
            >
              <div style="display: flex">
                <p
                  class="fontTopicInfo"
                  style="margin-top: 26.5px; display: block"
                >
                  ประวัติการเกิดปฏิกิริยาจากการรับเลือด
                </p>
                <div
                  style="display: block; margin-left: 32px; margin-top: 18px"
                >
                  <div class="form-check form-check-inline">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="history_reaction_transfusion"
                      id="inlineRadio1"
                      value="0"
                      v-model="formData.isReactionHistory"
                    />
                    <label
                      class="form-check-label"
                      for="inlineRadio1"
                      style="margin-top: 2px"
                      >ไม่มี</label
                    >
                  </div>
                  <div class="form-check form-check-inline">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="history_reaction_transfusion"
                      id="inlineRadio2"
                      value="1"
                      v-model="formData.isReactionHistory"
                    />
                    <label
                      class="form-check-label"
                      for="inlineRadio2"
                      style="margin-top: 2px"
                      >มี</label
                    >
                  </div>
                </div>
              </div>
            </div>
            <!-- ชนิดของปฏิกิริยา -->
            <div
              class="col-md-7 size-col-7point5 mt16 size-col-57w vertical-style-100w"
            >
              <div class="card-box-info-row-component-style">
                <div style="display: inline; position: absolute; width: 100%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    ชนิดของปฏิกิริยา
                  </p>
                  <input
                    class="form-control typing-box-style"
                    style="
                      padding-left: 16px;
                      padding-right: 16px;
                      padding-top: 0px;
                      padding-bottom: 0px;
                    "
                    type="text"
                    aria-label="default input example"
                    placeholder="กรุณากรอกข้อมูล"
                    v-model="formData.reactionCategory"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
        <hr class="dashed" />
        <!-- BloodTransfusionTest-->
        <div class="card" style="border: 0px">
          <div class="row">
            <!-- การตรวจสอบการให้เลือดของหอผู้ป่วย -->
            <div class="col-md-12">
              <p class="fontTopicBox" style="margin-top: 8px">
                การตรวจสอบการให้เลือดของหอผู้ป่วย
              </p>
            </div>
            <div class="col-md-12 flexAndSpaceBetween">
              <!-- ชื่อ-นามสกุล ผู้ป่วย-->
              <div class="col-md-5 size-col-50w vertical-style-100w">
                <div
                  style="
                    display: flex;
                    justify-content: space-between;
                    width: 100%;
                  "
                >
                  <p
                    class="fontTopicCheckBox"
                    style="width: 50%; margin-top: 26.5px; display: block"
                  >
                    ชื่อ - นามสกุล ผู้ป่วย
                  </p>
                  <div
                    style="
                      display: block;
                      width: 50%;
                      margin-left: 32px;
                      margin-top: 22px;
                    "
                  >
                    <div
                      class="form-check form-check-inline"
                      style="width: 40%"
                    >
                      <input
                        class="form-check-input"
                        type="radio"
                        name="isCorrectPatientName"
                        id="inlineRadio1"
                        value="1"
                        v-model="
                          formData.BloodTransfusionTest.isCorrectPatientName
                        "
                      />
                      <label
                        class="form-check-label"
                        for="inlineRadio1"
                        style="margin-top: 2px"
                        >ถูกต้อง</label
                      >
                    </div>
                    <div
                      class="form-check form-check-inline"
                      style="width: 45%"
                    >
                      <input
                        class="form-check-input"
                        type="radio"
                        name="isCorrectPatientName"
                        id="inlineRadio2"
                        value="0"
                        v-model="
                          formData.BloodTransfusionTest.isCorrectPatientName
                        "
                      />
                      <label
                        class="form-check-label"
                        for="inlineRadio2"
                        style="margin-top: 2px"
                        >ไม่ถูกต้อง</label
                      >
                    </div>
                  </div>
                </div>
              </div>
              <!-- ภายใน 24 ชั่วโมง ผู้ป่วย -->
              <div class="col-md-5 size-col-50w vertical-style-100w">
                <div
                  style="
                    display: flex;
                    justify-content: space-between;
                    width: 100%;
                  "
                >
                  <p
                    class="fontTopicCheckBox pdl16Horizontal"
                    style="width: 50%; margin-top: 26.5px; display: block"
                  >
                    ภายใน 24 ชั่วโมง ผู้ป่วย
                  </p>
                  <div
                    style="
                      display: block;
                      width: 50%;
                      margin-left: 32px;
                      margin-top: 22px;
                    "
                  >
                    <div
                      class="form-check form-check-inline"
                      style="width: 40%"
                    >
                      <input
                        class="form-check-input"
                        type="radio"
                        name="isWithin24hrsFever"
                        id="inlineRadio1"
                        value="1"
                        v-model="
                          formData.BloodTransfusionTest.isWithin24hrsFever
                        "
                      />
                      <label
                        class="form-check-label"
                        for="inlineRadio1"
                        style="margin-top: 2px"
                        >มีไข้</label
                      >
                    </div>
                    <div
                      class="form-check form-check-inline"
                      style="width: 45%"
                    >
                      <input
                        class="form-check-input"
                        type="radio"
                        name="isWithin24hrsFever"
                        id="inlineRadio2"
                        value="0"
                        v-model="
                          formData.BloodTransfusionTest.isWithin24hrsFever
                        "
                      />
                      <label
                        class="form-check-label"
                        for="inlineRadio2"
                        style="margin-top: 2px"
                        >ไม่มีไข้</label
                      >
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12 flexAndSpaceBetween">
              <!-- ชนิดของเลือดที่ให้-->
              <div class="col-md-5 size-col-50w vertical-style-100w">
                <div
                  style="
                    display: flex;
                    justify-content: space-between;
                    width: 100%;
                  "
                >
                  <p
                    class="fontTopicCheckBox"
                    style="width: 50%; margin-top: 26.5px; display: block"
                  >
                    ชนิดของเลือดที่ให้
                  </p>
                  <div
                    style="
                      display: block;
                      width: 50%;
                      margin-left: 32px;
                      margin-top: 22px;
                    "
                  >
                    <div
                      class="form-check form-check-inline"
                      style="width: 40%"
                    >
                      <input
                        class="form-check-input"
                        type="radio"
                        name="isCorrectBloodComponent"
                        id="inlineRadio1"
                        value="1"
                        v-model="
                          formData.BloodTransfusionTest.isCorrectBloodComponent
                        "
                      />
                      <label
                        class="form-check-label"
                        for="inlineRadio1"
                        style="margin-top: 2px"
                        >ถูกต้อง</label
                      >
                    </div>
                    <div
                      class="form-check form-check-inline"
                      style="width: 45%"
                    >
                      <input
                        class="form-check-input"
                        type="radio"
                        name="isCorrectBloodComponent"
                        id="inlineRadio2"
                        value="0"
                        v-model="
                          formData.BloodTransfusionTest.isCorrectBloodComponent
                        "
                      />
                      <label
                        class="form-check-label"
                        for="inlineRadio2"
                        style="margin-top: 2px"
                        >ไม่ถูกต้อง</label
                      >
                    </div>
                  </div>
                </div>
              </div>
              <!-- บันทึกการให้เลือด -->
              <div class="col-md-5 size-col-50w vertical-style-100w">
                <div
                  style="
                    display: flex;
                    justify-content: space-between;
                    width: 100%;
                  "
                >
                  <p
                    class="fontTopicCheckBox pdl16Horizontal"
                    style="width: 50%; margin-top: 26.5px; display: block"
                  >
                    บันทึกการให้เลือด
                  </p>
                  <div
                    style="
                      display: block;
                      width: 50%;
                      margin-left: 32px;
                      margin-top: 22px;
                    "
                  >
                    <div
                      class="form-check form-check-inline"
                      style="width: 40%"
                    >
                      <input
                        class="form-check-input"
                        type="radio"
                        name="isCorrectBloodTransfusionRec"
                        id="inlineRadio1"
                        value="1"
                        v-model="
                          formData.BloodTransfusionTest
                            .isCorrectBloodTransfusionRec
                        "
                      />
                      <label
                        class="form-check-label"
                        for="inlineRadio1"
                        style="margin-top: 2px"
                        >ถูกต้อง</label
                      >
                    </div>
                    <div
                      class="form-check form-check-inline"
                      style="width: 45%"
                    >
                      <input
                        class="form-check-input"
                        type="radio"
                        name="isCorrectBloodTransfusionRec"
                        id="inlineRadio2"
                        value="0"
                        v-model="
                          formData.BloodTransfusionTest
                            .isCorrectBloodTransfusionRec
                        "
                      />
                      <label
                        class="form-check-label"
                        for="inlineRadio2"
                        style="margin-top: 2px"
                        >ไม่ถูกต้อง</label
                      >
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12 flexAndSpaceBetween">
              <!-- หมายเลขถุงเลือด -->
              <div class="col-md-5 size-col-50w vertical-style-100w">
                <div
                  style="
                    display: flex;
                    justify-content: space-between;
                    width: 100%;
                  "
                >
                  <p
                    class="fontTopicCheckBox"
                    style="width: 50%; margin-top: 26.5px; display: block"
                  >
                    หมายเลขถุงเลือด
                  </p>
                  <div
                    style="
                      display: block;
                      width: 50%;
                      margin-left: 32px;
                      margin-top: 22px;
                    "
                  >
                    <div
                      class="form-check form-check-inline"
                      style="width: 40%"
                    >
                      <input
                        class="form-check-input"
                        type="radio"
                        name="isCorrectBloodBagNumber"
                        id="inlineRadio1"
                        value="1"
                        v-model="
                          formData.BloodTransfusionTest.isCorrectBloodBagNumber
                        "
                      />
                      <label
                        class="form-check-label"
                        for="inlineRadio1"
                        style="margin-top: 2px"
                        >ถูกต้อง</label
                      >
                    </div>
                    <div
                      class="form-check form-check-inline"
                      style="width: 45%"
                    >
                      <input
                        class="form-check-input"
                        type="radio"
                        name="isCorrectBloodBagNumber"
                        id="inlineRadio2"
                        value="0"
                        v-model="
                          formData.BloodTransfusionTest.isCorrectBloodBagNumber
                        "
                      />
                      <label
                        class="form-check-label"
                        for="inlineRadio2"
                        style="margin-top: 2px"
                        >ไม่ถูกต้อง</label
                      >
                    </div>
                  </div>
                </div>
              </div>
              <!-- หมู่เลือดผู้บริจาค -->
              <div class="col-md-5 size-col-50w vertical-style-100w">
                <div
                  style="
                    display: flex;
                    justify-content: space-between;
                    width: 100%;
                  "
                >
                  <p
                    class="fontTopicCheckBox pdl16Horizontal"
                    style="width: 50%; margin-top: 26.5px; display: block"
                  >
                    หมู่เลือดผู้บริจาค
                  </p>
                  <div
                    style="
                      display: block;
                      width: 50%;
                      margin-left: 32px;
                      margin-top: 22px;
                    "
                  >
                    <div
                      class="form-check form-check-inline"
                      style="width: 40%"
                    >
                      <input
                        class="form-check-input"
                        type="radio"
                        name="isCorrectBloodGroupDonor"
                        id="inlineRadio1"
                        value="1"
                        v-model="
                          formData.BloodTransfusionTest.isCorrectBloodGroupDonor
                        "
                      />
                      <label
                        class="form-check-label"
                        for="inlineRadio1"
                        style="margin-top: 2px"
                        >ถูกต้อง</label
                      >
                    </div>
                    <div
                      class="form-check form-check-inline"
                      style="width: 45%"
                    >
                      <input
                        class="form-check-input"
                        type="radio"
                        name="isCorrectBloodGroupDonor"
                        id="inlineRadio2"
                        value="0"
                        v-model="
                          formData.BloodTransfusionTest.isCorrectBloodGroupDonor
                        "
                      />
                      <label
                        class="form-check-label"
                        for="inlineRadio2"
                        style="margin-top: 2px"
                        >ไม่ถูกต้อง</label
                      >
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12 flexAndSpaceBetween">
              <!-- หมู่เลือดผู้ป่วย -->
              <div class="col-md-5 size-col-50w vertical-style-100w">
                <div
                  style="
                    display: flex;
                    justify-content: space-between;
                    width: 100%;
                  "
                >
                  <p
                    class="fontTopicCheckBox"
                    style="width: 50%; margin-top: 26.5px; display: block"
                  >
                    หมู่เลือดผู้ป่วย
                  </p>
                  <div
                    style="
                      display: block;
                      width: 50%;
                      margin-left: 32px;
                      margin-top: 22px;
                    "
                  >
                    <div
                      class="form-check form-check-inline"
                      style="width: 40%"
                    >
                      <input
                        class="form-check-input"
                        type="radio"
                        name="isCorrectBloodGroupPatient"
                        id="inlineRadio1"
                        value="1"
                        v-model="
                          formData.BloodTransfusionTest
                            .isCorrectBloodGroupPatient
                        "
                      />
                      <label
                        class="form-check-label"
                        for="inlineRadio1"
                        style="margin-top: 2px"
                        >ถูกต้อง</label
                      >
                    </div>
                    <div
                      class="form-check form-check-inline"
                      style="width: 45%"
                    >
                      <input
                        class="form-check-input"
                        type="radio"
                        name="isCorrectBloodGroupPatient"
                        id="inlineRadio2"
                        value="0"
                        v-model="
                          formData.BloodTransfusionTest
                            .isCorrectBloodGroupPatient
                        "
                      />
                      <label
                        class="form-check-label"
                        for="inlineRadio2"
                        style="margin-top: 2px"
                        >ไม่ถูกต้อง</label
                      >
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Vital signs -->
        <div class="card mt16" style="border: 0px">
          <div class="row">
            <div class="col-md-12">
              <p class="fontTopicBox" style="margin-top: 8px">Vital signs</p>
            </div>
            <!-- ก่อนเกิดปฏิกิริยา -->
            <div class="col-md-2 mt16 size-col-2point5">
              <p
                class="fontTopicInfo"
                style="margin-top: 26.5px; display: block"
              >
                ก่อนเกิดปฏิกิริยา
              </p>
            </div>
            <!-- ก่อนเกิดปฏิกิริยา เวลา -->
            <div class="col-md-3 mt16 size-col-2point5">
              <div class="card-box-info-row-component-style">
                <Icon
                  icon="bx:alarm"
                  style="
                    width: 14%;
                    height: 60%;
                    margin-left: 16px;
                    margin-top: 18px;
                  "
                />
                <div style="display: inline; position: absolute; width: 80%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    เวลา
                  </p>
                  <div style="position: relative">
                    <div
                      style="display: inline; position: absolute; width: 100%"
                    >
                      <input
                        class="form-control typing-box-style"
                        style="
                          padding-left: 16px;
                          padding-right: 16px;
                          padding-top: 0px;
                          padding-bottom: 0px;
                        "
                        type="text"
                        aria-label="readonly input example"
                        placeholder="กรุณากรอกข้อมูล"
                        v-model="formData.VitalSigns.beforeReactionTime"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- ก่อนเกิดปฏิกิริยา อุณหภูมิ -->
            <div class="col-md-3 mt16 size-col-2point5">
              <div class="input-group card-box-info-row-component-style">
                <div style="display: inline; position: absolute; width: 100%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    อุณหภูมิ
                  </p>
                  <div style="display: flex; height: 24px">
                    <input
                      class="form-control typing-box-style"
                      style="
                        padding-left: 16px;
                        padding-right: 16px;
                        padding-top: 0px;
                        padding-bottom: 0px;
                      "
                      type="number"
                      pattern="[0-9]*"
                      onkeypress="return event.charCode != 45"
                      min="0"
                      aria-label="default input example"
                      placeholder="กรุณากรอกข้อมูล"
                      v-model="formData.VitalSigns.beforeReactionTemp"
                    />
                    <span
                      class="input-group-text"
                      style="
                        border: 0px;
                        width: 10%;
                        padding-left: 0px;
                        margin-right: 16px;
                        margin-bottom: 2px;
                        font-weight: 700;
                        font-size: 0.9rem;
                        color: #202124;
                        font-family: 'IBM Plex Sans Thai';
                      "
                    >
                      °C</span
                    >
                  </div>
                </div>
              </div>
            </div>
            <!-- ก่อนเกิดปฏิกิริยา BP-->
            <div class="col-md-3 mt16 size-col-2point5">
              <div class="card-box-info-row-component-style">
                <div style="display: inline; position: absolute; width: 100%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    BP
                  </p>
                  <input
                    class="form-control typing-box-style"
                    style="
                      padding-left: 16px;
                      padding-right: 16px;
                      padding-top: 0px;
                      padding-bottom: 0px;
                    "
                    type="text"
                    aria-label="default input example"
                    placeholder="กรุณากรอกข้อมูล"
                    v-model="formData.VitalSigns.beforeReactionBP"
                  />
                </div>
              </div>
            </div>
            <!-- ก่อนเกิดปฏิกิริยา Pulse-->
            <div class="col-md-3 mt16 size-col-2point5">
              <div class="card-box-info-row-component-style">
                <div style="display: inline; position: absolute; width: 100%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    Pulse
                  </p>
                  <input
                    class="form-control typing-box-style"
                    style="
                      padding-left: 16px;
                      padding-right: 16px;
                      padding-top: 0px;
                      padding-bottom: 0px;
                    "
                    type="text"
                    aria-label="default input example"
                    placeholder="กรุณากรอกข้อมูล"
                    v-model="formData.VitalSigns.beforeReactionPulse"
                  />
                </div>
              </div>
            </div>
            <!-- หลังเกิดปฏิกิริยา -->
            <div class="col-md-2 mt16 size-col-2point5">
              <p
                class="fontTopicInfo"
                style="margin-top: 26.5px; display: block"
              >
                หลังเกิดปฏิกิริยา
              </p>
            </div>
            <!-- หลังเกิดปฏิกิริยา เวลา -->
            <div class="col-md-3 mt16 size-col-2point5">
              <div class="card-box-info-row-component-style">
                <Icon
                  icon="bx:alarm"
                  style="
                    width: 14%;
                    height: 60%;
                    margin-left: 16px;
                    margin-top: 18px;
                  "
                />
                <div style="display: inline; position: absolute; width: 80%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    เวลา
                  </p>
                  <div style="position: relative">
                    <div
                      style="display: inline; position: absolute; width: 100%"
                    >
                      <input
                        class="form-control typing-box-style"
                        style="
                          padding-left: 16px;
                          padding-right: 16px;
                          padding-top: 0px;
                          padding-bottom: 0px;
                        "
                        type="text"
                        aria-label="readonly input example"
                        placeholder="กรุณากรอกข้อมูล"
                        v-model="formData.VitalSigns.afterReactionTime"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- หลังเกิดปฏิกิริยา อุณหภูมิ -->
            <div class="col-md-3 mt16 size-col-2point5">
              <div class="input-group card-box-info-row-component-style">
                <div style="display: inline; position: absolute; width: 100%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    อุณหภูมิ
                  </p>
                  <div style="display: flex; height: 24px">
                    <input
                      class="form-control typing-box-style"
                      style="
                        padding-left: 16px;
                        padding-right: 16px;
                        padding-top: 0px;
                        padding-bottom: 0px;
                      "
                      type="number"
                      pattern="[0-9]*"
                      onkeypress="return event.charCode != 45"
                      min="0"
                      aria-label="default input example"
                      placeholder="กรุณากรอกข้อมูล"
                      v-model="formData.VitalSigns.afterReactionTemp"
                    />
                    <span
                      class="input-group-text"
                      style="
                        border: 0px;
                        width: 10%;
                        padding-left: 0px;
                        margin-right: 16px;
                        margin-bottom: 2px;
                        font-weight: 700;
                        font-size: 0.9rem;
                        color: #202124;
                        font-family: 'IBM Plex Sans Thai';
                      "
                    >
                      °C</span
                    >
                  </div>
                </div>
              </div>
            </div>
            <!-- หลังเกิดปฏิกิริยา BP-->
            <div class="col-md-3 mt16 size-col-2point5">
              <div class="card-box-info-row-component-style">
                <div style="display: inline; position: absolute; width: 100%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    BP
                  </p>
                  <input
                    class="form-control typing-box-style"
                    style="
                      padding-left: 16px;
                      padding-right: 16px;
                      padding-top: 0px;
                      padding-bottom: 0px;
                    "
                    type="text"
                    aria-label="default input example"
                    placeholder="กรุณากรอกข้อมูล"
                    v-model="formData.VitalSigns.afterReactionBP"
                  />
                </div>
              </div>
            </div>
            <!-- หลังเกิดปฏิกิริยา Pulse-->
            <div class="col-md-3 mt16 size-col-2point5">
              <div class="card-box-info-row-component-style">
                <div style="display: inline; position: absolute; width: 100%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    Pulse
                  </p>
                  <input
                    class="form-control typing-box-style"
                    style="
                      padding-left: 16px;
                      padding-right: 16px;
                      padding-top: 0px;
                      padding-bottom: 0px;
                    "
                    type="text"
                    aria-label="default input example"
                    placeholder="กรุณากรอกข้อมูล"
                    v-model="formData.VitalSigns.afterReactionPulse"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- {{ console.log('signsAndSymptomsOptions :',signsAndSymptomsOptions) }} -->
        <!-- Signs and Symptoms -->
        <div class="card mt16" style="border: 0px">
          <div class="col-md-12">
            <p class="fontTopicBox" style="margin-top: 8px">
              Signs and Symptoms
            </p>
          </div>
          <!-- Signs and Symptoms Name-->
          <div class="row">
            <div
              class="col-md-4"
              v-for="(SignsAndSymptoms, index) in signsAndSymptomsOptions"
              :style="{
                width:
                  index === signsAndSymptomsOptions.length - 1
                    ? '66.66%'
                    : '33.3333%',
                display: 'flex', // To prevent flex-grow and flex-shrink
              }"
              :key="index"
            >
              <div style="display: block; margin-top: 18px">
                <div class="form-check form-check-inline">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    id="inlineCheckbox1"
                    :value="SignsAndSymptoms.idSignsAndSymtomsName"
                    v-model="
                      formData.SignsAndSymptomsObject.SignsAndSymptomsSelected
                    "
                  />
                  <label
                    style="margin-top: 2px"
                    class="form-check-label"
                    for="inlineCheckbox1"
                    >{{ SignsAndSymptoms.name }}</label
                  >
                </div>
              </div>
              <div
                v-if="index === signsAndSymptomsOptions.length - 1"
                style="width: 100%"
              >
                <div
                  class="card-box-info-row-component-style"
                  style="width: 100%"
                >
                  <div style="display: inline; position: absolute; width: 100%">
                    <input
                      class="form-control typing-box-style"
                      style="
                        padding-left: 16px;
                        padding-right: 16px;
                        padding-top: 0px;
                        padding-bottom: 0px;
                        margin-top: 24px;
                      "
                      type="text"
                      aria-label="default input example"
                      placeholder="กรุณากรอกข้อมูล"
                      v-model="formData.SignsAndSymptomsObject.Others"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Reaction Category-->
          <div class="row">
            <div class="col-md-12 mt16">
              <p class="fontTopicInfo" style="display: inline">
                Reaction Category
              </p>
              <p
                class="fontTopicInfo"
                style="display: inline"
                v-for="(reactionCategory, index) in reactionCategory"
                :key="index"
              >
                {{
                  ":" +
                  "&nbsp;&nbsp;&nbsp;" +
                  reactionCategory.nameReactionCategory +
                  "[" +
                  reactionCategory.idReactionCategory +
                  "]" +
                  "&nbsp;&nbsp;&nbsp;"
                }}
              </p>
            </div>
          </div>
        </div>
        <!-- บันทึกรายละเอียดในกรณีที่มีการเติมเลือดมาก่อน ภายใน 24 ชั่วโมง -->
        <div class="card mt16" style="border: 0px">
          <div class="row">
            <div class="col-md-12">
              <p class="fontTopicBox" style="margin-top: 8px">
                บันทึกรายละเอียดในกรณีที่มีการเติมเลือดมาก่อน ภายใน 24 ชั่วโมง<!-- <Icon
                  @click="handleIconAddClick"
                  icon="material-symbols:add-circle-outline"
                  width="24"
                  height="24"
                  style="margin-left: 16px; margin-bottom: 3px; color: #3c3c3c"
                /> -->
              </p>
            </div>
            <div class="accordion mt16" id="accordionPanelsStayOpenExample">
              <div class="accordion-item">
                <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                  <button
                    class="accordion-button"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#panelsStayOpen-collapseOne"
                    aria-expanded="true"
                    aria-controls="panelsStayOpen-collapseOne"
                  >
                    <div
                      style="
                        display: inline;
                        position: absolute;
                        width: 100%;
                        margin-top: 8px;
                      "
                    >
                      <p class="fontTopicInfo" style="">
                        <Icon
                          icon="healthicons:blood-bag"
                          width="24"
                          height="24"
                        />
                        หมายเลขถุงเลือด : 11234567
                      </p>
                    </div>
                  </button>
                </h2>
                <div
                  id="panelsStayOpen-collapseOne"
                  class="accordion-collapse collapse show"
                  aria-labelledby="panelsStayOpen-headingOne"
                >
                  <div class="accordion-body">
                    <div class="row">
                      <!-- ชนิดของเลือดที่ให้ -->
                      <div class="col-md-2 vertical-style-33w">
                        <div class="card-box-info-row-component-style">
                          <div
                            style="
                              display: inline;
                              position: absolute;
                              width: 100%;
                            "
                          >
                            <p
                              class="fontTopicInfo"
                              style="margin-left: 16px; margin-top: 7px"
                            >
                              ชนิดเลือด
                            </p>

                            <div class="custom-select">
                              <select
                                class="form-select-sm select-box-style"
                                style="
                                  padding-left: 16px;
                                  padding-right: 16px;
                                  padding-top: 0px;
                                  padding-bottom: 0px;
                                "
                                aria-label="Small select example"
                                v-model="formData.blood_component"
                              >
                                <option value="" disabled selected>
                                  {{ placeholderOption.label }}
                                </option>
                                <option
                                  v-for="option in selectOptionsBloodComponent"
                                  :key="option"
                                  :value="option"
                                >
                                  {{ option }}
                                </option>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- วันที่ -->
                      <div class="col-md-2 vertical-style-50w">
                        <div class="card-box-info-row-component-style">
                          <Icon
                            icon="bx:calendar-event"
                            style="
                              width: 20%;
                              height: 60%;
                              margin-left: 16px;
                              margin-top: 18px;
                            "
                          />
                          <div
                            style="
                              display: inline;
                              position: absolute;
                              width: 70%;
                            "
                          >
                            <p
                              class="fontTopicInfo"
                              style="margin-left: 16px; margin-top: 7px"
                            >
                              วันที่
                            </p>
                            <div style="position: relative">
                              <div
                                style="
                                  display: inline;
                                  position: absolute;
                                  width: 100%;
                                "
                              >
                                <input
                                  class="form-control typing-box-style"
                                  style="
                                    padding-left: 16px;
                                    padding-right: 16px;
                                    padding-top: 0px;
                                    padding-bottom: 0px;
                                  "
                                  type="text"
                                  name=""
                                  :value="currentDate()"
                                  aria-label="readonly input example"
                                  readonly
                                />
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- เริ่ม -->
                      <div class="col-md-2 size-col-1point5 vertical-style-50w">
                        <div class="card-box-info-row-component-style">
                          <Icon
                            icon="bx:alarm"
                            style="
                              width: 32px;
                              height: 60%;
                              margin-left: 16px;
                              margin-top: 18px;
                            "
                          />
                          <div
                            style="
                              display: inline;
                              position: absolute;
                              width: 80%;
                            "
                          >
                            <p
                              class="fontTopicInfo"
                              style="margin-left: 16px; margin-top: 7px"
                            >
                              เริ่ม
                            </p>
                            <div style="position: relative">
                              <div
                                style="
                                  display: inline;
                                  position: absolute;
                                  width: 100%;
                                "
                              >
                                <input
                                  class="form-control typing-box-style"
                                  style="
                                    padding-left: 16px;
                                    padding-right: 16px;
                                    padding-top: 0px;
                                    padding-bottom: 0px;
                                  "
                                  type="text"
                                  value="15:30"
                                  aria-label="default input example"
                                />
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- เสร็จสิ้น -->
                      <div class="col-md-2 size-col-1point5 vertical-style-50w">
                        <div class="card-box-info-row-component-style">
                          <Icon
                            icon="bx:alarm"
                            style="
                              width: 32px;
                              height: 60%;
                              margin-left: 16px;
                              margin-top: 18px;
                            "
                          />
                          <div
                            style="
                              display: inline;
                              position: absolute;
                              width: 80%;
                            "
                          >
                            <p
                              class="fontTopicInfo"
                              style="margin-left: 16px; margin-top: 7px"
                            >
                              เสร็จสิ้น
                            </p>
                            <div style="position: relative">
                              <div
                                style="
                                  display: inline;
                                  position: absolute;
                                  width: 100%;
                                "
                              >
                                <input
                                  class="form-control typing-box-style"
                                  style="
                                    padding-left: 16px;
                                    padding-right: 16px;
                                    padding-top: 0px;
                                    padding-bottom: 0px;
                                  "
                                  type="text"
                                  value="16:30"
                                  aria-label="default input example"
                                />
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- ปริมาตรที่ให้ -->
                      <div
                        class="col-md-2 size-col-1point75 vertical-style-50w"
                      >
                        <div class="card-box-info-row-component-style">
                          <div
                            style="
                              display: inline;
                              position: absolute;
                              width: 80%;
                            "
                          >
                            <p
                              class="fontTopicInfo"
                              style="margin-left: 16px; margin-top: 7px"
                            >
                              ปริมาตรที่ให้
                            </p>
                            <div style="display: flex; height: 24px">
                              <input
                                class="form-control typing-box-style"
                                style="
                                  padding-left: 16px;
                                  padding-right: 16px;
                                  padding-top: 0px;
                                  padding-bottom: 0px;
                                "
                                type="number"
                                pattern="[0-9]*"
                                onkeypress="return event.charCode != 45"
                                min="0"
                                aria-label="default input example"
                                v-model="formData.volume"
                              />
                              <span
                                class="input-group-text"
                                style="
                                  border: 0px;
                                  width: 10%;
                                  padding-left: 0px;
                                  /* margin-right: 16px; */
                                  margin-bottom: 2px;
                                  font-weight: 700;
                                  font-size: 0.9rem;
                                  color: #202124;
                                  font-family: 'IBM Plex Sans Thai';
                                "
                              >
                                มล.</span
                              >
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- เกิดปฏิกิริยา -->
                      <div
                        class="col-md-2 size-col-2point5 size-col-43w vertical-style-100w"
                      >
                        <div style="display: flex">
                          <p
                            class="fontTopicInfo"
                            style="margin-top: 26.5px; display: block"
                          >
                            เกิดปฏิกิริยา
                          </p>
                          <div
                            style="
                              display: block;
                              margin-left: 8px;
                              margin-top: 18px;
                            "
                          >
                            <div class="form-check form-check-inline">
                              <input
                                class="form-check-input"
                                type="radio"
                                name="history_reaction_transfusion"
                                id="inlineRadio1"
                                value="0"
                                v-model="formData.isReactionHistory"
                              />
                              <label
                                class="form-check-label"
                                for="inlineRadio1"
                                style="margin-top: 2px"
                                >ไม่มี</label
                              >
                            </div>
                            <div class="form-check form-check-inline">
                              <input
                                class="form-check-input"
                                type="radio"
                                name="history_reaction_transfusion"
                                id="inlineRadio2"
                                value="1"
                                v-model="formData.isReactionHistory"
                              />
                              <label
                                class="form-check-label"
                                for="inlineRadio2"
                                style="margin-top: 2px"
                                >มี</label
                              >
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card mt16" style="border: 0px">
          <div class="row">
            <div class="col-md-12">
              <p class="fontTopicBox" style="margin-top: 8px">การส่งตรวจ</p>
            </div>
            <!--ส่งตัวอย่างเลือดผู้ป่วยหลังจากเกิดปฏิกิริยา-->
            <div class="col-md-12">
              <div style="display: block; margin-top: 18px">
                <div class="form-check form-check-inline">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    id="inlineCheckbox1"
                    :value="formData.SubmittingTest.isBloodSample"
                    v-model="formData.SubmittingTest.isBloodSample"
                    :checked="formData.SubmittingTest.isBloodSample !== 0"
                  />
                  <label
                    style="margin-top: 2px; margin-left: 8px"
                    class="form-check-label"
                    for="inlineCheckbox1"
                    >ส่งตัวอย่างเลือดผู้ป่วยหลังจากเกิดปฏิกิริยา (EDTA blood 6
                    ml.)</label
                  >
                </div>
              </div>
            </div>
            <!--ส่งถุงเลือดที่เกิดปฏิกริยาพร้อมใบคล้องถุงเลือด-->
            <div class="col-md-12">
              <div style="display: block; margin-top: 18px">
                <div class="form-check form-check-inline">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    id="inlineCheckbox1"
                    :value="formData.SubmittingTest.isBloodBagReaction"
                    v-model="formData.SubmittingTest.isBloodBagReaction"
                    :checked="formData.SubmittingTest.isBloodBagReaction !== 0"
                  />
                  <label
                    style="margin-top: 2px; margin-left: 8px"
                    class="form-check-label"
                    for="inlineCheckbox1"
                    >ส่งถุงเลือดที่เกิดปฏิกริยาพร้อมใบคล้องถุงเลือด</label
                  >
                </div>
              </div>
            </div>
            <!-- พยาบาลผู้รายงาน -->
            <div class="col-md-6 mt16">
              <div class="card-box-info-row-component-style">
                <div style="display: inline; position: absolute; width: 100%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    พยาบาลผู้รายงาน
                  </p>

                  <input
                    class="form-control typing-box-style"
                    style="
                      padding-left: 16px;
                      padding-right: 16px;
                      padding-top: 0px;
                      padding-bottom: 0px;
                    "
                    type="text"
                    aria-label="default input example"
                    placeholder="กรุณากรอกข้อมูล"
                    v-model="formData.SubmittingTest.nurseName"
                  />
                </div>
              </div>
            </div>
            <!-- วันที่ -->
            <div class="col-md-3 mt16 vertical-style-50w">
              <div class="card-box-info-row-component-style">
                <Icon
                  icon="bx:calendar-event"
                  style="
                    width: 14%;
                    height: 60%;
                    margin-left: 16px;
                    margin-top: 18px;
                  "
                />
                <div style="display: inline; position: absolute; width: 80%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    วันที่
                  </p>
                  <div style="position: relative">
                    <div
                      style="display: inline; position: absolute; width: 100%"
                    >
                      <input
                        class="form-control typing-box-style"
                        style="
                          padding-left: 16px;
                          padding-right: 16px;
                          padding-top: 0px;
                          padding-bottom: 0px;
                        "
                        type="text"
                        aria-label="default input example"
                        placeholder="กรุณากรอกข้อมูล"
                        v-model="formData.SubmittingTest.nurseDateTime"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- เวลา -->
            <div class="col-md-3 mt16 vertical-style-50w">
              <div class="card-box-info-row-component-style">
                <Icon
                  icon="bx:alarm"
                  style="
                    width: 14%;
                    height: 60%;
                    margin-left: 16px;
                    margin-top: 18px;
                  "
                />
                <div style="display: inline; position: absolute; width: 80%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    เวลา
                  </p>
                  <div style="position: relative">
                    <div
                      style="display: inline; position: absolute; width: 100%"
                    >
                      <input
                        class="form-control typing-box-style"
                        style="
                          padding-left: 16px;
                          padding-right: 16px;
                          padding-top: 0px;
                          padding-bottom: 0px;
                        "
                        type="text"
                        aria-label="default input example"
                        placeholder="กรุณากรอกข้อมูล"
                        v-model="formData.SubmittingTest.nurseDateTime"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- แพทย์ผู้ดูแล -->
            <div class="col-md-6 mt16">
              <div class="card-box-info-row-component-style">
                <div style="display: inline; position: absolute; width: 100%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    แพทย์ผู้ดูแล
                  </p>

                  <input
                    class="form-control typing-box-style"
                    style="
                      padding-left: 16px;
                      padding-right: 16px;
                      padding-top: 0px;
                      padding-bottom: 0px;
                    "
                    type="text"
                    aria-label="default input example"
                    placeholder="กรุณากรอกข้อมูล"
                    v-model="formData.SubmittingTest.physicianName"
                  />
                </div>
              </div>
            </div>
            <!-- วันที่ -->
            <div class="col-md-3 mt16 vertical-style-50w">
              <div class="card-box-info-row-component-style">
                <Icon
                  icon="bx:calendar-event"
                  style="
                    width: 14%;
                    height: 60%;
                    margin-left: 16px;
                    margin-top: 18px;
                  "
                />
                <div style="display: inline; position: absolute; width: 80%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    วันที่
                  </p>
                  <div style="position: relative">
                    <div
                      style="display: inline; position: absolute; width: 100%"
                    >
                      <input
                        class="form-control typing-box-style"
                        style="
                          padding-left: 16px;
                          padding-right: 16px;
                          padding-top: 0px;
                          padding-bottom: 0px;
                        "
                        type="text"
                        aria-label="default input example"
                        placeholder="กรุณากรอกข้อมูล"
                        v-model="formData.SubmittingTest.physicianDateTime"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- เวลา -->
            <div class="col-md-3 mt16 vertical-style-50w">
              <div class="card-box-info-row-component-style">
                <Icon
                  icon="bx:alarm"
                  style="
                    width: 14%;
                    height: 60%;
                    margin-left: 16px;
                    margin-top: 18px;
                  "
                />
                <div style="display: inline; position: absolute; width: 80%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    เวลา
                  </p>
                  <div style="position: relative">
                    <div
                      style="display: inline; position: absolute; width: 100%"
                    >
                      <input
                        class="form-control typing-box-style"
                        style="
                          padding-left: 16px;
                          padding-right: 16px;
                          padding-top: 0px;
                          padding-bottom: 0px;
                        "
                        type="text"
                        aria-label="default input example"
                        placeholder="กรุณากรอกข้อมูล"
                        v-model="formData.SubmittingTest.physicianDateTime"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card" style="border: 0px">
          <div style="display: flex; justify-content: flex-end; gap: 2%">
            <button class="btn button-style-close" style="margin-top: 32px">
              ปิด
            </button>
            <button
              class="btn button-style-save"
              style="margin-top: 32px"
              type="submit"
            >
              บันทึกข้อมูล
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>
<style scoped>
.fontSize_header {
  font-size: 1.6rem;
  font-weight: 700;
  font-family: "IBM Plex Sans Thai";
}
.fontTopicBox {
  font-family: "IBM Plex Sans Thai";
  font-size: 1.2rem;
  font-weight: 600;
  margin-top: 30px;
  margin-bottom: 0;
  color: #3c3c3c;
}
.fontInsideBox {
  font-family: "IBM Plex Sans Thai";
  font-size: 1.1rem;
  font-weight: 400;
  color: #c4c4c4;
}
.fontTopicInfo {
  font-family: "IBM Plex Sans Thai";
  font-weight: 700;
  font-size: 0.9rem;
  color: #202124;
  display: inline;
}
.fontTopicCheckBox {
  font-family: "IBM Plex Sans";
  font-weight: 500;
  font-size: 1rem;
  color: #202124;
  display: inline;
}
.custom-select select {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'%3E%3Cpath d='M12 2C6.486 2 2 6.486 2 12C2 17.514 6.486 22 12 22C17.514 22 22 17.514 22 12C22 6.486 17.514 2 12 2ZM12 16.414L6.293 10.707L7.707 9.293L12 13.586L16.293 9.293L17.707 10.707L12 16.414Z' fill='%2300BFA5'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 8px top 50%;
}
.size-col-1point5 {
  width: 12.5%;
}
.size-col-1point75 {
  width: 14.6%;
}
.size-col-2point5 {
  width: 20%;
}
.size-col-4point5 {
  width: 37.5%;
}
.size-col-7point5 {
  width: 62.5%;
}
.container-md {
  margin-left: auto;
  margin-right: auto;
}
.card-box-info-style {
  margin-top: 16px;
  width: auto;
  /* height: 375px; */
  border: 0;
}
.card-box-style {
  width: 100%;
  height: 48px;
  border: 2px solid #dee0e6;
  border-radius: 8px;
  background-color: #fbfbfc;
}
.card-box-body-style {
  width: 100%;
  height: 48px;
  padding: 12px 0px 0px 16px;
}
.card-box-info-row-component-style {
  width: 100%;
  height: 50px;
  background-color: rgb(213, 224, 224, 20%);
  position: relative;
  border-bottom: 2px solid #d5e0e0;
  border-radius: 5px 5px 0px 0px;
}
.typing-box-style {
  width: 100%;
  background-color: rgb(213, 224, 224, 0);
  border: rgb(213, 224, 224, 0);
  font-family: "Noto Looped Thai";
  font-weight: 400;
  font-size: 16px;
  color: #202124;
}
.select-box-style {
  width: 100%;
  background-color: rgb(213, 224, 224, 0);
  border: rgb(213, 224, 224, 0);
}
.form-select-sm:focus {
  outline: none;
  border-radius: 0px;
}

.form-control:focus {
  background-color: rgb(213, 224, 224, 0%);
  box-shadow: none;
}
.mt16 {
  margin-top: 16px;
}
.ml16 {
  margin-left: 16px;
}
hr.dashed {
  border-top: 2px dashed #999;
  width: 100%;
  margin-top: 32px;
}

.form-check-input[type="radio"] {
  border-radius: 3px;
  border: 3px solid rgba(157, 157, 157, 1);
}
.form-check-input[type="checkbox"] {
  border-radius: 3px;
  border: 3px solid rgba(157, 157, 157, 1);
}
.form-check-input:focus {
  border-radius: 3px;
  border: 3px solid rgba(157, 157, 157, 1);
  box-shadow: none;
  --bs-form-check-bg-image: url('data:image/svg+xml,%3csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23FFFFFF" width="24px" height="24px"%3e%3cpath d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" /%3e%3c/svg%3e');
}

.form-check-input:checked {
  background-color: rgba(157, 157, 157, 1);
  --bs-form-check-bg-image: url('data:image/svg+xml,%3csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23FFFFFF" width="24px" height="24px"%3e%3cpath d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" /%3e%3c/svg%3e');
}
.form-check-input:checked[type="radio"] {
  --bs-form-check-bg-image: url('data:image/svg+xml,%3csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23FFFFFF" width="24px" height="24px"%3e%3cpath d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" /%3e%3c/svg%3e');
}
.form-check-input:checked[type="checkbox"] {
  --bs-form-check-bg-image: url('data:image/svg+xml,%3csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23FFFFFF" width="24px" height="24px"%3e%3cpath d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" /%3e%3c/svg%3e');
}

.form-check-input {
  width: 22px;
  height: 22px;
}
.flexAndSpaceBetween {
  display: flex;
  justify-content: space-between;
}

.accordion-button {
  background-color: rgba(255, 255, 255, 1);
  border-bottom: 2px solid #d5e0e0;
}
.accordion-button:not(.collapsed) {
  border-bottom: 0px;
  background-color: rgba(255, 255, 255, 1);
  box-shadow: none;
}
.accordion-button:focus {
  border-bottom: none;
  box-shadow: none;
}

.accordion {
  --bs-accordion-btn-icon: url("data:image/svg+xml,%3Csvg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M4.68937 7.10275L11.0131 13.4265L12.2079 14.6213L13.4026 13.4265L19.7264 7.10275L21.4158 8.79211L12.2079 18L3 8.79211L4.68937 7.10275Z' fill='%2300BFA5'/%3E%3C/svg%3E");

  --bs-accordion-btn-active-icon: url("data:image/svg+xml,%3Csvg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M4.68937 7.10275L11.0131 13.4265L12.2079 14.6213L13.4026 13.4265L19.7264 7.10275L21.4158 8.79211L12.2079 18L3 8.79211L4.68937 7.10275Z' fill='%2300BFA5'/%3E%3C/svg%3E");

  --bs-accordion-btn-icon-width: 2rem;
}

.button-style-save {
  width: 16%;
  height: 44px;
  border-radius: 100px;
  background-color: rgba(0, 191, 165, 1);
  font-family: "IBM Plex Sans Thai";
  font-size: 1.2rem;
  font-weight: 600;
  margin-top: 30px;
  margin-bottom: 0;
  color: rgba(255, 255, 255, 1);
  transition: background-color 0.3s ease, color 0.3s ease; /* Define smooth transition for background-color and color */
}

.button-style-save:hover {
  background-color: rgba(
    0,
    191,
    165,
    0.8
  ); /* Darker background color on hover */
  color: rgba(255, 255, 255, 0.9); /* Slightly lighter text color on hover */
}
.button-style-close {
  width: 16%;
  height: 44px;
  border-radius: 100px;
  background-color: transparent;
  border: 2px solid rgba(0, 191, 165, 1);
  font-family: "IBM Plex Sans Thai";
  font-size: 1.2rem;
  font-weight: 600;
  margin-top: 30px;
  margin-bottom: 0;
  color: rgba(255, 59, 48, 1);
  transition: background-color 0.3s ease, color 0.3s ease; /* Define smooth transition for background-color and color */
}

.button-style-close:hover {
  background-color: rgba(255, 59, 48, 1); /* Light background color on hover */
  color: rgba(255, 255, 255, 1); /* Darker text color on hover */
}
@media only screen and (min-device-width: 768px) and (max-device-width: 1100px) {
  .fontSize_header {
    font-size: 20px;
  }
  .fontTopicBox {
    font-size: 18px;
  }
  .fontTopicInfo {
    font-size: 14px;
  }
  .fontInsideBox {
    font-size: 14px;
  }
  .container-md {
    margin-left: auto;
    margin-right: auto;
  }
  .mt16 {
    margin-top: 16px;
  }
  .pdl16Horizontal {
    padding-left: 16px;
  }
  .size-col-43w {
    width: 43%;
  }
  .size-col-57w {
    width: 57%;
  }
  .size-col-50w {
    width: 50%;
  }
}
@media only screen and ((max-device-width: 768px) or (max-device-width: 810px)) {
  .fontSize_header {
    font-size: 20px;
  }
  .fontTopicBox {
    font-size: 18px;
  }
  .fontInsideBox {
    font-size: 16px;
  }
  .container-md {
    margin-left: auto;
    margin-right: auto;
  }
  .vertical-style-50w {
    width: 50%;
    margin-bottom: 16px;
  }
  .vertical-style-33w {
    width: 33.33%;
    margin-bottom: 16px;
  }
  .vertical-style-100w {
    width: 100%;
    margin-bottom: 16px;
  }
  .mt16 {
    margin-top: 0px;
  }
  .mb16 {
    margin-bottom: 16px;
  }
  .pdl16Horizontal {
    padding-left: 0px;
  }
  .flexAndSpaceBetween {
    display: block;
    justify-content: none;
  }
}
</style>
