<script>
import { defineComponent } from "vue";
import { Icon } from "@iconify/vue";
import { useCurrentTime } from "../general/useCurrentTime";
import {
  parseDate,
  parseTime,
  currentDate,
  currentTime,
} from "../general/dateUtils";
import axios from "axios";
import { watch } from "vue";

export default defineComponent({
  name: "TransfusionFormDisplay",
  data() {
    return {
      // TR_Form Section
      formData: {
        // Patient Info
        PatientInfo: {
          title: "",
          firstName: "",
          lastName: "",
          HN: "",
          //ค่าอะไร ? id?
          TXN: "",
          //ค่าอะไร ?
          pt_type: "",
          createdDate: "" || new Date(),
          ward: "",
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
        },
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
        //Vital Signs
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
        //SignsAndSymptoms
        SignsAndSymptomsObject: {
          idSignsAndSymptomsName: [],
          Other: "",
        },
        //SubmittingTest
        SubmittingTest: {
          isBloodSample: "",
          isBloodBagReaction: "",
          nurseName: "",
          nurseDateTime: new Date(),
          physicianName: "",
          physicianDateTime: new Date(),
        },
        DetailRecordIn24Hrs: {},
      },
      signsAndSymptomsOptions: [],
      reactionCategory: [],
      //baseURL
      baseURL: import.meta.env.VITE_BASE_URL,
      blood_tranf_detail: {},
      //BP.
      beforeReactionBPSectionOne: "",
      beforeReactionBPSectionTwo: "",
      afterReactionBPSectionOne: "",
      afterReactionBPSectionTwo: "",
      //nurse doctor DateTime
      nurseDate: new Date().toISOString().split("T")[0],
      nurseTime: this.parseTime(new Date()),
      physicianDate: new Date().toISOString().split("T")[0],
      physicianTime: this.parseTime(new Date()),
      SignsAndSymptomsOtherObject: {
        isOther: "",
      },
      showReactionCategoryInput: "",
    };
  },
  async mounted() {
    // Fetch Signs and Symptoms data on component mount
    const idTR_Form = this.$route.params.id;
    /* this.blood_transf_id = this.$route.params.id; */
    await this.fetchSignsAndSymptoms();
    await this.fetchReactionCategory();
    await this.fetchTR_Form(idTR_Form);

    watch(
      [
        () => this.signsAndSymptomsOptions,
        () => this.reactionCategory,
        () => this.fetchTR_Form,
      ],
      ([newSigns, newReaction]) => {
        // This block will run whenever signsAndSymptomsOptions or reactionCategory change
        this.fetchSignsAndSymptoms();
        this.fetchReactionCategory();
        this.fetchTR_Form(idTR_Form);
      }
    );
  },
  computed: {
    inputWidth() {
      return (category) => {
        // Calculate width based on input values
        if (
          category === "beforeBP" &&
          this.beforeReactionBPSectionOne &&
          this.beforeReactionBPSectionTwo
        ) {
          return "inputWidth";
        } else if (
          category === "afterBP" &&
          this.afterReactionBPSectionOne &&
          this.afterReactionBPSectionTwo
        ) {
          return "inputWidth";
        } else {
          return "inputWidth"; // Set the width to 100% initially or if only one input is completed
        }
      };
    },
    HNWidth() {
      return () => {
        const name =
          this.formData.PatientInfo.title +
          " " +
          this.formData.PatientInfo.firstName +
          " " +
          this.formData.PatientInfo.lastName;
        const length = name.length;
        if (length > 20) {
          return "HNWidth";
        } else {
          return "";
        }
      };
    },
    NameWidth() {
      return () => {
        const name =
          this.formData.PatientInfo.title +
          " " +
          this.formData.PatientInfo.firstName +
          " " +
          this.formData.PatientInfo.lastName;
        const length = name.length;
        if (length > 20) {
          this.styleName2Line = true;
          return "NameWidth";
        } else {
          return "";
        }
      };
    },
  },
  methods: {
    async fetchSignsAndSymptoms() {
      try {
        const response = await axios.get(
          this.baseURL + "trasfusion-form/getAllSignsAndSymptoms"
        );
        this.signsAndSymptomsOptions = response.data;
      } catch (error) {
        console.error("Error fetching Signs and Symptoms data:", error);
      }
    },
    async fetchReactionCategory() {
      try {
        const response = await axios.get(
          this.baseURL + "trasfusion-form/getAllReactionCategory"
        );
        /* console.log(response.data); */
        this.reactionCategory = response.data;
      } catch (error) {
        console.error("Error fetching Reaction Category data:", error);
      }
    },
    async fetchTR_Form(idTR_Form) {
      try {
        const response = await axios.get(
          this.baseURL + `trasfusion-form/getTR_Form/${idTR_Form}`
        );
        this.formData.PatientInfo = response.data.PatientInfo;
        this.formData.BloodTransfusionTest = response.data.BloodTransfusionTest;
        this.formData.VitalSigns = response.data.VitalSigns;
        let beforeReactionBP = response.data.VitalSigns.beforeReactionBP;
        let afterReactionBP = response.data.VitalSigns.afterReactionBP;
        let [beforeReactionBPSectionOne, beforeReactionBPSectionTwo] =
          beforeReactionBP.split("/");
        let [afterReactionBPSectionOne, afterReactionBPSectionTwo] =
          beforeReactionBP.split("/");
        this.formData.VitalSigns.beforeReactionTime = parseTime(
          response.data.VitalSigns.beforeReactionTime
        );
        this.formData.VitalSigns.afterReactionTime = parseTime(
          response.data.VitalSigns.afterReactionTime
        );
        this.beforeReactionBPSectionOne = beforeReactionBPSectionOne;
        this.beforeReactionBPSectionTwo = beforeReactionBPSectionTwo;
        this.afterReactionBPSectionOne = afterReactionBPSectionOne;
        this.afterReactionBPSectionTwo = afterReactionBPSectionTwo;
        this.formData.SignsAndSymptomsObject =
          response.data.SignsAndSymptomsObject;
        this.formData.DetailRecordIn24Hrs = response.data.DetailRecordIn24Hrs;
        this.formData.SubmittingTest = response.data.SubmittingTest;
        this.nurseDate = new Date(response.data.SubmittingTest.nurseDateTime)
          .toISOString()
          .split("T")[0];
        this.nurseTime = parseTime(
          new Date(response.data.SubmittingTest.nurseDateTime)
        );
        this.physicianDate = new Date(
          response.data.SubmittingTest.physicianDateTime
        )
          .toISOString()
          .split("T")[0];
        this.physicianTime = parseTime(
          new Date(response.data.SubmittingTest.physicianDateTime)
        );
        console.log(response.data.PatientInfo);
      } catch (error) {
        console.error("Error fetching TR Form data:", error);
      }
    },
    parseDate,
    parseTime,
    // go back to previous page
    navigateToPreviousPage() {
      this.$router.push(`/mainBloodChecklist`);
      console.log("click");
      $("#CloseButton").modal("hide");
    },
    //cleansing form
  },
  watch: {
    "SignsAndSymptomsOtherObject.isOther": function (newVal, oldVal) {
      if (newVal !== 1) {
        this.formData.SignsAndSymptomsObject.Other = "";
      }
    },
    "formData.PatientInfo.isReactionHistory": function (newVal, oldVal) {
      if (newVal === 1 || newVal === "1") {
        // Set a flag or update a state variable to show the input field
        this.showReactionCategoryInput = true;
      } else {
        this.showReactionCategoryInput = false;
      }
    },
  },
  components: {
    Icon,
  },
});
</script>
<template>
  <div class="container-md">
      <div class="card" style="border: 0px; justify-content: center">
        <!-- header -->
        <div class="row">
          <div class="col-md-6 vertical-style-100w">
            <div style="margin-top: 60px">
              <p class="fontSize_header">
                ฟอร์มนำส่งตรวจการเกิดปฏิกิริยาจากการรับเลือด
              </p>
            </div>
          </div>
          <!-- HN -->
          <div class="col-md-3 vertical-style-33w" :class="HNWidth()">
            <p class="fontTopicBox">HN</p>
            <div class="card card-box-style">
              <div class="card-body card-box-body-style">
                <!-- HN value -->
                <p class="fontInsideBox">
                  <Icon
                    icon="bx:id-card"
                    style="
                      color: #00bfa5;
                      width: 32;
                      height: 32;
                      margin-bottom: 4px;
                    "
                  ></Icon>
                  &nbsp; {{ formData.PatientInfo.HN }}
                </p>
              </div>
            </div>
          </div>
          <!-- ชื่อผู้ป่วย -->
          <div class="col-md-3 vertical-style-66w" :class="NameWidth()">
            <div>
              <p class="fontTopicBox">ชื่อผู้ป่วย</p>
              <div class="card card-box-style">
                <div class="card-body card-box-body-style">
                  <p class="fontInsideBox" style="margin-top: 6px;">
                    &nbsp;
                    {{
                      formData.PatientInfo.title +
                      " " +
                      formData.PatientInfo.firstName +
                      " " +
                      formData.PatientInfo.lastName
                    }}
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
            <div class="col-md-3">
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
                        :value="parseDate(formData.PatientInfo.createdDate)"
                        aria-label="readonly input example"
                        readonly
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- เวลา -->
            <div class="col-md-3">
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
                      <!--value is "currentTime()" -->
                      <input
                        class="form-control typing-box-style"
                        style="
                          padding-left: 16px;
                          padding-right: 16px;
                          padding-top: 0px;
                          padding-bottom: 0px;
                        "
                        type="text"
                        :value="parseTime(formData.PatientInfo.createdDate)"
                        aria-label="readonly input example"
                        readonly
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- หอผู้ป่วย -->
            <div class="col-md-3">
              <div class="card-box-info-row-component-style">
                <div style="display: inline; position: absolute; width: 80%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    หอผู้ป่วย
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
                        :value="formData.PatientInfo.ward"
                        aria-label="readonly input example"
                        readonly
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- โทร -->
            <div class="col-md-3">
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
                        :value="formData.PatientInfo.phoneNumber"
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
            <div class="col-md-8 mt16">
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
                    :value="formData.PatientInfo.diagnosis"
                    aria-label="default input example"
                    readonly
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
                  <input
                    class="form-control typing-box-style"
                    style="
                      padding-left: 16px;
                      padding-right: 16px;
                      padding-top: 0px;
                      padding-bottom: 0px;
                    "
                    type="text"
                    :value="formData.PatientInfo.primaryPhysicianName"
                    aria-label="default input example"
                    readonly
                  />
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
                  <input
                    class="form-control typing-box-style"
                    style="
                      padding-left: 16px;
                      padding-right: 16px;
                      padding-top: 0px;
                      padding-bottom: 0px;
                    "
                    type="text"
                    :value="formData.PatientInfo.bloodGroup_Patient"
                    aria-label="default input example"
                    readonly
                  />
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

                  <input
                    class="form-control typing-box-style"
                    style="
                      padding-left: 16px;
                      padding-right: 16px;
                      padding-top: 0px;
                      padding-bottom: 0px;
                    "
                    type="text"
                    :value="formData.PatientInfo.Rh_Patient"
                    aria-label="default input example"
                    readonly
                  />
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

                  <input
                    class="form-control typing-box-style"
                    style="
                      padding-left: 16px;
                      padding-right: 16px;
                      padding-top: 0px;
                      padding-bottom: 0px;
                    "
                    type="text"
                    :value="formData.PatientInfo.blood_component"
                    aria-label="default input example"
                    readonly
                  />
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

                  <input
                    class="form-control typing-box-style"
                    style="
                      padding-left: 16px;
                      padding-right: 16px;
                      padding-top: 0px;
                      padding-bottom: 0px;
                    "
                    type="text"
                    :value="formData.PatientInfo.bloodGroup_Donor"
                    aria-label="default input example"
                    readonly
                  />
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

                  <input
                    class="form-control typing-box-style"
                    style="
                      padding-left: 16px;
                      padding-right: 16px;
                      padding-top: 0px;
                      padding-bottom: 0px;
                    "
                    type="text"
                    :value="formData.PatientInfo.Rh_Donor"
                    aria-label="default input example"
                    readonly
                  />
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
                    type="text"
                    :value="formData.PatientInfo.bloodBagNumber"
                    aria-label="default input example"
                    readonly
                  />
                </div>
              </div>
            </div>
            <!-- ปริมาตรที่เติม -->
            <div class="col-md-3 mt16 vertical-style-50w">
              <div class="card-box-info-row-component-style">
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
                      type="text"
                      :value="formData.PatientInfo.volume"
                      aria-label="default input example"
                    />
                    <span
                      class="input-group-text"
                      style="
                        border: 0px;
                        width: 10%;
                        padding-left: 0px;
                        margin-right: 16px;
                        margin-bottom: 1px;
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
                    :value="formData.PatientInfo.medicationHistory"
                    readonly
                  />
                </div>
              </div>
            </div>
            <!--row 5 -->
            <!-- ประวัติการเกิดปฏิกิริยาการรับเลือด มีหรือไม่ -->
            <div
              class="col-md-5 size-col-4point5 mt16 size-col-50w vertical-style-100w"
            >
              <div style="display: flex">
                <p
                  class="fontTopicInfo"
                  style="margin-top: 26.5px; display: block"
                >
                  ประวัติการเกิดปฏิกิริยาจากการรับเลือด
                </p>
                <div
                  style="display: block; margin-left: 32px; margin-top: 22px"
                >
                  <div class="form-check form-check-inline">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="isReactionHistory"
                      id="isReactionHistory1"
                      :checked = "formData.PatientInfo.isReactionHistory == 0 ? true : false"
                      :disabled ="formData.PatientInfo.isReactionHistory == 0 ? false : true"
                    />
                    <label
                      class="form-check-label"
                      for="isReactionHistory1"
                      style=" margin-top: 2px; margin-left: 10px"
                      >ไม่มี</label
                    >
                  </div>
                  <div class="form-check form-check-inline">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="isReactionHistory"
                      id="isReactionHistory2"
                      :checked = "formData.PatientInfo.isReactionHistory == 1 ? true : false"
                      :disabled ="formData.PatientInfo.isReactionHistory == 1 ? false : true"
                    />
                    <label
                      class="form-check-label"
                      for="isReactionHistory2"
                      style=" margin-top: 2px; margin-left: 10px"
                      >มี</label
                    >
                  </div>
                </div>
              </div>
            </div>
            <!-- ชนิดของปฏิกิริยา -->
            <div
              v-if="showReactionCategoryInput"
              class="col-md-7 size-col-7point5 mt16 size-col-50w vertical-style-100w"
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
                    :value="formData.PatientInfo.reactionCategory"
                    readonly
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
                    style="width: 40%; margin-top: 26.5px; display: block"
                  >
                    ชื่อ - นามสกุล ผู้ป่วย
                  </p>
                  <div
                    style="
                      display: block;
                      width: 60%;
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
                        id="isCorrectPatientName1"
                        :checked = "formData.BloodTransfusionTest.isCorrectPatientName == 1 ? true : false"
                        :disabled ="formData.BloodTransfusionTest.isCorrectPatientName == 1 ? false : true"
                      />
                      <label
                        class="form-check-label"
                        for="isCorrectPatientName1"
                        style=" margin-top: 2px; margin-left: 10px"
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
                        id="isCorrectPatientName2"
                        :checked = "formData.BloodTransfusionTest.isCorrectPatientName == 0 ? true : false"
                        :disabled ="formData.BloodTransfusionTest.isCorrectPatientName == 0 ? false : true"
                      />
                      <label
                        class="form-check-label"
                        for="isCorrectPatientName2"
                        style=" margin-top: 2px; margin-left: 10px"
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
                    style="width: 40%; margin-top: 26.5px; display: block"
                  >
                    ภายใน 24 ชั่วโมง ผู้ป่วย
                  </p>
                  <div
                    style="
                      display: block;
                      width: 60%;
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
                        id="isWithin24hrsFever1"
                        :checked = "formData.BloodTransfusionTest.isWithin24hrsFever == 1 ? true : false"
                        :disabled ="formData.BloodTransfusionTest.isWithin24hrsFever == 1 ? false : true"
                      />
                      <label
                        class="form-check-label"
                        for="isWithin24hrsFever1"
                        style=" margin-top: 2px; margin-left: 10px"
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
                        id="isWithin24hrsFever2"
                        :checked = "formData.BloodTransfusionTest.isWithin24hrsFever == 0 ? true : false"
                        :disabled ="formData.BloodTransfusionTest.isWithin24hrsFever == 0 ? false : true"
                      />
                      <label
                        class="form-check-label"
                        for="isWithin24hrsFever2"
                        style=" margin-top: 2px; margin-left: 10px"
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
                    style="width: 40%; margin-top: 26.5px; display: block"
                  >
                    ชนิดของเลือดที่ให้
                  </p>
                  <div
                    style="
                      display: block;
                      width: 60%;
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
                        id="isCorrectBloodComponent1"
                        :checked = "formData.BloodTransfusionTest.isCorrectBloodComponent == 1 ? true : false"
                        :disabled ="formData.BloodTransfusionTest.isCorrectBloodComponent == 1 ? false : true"
                      />
                      <label
                        class="form-check-label"
                        for="isCorrectBloodComponent1"
                        style=" margin-top: 2px; margin-left: 10px"
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
                        id="isCorrectBloodComponent2"
                        :checked = "formData.BloodTransfusionTest.isCorrectBloodComponent == 0 ? true : false"
                        :disabled ="formData.BloodTransfusionTest.isCorrectBloodComponent == 0 ? false : true"
                      />
                      <label
                        class="form-check-label"
                        for="isCorrectBloodComponent2"
                        style=" margin-top: 2px; margin-left: 10px"
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
                    style="width: 40%; margin-top: 26.5px; display: block"
                  >
                    บันทึกการให้เลือด
                  </p>
                  <div
                    style="
                      display: block;
                      width: 60%;
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
                        id="isCorrectBloodTransfusionRec1"
                        :checked = "formData.BloodTransfusionTest.isCorrectBloodTransfusionRec == 1 ? true : false"
                        :disabled ="formData.BloodTransfusionTest.isCorrectBloodTransfusionRec == 1 ? false : true"
                      />
                      <label
                        class="form-check-label"
                        for="isCorrectBloodTransfusionRec1"
                        style=" margin-top: 2px; margin-left: 10px"
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
                        id="isCorrectBloodTransfusionRec2"
                        :checked = "formData.BloodTransfusionTest.isCorrectBloodTransfusionRec == 0 ? true : false"
                        :disabled ="formData.BloodTransfusionTest.isCorrectBloodTransfusionRec == 0 ? false : true"
                      />
                      <label
                        class="form-check-label"
                        for="isCorrectBloodTransfusionRec2"
                        style=" margin-top: 2px; margin-left: 10px"
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
                    style="width: 40%; margin-top: 26.5px; display: block"
                  >
                    หมายเลขถุงเลือด
                  </p>
                  <div
                    style="
                      display: block;
                      width: 60%;
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
                        id="isCorrectBloodBagNumber1"
                        :checked = "formData.BloodTransfusionTest.isCorrectBloodBagNumber == 1 ? true : false"
                        :disabled ="formData.BloodTransfusionTest.isCorrectBloodBagNumber == 1 ? false : true"
                      />
                      <label
                        class="form-check-label"
                        for="isCorrectBloodBagNumber1"
                        style=" margin-top: 2px; margin-left: 10px"
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
                        id="isCorrectBloodBagNumber2"
                        :checked = "formData.BloodTransfusionTest.isCorrectBloodBagNumber == 0 ? true : false"
                        :disabled ="formData.BloodTransfusionTest.isCorrectBloodBagNumber == 0 ? false : true"
                      />
                      <label
                        class="form-check-label"
                        for="isCorrectBloodBagNumber2"
                        style=" margin-top: 2px; margin-left: 10px"
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
                    style="width: 40%; margin-top: 26.5px; display: block"
                  >
                    หมู่เลือดผู้บริจาค
                  </p>
                  <div
                    style="
                      display: block;
                      width: 60%;
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
                        id="isCorrectBloodGroupDonor1"
                        :checked = "formData.BloodTransfusionTest.isCorrectBloodGroupDonor == 1 ? true : false"
                        :disabled ="formData.BloodTransfusionTest.isCorrectBloodGroupDonor == 1 ? false : true"
                      />
                      <label
                        class="form-check-label"
                        for="isCorrectBloodGroupDonor1"
                        style=" margin-top: 2px; margin-left: 10px"
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
                        id="isCorrectBloodGroupDonor2"
                        :checked = "formData.BloodTransfusionTest.isCorrectBloodGroupDonor == 0 ? true : false"
                        :disabled ="formData.BloodTransfusionTest.isCorrectBloodGroupDonor == 0 ? false : true"
                      />
                      <label
                        class="form-check-label"
                        for="isCorrectBloodGroupDonor2"
                        style=" margin-top: 2px; margin-left: 10px"
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
                    style="width: 40%; margin-top: 26.5px; display: block"
                  >
                    หมู่เลือดผู้ป่วย
                  </p>
                  <div
                    style="
                      display: block;
                      width: 60%;
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
                        id="isCorrectBloodGroupPatient1"
                        :checked = "formData.BloodTransfusionTest.isCorrectBloodGroupPatient == 1 ? true : false"
                        :disabled ="formData.BloodTransfusionTest.isCorrectBloodGroupPatient == 1 ? false : true"
                      />
                      <label
                        class="form-check-label"
                        for="isCorrectBloodGroupPatient1"
                        style=" margin-top: 2px; margin-left: 10px"
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
                        id="isCorrectBloodGroupPatient2"
                        :checked = "formData.BloodTransfusionTest.isCorrectBloodGroupPatient == 0 ? true : false"
                        :disabled ="formData.BloodTransfusionTest.isCorrectBloodGroupPatient == 0 ? false : true"
                      />
                      <label
                        class="form-check-label"
                        for="isCorrectBloodGroupPatient2"
                        style=" margin-top: 2px; margin-left: 10px"
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
                        type="time"
                        v-model="formData.VitalSigns.beforeReactionTime"
                        aria-label="readonly input example"
                        readonly
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
                      v-model="formData.VitalSigns.beforeReactionTemp"
                      readonly
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
            <!-- ก่อนเกิดปฏิกิริยา B.P.-->
            <div class="col-md-3 mt16 size-col-2point5">
              <div class="card-box-info-row-component-style">
                <div style="display: inline; position: absolute; width: 100%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    B.P.
                  </p>
                  <div style="display: flex; height: 24px">
                    <input
                      class="form-control typing-box-style"
                      :class="inputWidth('beforeBP')"
                      style="
                        padding-left: 16px;
                        padding-right: 16px;
                        padding-top: 0px;
                        padding-bottom: 0px;
                        text-align: center;
                      "
                      type="text"
                      aria-label="default input example"
                      placeholder="กรุณากรอกข้อมูล"
                      v-model="beforeReactionBPSectionOne"
                      readonly
                    />
                    <p class="fontTopicInfo" style="margin-top: 2px">/</p>
                    <input
                      class="form-control typing-box-style"
                      :class="inputWidth('beforeBP')"
                      style="
                        padding-left: 16px;
                        padding-right: 16px;
                        padding-top: 0px;
                        padding-bottom: 0px;
                        text-align: center;
                      "
                      type="text"
                      aria-label="default input example"
                      placeholder="กรุณากรอกข้อมูล"
                      v-model="beforeReactionBPSectionTwo"
                      readonly
                    />
                  </div>
                </div>
              </div>
            </div>
            <!-- ก่อนเกิดปฏิกิริยา Pulse-->
            <div class="col-md-3 mt16 size-col-2point5">
              <div class="input-group card-box-info-row-component-style">
                <div style="display: inline; position: absolute; width: 100%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    Pulse
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
                      v-model="formData.VitalSigns.beforeReactionPulse"
                      readonly
                    />
                  </div>
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
                        type="time"
                        v-model="formData.VitalSigns.afterReactionTime"
                        aria-label="readonly input example"
                        readonly
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
                      readonly
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
            <!-- หลังเกิดปฏิกิริยา B.P.-->
            <div class="col-md-3 mt16 size-col-2point5">
              <div class="card-box-info-row-component-style">
                <div style="display: inline; position: absolute; width: 100%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    B.P.
                  </p>
                  <div style="display: flex; height: 24px">
                    <input
                      class="form-control typing-box-style"
                      :class="inputWidth('afterBP')"
                      style="
                        padding-left: 16px;
                        padding-right: 16px;
                        padding-top: 0px;
                        padding-bottom: 0px;
                        text-align: center;
                      "
                      type="text"
                      aria-label="default input example"
                      placeholder="กรุณากรอกข้อมูล"
                      v-model="afterReactionBPSectionOne"
                      readonly
                    />
                    <p class="fontTopicInfo" style="margin-top: 2px">/</p>
                    <input
                      class="form-control typing-box-style"
                      :class="inputWidth('afterBP')"
                      style="
                        padding-left: 16px;
                        padding-right: 16px;
                        padding-top: 0px;
                        padding-bottom: 0px;
                        text-align: center;
                      "
                      type="text"
                      aria-label="default input example"
                      placeholder="กรุณากรอกข้อมูล"
                      v-model="afterReactionBPSectionTwo"
                      readonly
                    />
                  </div>
                </div>
              </div>
            </div>
            <!-- หลังเกิดปฏิกิริยา Pulse-->
            <div class="col-md-3 mt16 size-col-2point5">
              <div class="input-group card-box-info-row-component-style">
                <div style="display: inline; position: absolute; width: 100%">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    Pulse
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
                      v-model="formData.VitalSigns.afterReactionPulse"
                      readonly
                    />
                  </div>
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
          <div v-if="signsAndSymptomsOptions !== null" class="row">
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
                    :id="'inlineCheckbox' + index"
                    :value="SignsAndSymptoms.idSignsAndSymtomsName"
                    v-model="
                      formData.SignsAndSymptomsObject.idSignsAndSymptomsName
                    "
                    disabled
                    :style="{ opacity: formData.SignsAndSymptomsObject.idSignsAndSymptomsName.includes(SignsAndSymptoms.idSignsAndSymtomsName) ? '1' : '' }"
                    />
                  <label
                    style=" margin-top: 2px; margin-left: 5px;"
                    :style="{ opacity: formData.SignsAndSymptomsObject.idSignsAndSymptomsName.includes(SignsAndSymptoms.idSignsAndSymtomsName) ? '1' : '' }"
                    class="form-check-label"
                    :for="'inlineCheckbox' + index"
                    >{{ SignsAndSymptoms.name }}</label
                  >
                </div>
              </div>
              <div
                v-if="
                  index === signsAndSymptomsOptions.length - 1 &&
                  formData.SignsAndSymptomsObject.idSignsAndSymptomsName.includes(
                    SignsAndSymptoms.idSignsAndSymtomsName
                  )
                "
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
                      v-model="formData.SignsAndSymptomsObject.Other"
                      readonly
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
                บันทึกรายละเอียดในกรณีที่มีการเติมเลือดมาก่อน ภายใน 24 ชั่วโมง
              </p>
            </div>
            <div class="accordion mt16" id="accordionPanelsStayOpenExample">
              <div
                class="accordion-item"
                v-for="(blood_transf24, index) in formData.DetailRecordIn24Hrs"
                :key="index"
              >
                <h2
                  class="accordion-header"
                  :id="'panelsStayOpen-heading' + index"
                >
                  <button
                    class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    :data-bs-target="'#panelsStayOpen-collapse' + index"
                    aria-expanded="false"
                    :aria-controls="'panelsStayOpen-collapse' + index"
                  >
                    <div
                      style="
                        display: inline;
                        position: absolute;
                        width: 100%;
                        margin-top: 8px;
                      "
                    >
                      <p class="fontTopicInfo">
                        <Icon
                          icon="healthicons:blood-bag"
                          width="24"
                          height="24"
                        />
                        หมายเลขถุงเลือด : {{ blood_transf24.bloodBagNumber }}
                      </p>
                    </div>
                  </button>
                </h2>
                <div
                  :id="'panelsStayOpen-collapse' + index"
                  class="accordion-collapse collapse"
                  :aria-labelledby="'panelsStayOpen-heading' + index"
                >
                  <div class="accordion-body">
                    <div class="row">
                      <!-- ชนิดของเลือดที่ให้ -->
                      <div class="col-md-2 horizon-style-20w vertical-style-50w">
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
                              ชนิดของเลือด
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
                              :value="blood_transf24.bloodComponent"
                              aria-label="default input example"
                              readonly
                            />
                          </div>
                        </div>
                      </div>
                      <!-- วันที่ -->
                      <div class="col-md-2 horizon-style-25w vertical-style-50w">
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
                                  :value="
                                    parseDate(blood_transf24.startTransfusion)
                                  "
                                  aria-label="readonly input example"
                                  readonly
                                />
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- เริ่ม -->
                      <div class="col-md-2 size-col-1point5 horizon-style-20w vertical-style-50w">
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
                                  :value="
                                    parseTime(blood_transf24.startTransfusion)
                                  "
                                  aria-label="default input example"
                                />
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- เสร็จสิ้น -->
                      <div class="col-md-2 size-col-1point5 horizon-style-20w vertical-style-50w">
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
                                  :value="
                                    parseTime(blood_transf24.endTransfusion)
                                  "
                                  aria-label="default input example"
                                />
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- ปริมาตรที่ให้ -->
                      <div
                        class="col-md-2 size-col-1point75 horizon-style-20w mt-horizon-16 vertical-style-50w"
                      >
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
                                type="text"
                                :value="blood_transf24.volume"
                                aria-label="default input example"
                              />
                              <span
                                class="input-group-text"
                                style="
                                  border: 0px;
                                  width: 10%;
                                  padding-left: 0px;
                                  margin-right: 16px;
                                  margin-bottom: 1px;
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
                        class="col-md-2 size-col-2point5 size-col-43w vertical-style-50w"
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
                              margin-top: 22px;
                            "
                          >
                            <div class="form-check form-check-inline">
                              <input
                                class="form-check-input"
                                type="radio"
                                :name="
                                  'DetailRecordIn24Hrs_isReaction_0_' + index
                                "
                                :id="
                                  'DetailRecordIn24Hrs_isReaction_0_' + index
                                "
                                v-model="
                                  formData.DetailRecordIn24Hrs[index].isReaction
                                "
                                :checked = "formData.DetailRecordIn24Hrs[index].isReaction == 0 ? true : false"
                                :disabled ="formData.DetailRecordIn24Hrs[index].isReaction == 0 ? false : true"
                              />
                              <label
                                class="form-check-label"
                                :for="
                                  'DetailRecordIn24Hrs_isReaction_0_' + index
                                "
                                style=" margin-top: 2px; margin-left: 10px"
                                >ไม่มี</label
                              >
                            </div>
                            <div class="form-check form-check-inline">
                              <input
                                class="form-check-input"
                                type="radio"
                                :name="
                                  'DetailRecordIn24Hrs_isReaction_1_' + index
                                "
                                :id="
                                  'DetailRecordIn24Hrs_isReaction_1_' + index
                                "
                                v-model="
                                  formData.DetailRecordIn24Hrs[index].isReaction
                                "
                                :checked = "formData.DetailRecordIn24Hrs[index].isReaction == 1 ? true : false"
                                :disabled ="formData.DetailRecordIn24Hrs[index].isReaction == 1 ? false : true"
                              />
                              <label
                                class="form-check-label"
                                :for="
                                  'DetailRecordIn24Hrs_isReaction_1_' + index
                                "
                                style=" margin-top: 2px; margin-left: 10px"
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
                    id="isBloodSample1"
                    :value="formData.SubmittingTest.isBloodSample"
                    v-model="formData.SubmittingTest.isBloodSample"
                    :checked="formData.SubmittingTest.isBloodSample === 1"
                    disabled
                    :style="{ opacity: formData.SubmittingTest.isBloodSample == 1 ? '1' : '' }"
                  />
                  <label
                    style="margin-top: 2px; margin-left: 10px"
                    :style="{ opacity: formData.SubmittingTest.isBloodSample == 1 ? '1' : '' }"
                    class="form-check-label"
                    for="isBloodSample1"
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
                    id="isBloodBagReaction2"
                    :value="formData.SubmittingTest.isBloodBagReaction"
                    v-model="formData.SubmittingTest.isBloodBagReaction"
                    :checked="formData.SubmittingTest.isBloodBagReaction === 1"
                    disabled
                    :style="{ opacity: formData.SubmittingTest.isBloodBagReaction === 1 ? '1' : '' }"
                    
                  />
                  <label
                    style="margin-top: 2px; margin-left: 10px"
                    :style="{ opacity: formData.SubmittingTest.isBloodBagReaction === 1 ? '1' : '' }"     
                    class="form-check-label"
                    for="isBloodBagReaction2"
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
                    @input="handleInput('nurse')"
                    aria-label="default input example"
                    placeholder="กรุณากรอกข้อมูล"
                    v-model="formData.SubmittingTest.nurseName"
                  />
                  <ul
                    v-if="
                      showResultsNurse &&
                      formData.SubmittingTest.nurseName.length >= 1
                    "
                    class="autocomplete-results"
                  >
                    <li
                      v-for="(item, index) in filteredItems('nurse')"
                      :key="index"
                      @click="selectNurse(item)"
                    >
                      {{ item }}
                    </li>
                  </ul>
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
                        type="date"
                        v-model="nurseDate"
                        aria-label="readonly input example"
                        id="birthdaytime"
                        name="birthdaytime"
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
                        type="time"
                        v-model="nurseTime"
                        aria-label="readonly input example"
                        id="birthdaytime"
                        name="birthdaytime"
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
                    @input="handleInput('doctor')"
                    aria-label="default input example"
                    placeholder="กรุณากรอกข้อมูล"
                    v-model="formData.SubmittingTest.physicianName"
                  />
                  <ul
                    v-if="
                      showResultsDoctor &&
                      formData.SubmittingTest.physicianName.length >= 1
                    "
                    class="autocomplete-results"
                  >
                    <li
                      v-for="(item, index) in filteredItems('doctor')"
                      :key="index"
                      @click="selectDoctor(item)"
                    >
                      {{ item }}
                    </li>
                  </ul>
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
                        type="date"
                        v-model="physicianDate"
                        aria-label="readonly input example"
                        id="birthdaytime"
                        name="birthdaytime"
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
                        type="time"
                        v-model="physicianTime"
                        aria-label="readonly input example"
                        id="birthdaytime"
                        name="birthdaytime"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card" style="border: 0px; margin-bottom: 32px">
          <div style="display: flex; justify-content: flex-end; gap: 2%">
            <button
              class="btn button-style-close"
              data-bs-toggle="modal"
              data-bs-target="#CloseButton"
              style="margin-top: 32px"
              @click="navigateToPreviousPage"
            >
              ปิด
            </button>
          </div>
        </div>
      </div>
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
  font-weight: 800;
  color: #000000;
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
  padding: 5px 0px 0px 6px;
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
  width: 22%;
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

.autocomplete-results {
  width: 100%;
  list-style: none;
  padding: 0;
  margin: 0;
  border: 2px solid #d5e0e0;
  border-bottom: none;
  border-top: none;
  position: absolute;
  z-index: 999; /* Ensure it appears above other elements */
}

.autocomplete-results li {
  width: 100%;
  background-color: #ffffff;
  border-bottom: 2px solid #d5e0e0;
  padding: 8px;
  cursor: pointer;
}

.autocomplete-results li:hover {
  background-color: rgb(247 247 247);
}

.inputWidth {
  width: 30%
}

.HNWidth{
  width: 16.67%;
}
.NameWidth{
  width: 33.33%;
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
  .size-col-100w {
    width: 100%;
  }
  .inputWidth {
    width: 100%
  }
  .horizon-style-15w {
  width: 15%;
}
  .horizon-style-20w {
  width: 20%;
}
.horizon-style-25w {
  width: 25%;
}
.mt-horizon-16 {
    margin-top: 16px;
  }
  .inputWidth {
  width: 100%
}
.HNWidth{
  width: 16.67%;
}
.NameWidth{
  width: 33.33%;
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
  .vertical-style-66w {
    width: 66.67%;
    margin-bottom: 16px;
  }
  .vertical-style-100w {
    width: 100%;
    margin-bottom: 16px;
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
  .inputWidth {
  width: 100%
}

}
</style>
