<script>
import { defineComponent } from "vue";
import { ref, onBeforeUnmount } from "vue";
import DropDownSVGVue from "../general/DropDownSVG.vue";
import { Icon } from "@iconify/vue";
import { useCurrentTime } from "./useCurrentTime";

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
        isCorrectPatientName: "",
        isWithin24hrsFever: "",
        isCorrectBloodComponent: "",
        isCorrectBloodTransfusionRec: "",
        isCorrectBloodBagNumber: "",
        isCorrectBloodGroupDonor: "",
        isCorrectBloodGroupPatient: "",
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
    };
  },
  methods: {
    currentDate() {
      const current = new Date();
      const date = current.toLocaleDateString("th-TH", {
        year: "numeric",
        month: "long",
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
      const time =
        currentTime.value.getHours() +
        ":" +
        currentTime.value.getMinutes() +
        ":" +
        currentTime.value.getSeconds();
      return time;
    },
    handleSubmit() {
      // Handle form submission logic here
      // You can access form data and perform any validation or API calls
      // If there are errors, you can log them or take appropriate actions
      console.log("Form submitted! : ", this.formData);
      /* const cleaningForm = {
        currentTime : 
      } */
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
                      <!-- <input
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
                      /> -->
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
                    v-model="formData.reactionCategory"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
        <hr class="dashed" />
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
                        v-model="formData.isCorrectPatientName"
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
                        v-model="formData.isCorrectPatientName"
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
                        v-model="formData.isWithin24hrsFever"
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
                        v-model="formData.isWithin24hrsFever"
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
                        v-model="formData.isCorrectBloodComponent"
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
                        v-model="formData.isCorrectBloodComponent"
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
                        v-model="formData.isCorrectBloodTransfusionRec"
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
                        v-model="formData.isCorrectBloodTransfusionRec"
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
                        v-model="formData.isCorrectBloodBagNumber"
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
                        v-model="formData.isCorrectBloodBagNumber"
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
                        v-model="formData.isCorrectBloodBagNumber"
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
                        v-model="formData.isCorrectBloodBagNumber"
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
                        v-model="formData.isCorrectBloodBagNumber"
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
                        v-model="formData.isCorrectBloodBagNumber"
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
        <div class="card" style="border: 0px">
          <div class="row">
            <div class="col-md-12">
              <p class="fontTopicBox" style="margin-top: 8px">
                Vital signs
              </p>
            </div>
          </div>
        </div>
        <button type="submit">Submit</button>
      </div>
    </form>
  </div>
</template>
<style scoped>
.fontSize_header {
  font-size: 1.7rem;
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
  /* background-color: rgba(213, 224, 224, 100%); */
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'%3E%3Cpath d='M12 2C6.486 2 2 6.486 2 12C2 17.514 6.486 22 12 22C17.514 22 22 17.514 22 12C22 6.486 17.514 2 12 2ZM12 16.414L6.293 10.707L7.707 9.293L12 13.586L16.293 9.293L17.707 10.707L12 16.414Z' fill='%2300BFA5'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 8px top 50%;
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
/* .form-select-sm:optional {
  background-color: inherit
} */
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

.form-check-input {
  width: 22px;
  height: 22px;
}
.flexAndSpaceBetween {
  display: flex;
  justify-content: space-between;
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
