<script>
import { defineComponent } from "vue";
import DropDownSVGVue from "../general/DropDownSVG.vue";
import { Icon } from "@iconify/vue";
import { parseDate, parseTime } from "../general/dateUtils"
import axios from "axios";

export default defineComponent({
    name: "TransfusionReport",
    data() {
        return {
            formData: {
                // TR_Report 
                firstName: "",
                lastName: "",
                HN: "",
                createdDate: "",
                createdTime: "",
                ward: "",
                bloodGroup_Patient: "",
                blood_component: "",
                bloodGroup_Donor: "",
                bloodBagNumber: "",
                primaryPhysicianName: "",
                witness: "",
            },
            BloodBagCharacteristic: {
                //BloodBagCharacteristic
                isTransfusionSet: "",
                needleStatus: "",
                plasmaCharacteristicStatus: "",
                isLeakagePosition: "",
                leakagePosition: "",
                volumeOfBag: "",
                TransfusionVolume: "",

            },
            indicator: [{ idIndicatiorName: 0, PreTransfusionSample: "", PostTransfusionSample: "", bloodBagNumber: "", Remarks: "" },
            { idIndicatiorName: 1, PreTransfusionSample: "", PostTransfusionSample: "", bloodBagNumber: "", Remarks: "" },
            { idIndicatiorName: 2, PreTransfusionSample: "", PostTransfusionSample: "", bloodBagNumber: "", Remarks: "" },
            { idIndicatiorName: 3, PreTransfusionSample: "", PostTransfusionSample: "", bloodBagNumber: "", Remarks: "" },
            { idIndicatiorName: 4, PreTransfusionSample: "", PostTransfusionSample: "", bloodBagNumber: "", Remarks: "" },
            { idIndicatiorName: 5, PreTransfusionSample: "", PostTransfusionSample: "", bloodBagNumber: "", Remarks: "" },
            { idIndicatiorName: 6, PreTransfusionSample: "", PostTransfusionSample: "", bloodBagNumber: "", Remarks: "" },
            { idIndicatiorName: 7, PreTransfusionSample: "", PostTransfusionSample: "", bloodBagNumber: "", Remarks: "" },
            { idIndicatiorName: 8, PreTransfusionSample: "", PostTransfusionSample: "", bloodBagNumber: "", Remarks: "" },
            { idIndicatiorName: 9, PreTransfusionSample: "", PostTransfusionSample: "", bloodBagNumber: "", Remarks: "" },
            ],
            userInfo: [],
            tr_form: [],
            baseURL: import.meta.env.VITE_BASE_URL,
        };
    },
    async mounted() {
        const idTR_Form = this.$route.params.id;
        await this.fetchUser();
        await this.fetchTRForm(idTR_Form);
    },
    methods: {
        importData() {
            this.formData.firstName = this.tr_form[0].firstName
            this.formData.lastName = this.tr_form[0].lastName
            this.formData.HN = this.tr_form[0].HN
            this.formData.createdDate = parseDate(this.tr_form[0].createdDate)
            this.formData.createdTime = parseTime(this.tr_form[0].createdDate)
            this.formData.ward = this.tr_form[0].ward
            this.formData.bloodGroup_Patient = this.tr_form[0].bloodGroup_Patient
            this.formData.blood_component = this.tr_form[0].blood_component
            this.formData.bloodGroup_Donor = this.tr_form[0].bloodGroup_Donor
            this.formData.bloodBagNumber = this.tr_form[0].bloodBagNumber
            this.formData.primaryPhysicianName = this.tr_form[0].primaryPhysicianName
        },
        async fetchUser() {
            try {
                const response = await axios.get(this.baseURL + "getUserLogin");
                console.log(" response.data", response.data)
                this.userInfo = response.data;
            } catch (error) {
                console.error("Error fetching List Blood Transfusion data:", error);
            }
        },
        async fetchTRForm(idTR_Form) {
            try {
                const response = await axios.get(this.baseURL + "get-transfusion-form/" + idTR_Form);
                console.log(" response.data", response.data)
                this.tr_form = response.data;
                this.importData()
            } catch (error) {
                console.error("Error fetching List Blood Transfusion data:", error);
            }
        },
        currentDate() {
            const current = new Date();
            const date = current.toLocaleDateString("th-TH", {
                year: "numeric",
                month: "long",
                day: "numeric",
            });
            return date;
        },
        parseDate,
        parseTime,
        handleSubmit() {
            console.log("Form submitted! : ", this.formData);
            console.log("Form submitted! : ", this.BloodBagCharacteristic);
            console.log("Form submitted! : ", this.indicator);
        },
    },
    watch: {
        'BloodBagCharacteristic.isLeakagePosition': function (newVal, oldVal) {
            if (newVal !== 1) {
                this.BloodBagCharacteristic.leakagePosition = "";
            }
        }
    },
    components: {
        DropDownSVGVue,
        Icon,
    },
});
</script>
<template>
    <div class="container-md">
        <form @submit.prevent="handleSubmit">
            <div class="card" style="border: 0px; justify-content: center">
                <!-- header -->
                <div class="row">
                    <div class="col-md-6">
                        <div style="margin-top: 60px">
                            <p class="fontSize_header">
                                รายงานการตรวจการเกิดปฏิกิริยาจากการรับเลือด (Transfusion reaction report)
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6" style="display: flex;">
                        <div style="width: 45%;">
                            <p class="fontTopicBox">HN</p>
                            <div class="card card-box-style">
                                <div class="card-body card-box-body-style">
                                    <!-- HN value -->
                                    <p class="fontInsideBox">
                                        <i class="fa-regular fa-id-card" style="color: #00bfa5"></i>
                                        &nbsp;&nbsp; {{ formData.HN }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div style="width: 55%;">
                            <p class="fontTopicBox">ชื่อผู้ป่วย</p>
                            <div class="card card-box-name">
                                <div class="card-body card-box-body-style">
                                    <p class="fontInsideBox">
                                        &nbsp;{{ formData.firstName + " " + formData.lastName }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--pt1 info read only-->
                <div class="card card-box-info-style">
                    <!-- row 1 -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card-box-info-row-component-style">
                                <Icon icon="bx:calendar-event" style="
                                    width: 14%;
                                    height: 60%;
                                    margin-left: 16px;
                                    margin-top: 18px;
                                " />
                                <div style="display: inline; position: absolute; width: 80%">
                                    <p class="fontTopicInfo" style="margin-left: 16px; margin-top: 7px">
                                        วันที่
                                    </p>
                                    <input class="form-control typing-box-style" style="
                                        padding-right: 16px;
                                        padding-left: 16px;
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " type="text" :value="formData.createdDate" aria-label="readonly input example"
                                        readonly />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card-box-info-row-component-style">
                                <Icon icon="bx:alarm" style="
                                    width: 14%;
                                    height: 60%;
                                    margin-left: 16px;
                                    margin-top: 18px;
                                " />
                                <div style="display: inline; position: absolute">
                                    <p class="fontTopicInfo" style="margin-left: 16px; margin-top: 7px">
                                        เวลา
                                    </p>
                                    <input class="form-control typing-box-style" style="
                                        margin-left: 0px;
                                        margin-right: 16px;
                                        padding-left: 16px;
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " type="text" :value="formData.createdTime" aria-label="readonly input example"
                                        readonly />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 16px; margin-top: 7px">
                                        หอผู้ป่วย
                                    </p>
                                    <div>
                                        <input disabled class="form-select-sm select-box-style" style="
                                            margin-right: 16px;
                                            padding-left: 16px;
                                            padding-top: 0px;
                                            padding-bottom: 0px;
                                        " aria-label="Small select example" v-model="formData.ward">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute">
                                    <p class="fontTopicInfo" style="margin-left: 16px; margin-top: 7px">
                                        หมู่เลือดผู้ป่วย
                                    </p>
                                    <div>
                                        <input disabled class="form-select-sm select-box-style" style="
                                            margin-right: 16px;
                                            padding-left: 16px;
                                            padding-top: 0px;
                                            padding-bottom: 0px;
                                        " aria-label="Small select example" v-model="formData.bloodGroup_Patient">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- row 2 -->
                    <div class="row" style="margin-top: 16px;">
                        <div class="col-md-4 ">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute">
                                    <p class="fontTopicInfo" style="margin-left: 16px; margin-top: 7px">
                                        ชนิดของเลือดที่ให้
                                    </p>
                                    <div>
                                        <input disabled class="form-select-sm select-box-style" style="
                                            margin-right: 16px;
                                            padding-left: 16px;
                                            padding-top: 0px;
                                            padding-bottom: 0px;
                                        " aria-label="Small select example" v-model="formData.blood_component">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute">
                                    <p class="fontTopicInfo" style="margin-left: 16px; margin-top: 7px">
                                        หมู่เลือดผู้บริจาค
                                    </p>
                                    <div>
                                        <input disabled class="form-select-sm select-box-style" style="
                                            margin-right: 16px;
                                            padding-left: 16px;
                                            padding-top: 0px;
                                            padding-bottom: 0px;
                                        " aria-label="Small select example" v-model="formData.bloodGroup_Donor">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute">
                                    <p class="fontTopicInfo" style="margin-left: 16px; margin-top: 7px">
                                        หมายเลขถุงเลือด
                                    </p>
                                    <div>
                                        <input disabled class="form-select-sm select-box-style" style="
                                            margin-right: 16px;
                                            padding-left: 16px;
                                            padding-top: 0px;
                                            padding-bottom: 0px;
                                        " aria-label="Small select example" v-model="formData.bloodBagNumber">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- row 3 -->
                    <div class="row" style="margin-top: 16px;">
                        <div class="col-md-6 ">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute ">
                                    <p class="fontTopicInfo" style="margin-left: 16px; margin-top: 7px">
                                        แพทย์ผู้รักษา
                                    </p>
                                    <div>
                                        <input disabled class="form-select-sm select-box-style" style="
                                            margin-right: 16px;
                                            padding-left: 16px;
                                            padding-top: 0px;
                                            padding-bottom: 0px;
                                        " aria-label="Small select example" v-model="formData.primaryPhysicianName">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute">
                                    <p class="fontTopicInfo" style="margin-left: 16px; margin-top: 7px">
                                        แพทย์ / พยาบาลผู้รับทราบ
                                    </p>
                                    <div>
                                        <input disabled class="form-select-sm select-box-style" style="
                                            margin-right: 16px;
                                            padding-left: 16px;
                                            padding-top: 0px;
                                            padding-bottom: 0px;
                                        " aria-label="Small select example" v-model="formData.witness">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--pt2 ลักษณะถุงเลือด-->
                <div class="card card-box-info-style">
                    <!-- header -->
                    <div class="col-md-12">
                        <p class="fontTopicBox" style="margin-top: 8px">
                            ลักษณะถุงเลือด
                        </p>
                    </div>
                    <!-- row 1 -->
                    <div class="row" style="
                        margin-top: 16px;
                        ">
                        <p class="col-md-5 fontTopicCheckBox">
                            1. มี Transfusion set เสียบอยู่ที่ถุงเลือด
                        </p>
                        <div class="col-md-2 ">
                            <input class="form-check-input" type="radio" name="isTransfusionSet" id="inlineRadio1" value="1"
                                v-model="BloodBagCharacteristic.isTransfusionSet" />
                            <label class="form-check-label" for="inlineRadio1" style="margin-top: 2px">มี</label>
                        </div>
                        <div class="col-md-3 form-check-inline">
                            <input class="form-check-input" type="radio" name="isTransfusionSet" id="inlineRadio2" value="0"
                                v-model="BloodBagCharacteristic.isTransfusionSet" />
                            <label class="form-check-label" for="inlineRadio2" style="margin-top: 2px">ไม่มี</label>
                        </div>
                    </div>
                    <!-- row 2 -->
                    <div class="row" style="
                        margin-top: 16px;
                        ">
                        <p class="col-md-5 fontTopicCheckBox">
                            2. มี Needle ติดที่ปลาย Transfusion set
                        </p>
                        <div class="col-md-2 ">
                            <input class="form-check-input" type="radio" name="needleStatus" id="inlineRadio1" value="1"
                                v-model="BloodBagCharacteristic.needleStatus" />
                            <label class="form-check-label" for="inlineRadio1" style="margin-top: 2px">มี ปิดสนิท</label>
                        </div>
                        <div class="col-md-3 ">
                            <input class="form-check-input" type="radio" name="needleStatus" id="inlineRadio2" value="2"
                                v-model="BloodBagCharacteristic.needleStatus" />
                            <label class="form-check-label" for="inlineRadio2" style="margin-top: 2px">มี ปิดไม่สนิท</label>
                        </div>
                        <div class="col-md-2">
                            <input class="form-check-input" type="radio" name="needleStatus" id="inlineRadio3" value="0"
                                v-model="BloodBagCharacteristic.needleStatus" />
                            <label class="form-check-label" for="inlineRadio2" style="margin-top: 2px">ไม่มี</label>
                        </div>
                    </div>
                    <!-- row 3 -->
                    <div class="row" style="
                        margin-top: 16px;
                        ">
                        <p class="col-md-5 fontTopicCheckBox">
                            3. ลักษณะของพลาสมา
                        </p>
                        <div class="col-md-2 ">
                            <input class="form-check-input" type="radio" name="plasmaCharacteristicStatus" id="inlineRadio1"
                                value="1" v-model="BloodBagCharacteristic.plasmaCharacteristicStatus" />
                            <label class="form-check-label" for="inlineRadio1" style="margin-top: 2px">มี Fibrin</label>
                        </div>
                        <div class="col-md-3 ">
                            <input class="form-check-input" type="radio" name="plasmaCharacteristicStatus" id="inlineRadio2"
                                value="2" v-model="BloodBagCharacteristic.plasmaCharacteristicStatus" />
                            <label class="form-check-label" for="inlineRadio2" style="margin-top: 2px">ใส</label>
                        </div>
                        <div class="col-md-2">
                            <input class="form-check-input" type="radio" name="plasmaCharacteristicStatus" id="inlineRadio3"
                                value="3" v-model="BloodBagCharacteristic.plasmaCharacteristicStatus" />
                            <label class="form-check-label" for="inlineRadio3" style="margin-top: 2px">ขุ่น</label>
                        </div>
                    </div>
                    <!-- row 4 -->
                    <div class="row" style="
                        margin-top: 16px;
                        ">
                        <p class="col-md-5 fontTopicCheckBox">
                            4. ตำแหน่งที่เกิดรอยรั่ว
                        </p>
                        <div class="col-md-1 ">
                            <input class="form-check-input" type="radio" name="isLeakagePosition" id="inlineRadio1"
                                value="1" v-model="BloodBagCharacteristic.isLeakagePosition" />
                            <label class="form-check-label" for="inlineRadio1" style="margin-top: 2px">มี </label>
                        </div>
                        <div class="col-md-4">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100% ">
                                    <p class="fontTopicInfo" style="margin-left: 16px;">
                                        บริเวณ
                                    </p>
                                    <input class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                        v-model="BloodBagCharacteristic.leakagePosition"
                                        :disabled="parseInt(BloodBagCharacteristic.isLeakagePosition) !== 1" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <input class="form-check-input" type="radio" name="isLeakagePosition" id="inlineRadio2"
                                value="0" v-model="BloodBagCharacteristic.isLeakagePosition" />
                            <label class="form-check-label" for="inlineRadio2" style="margin-top: 2px">ไม่มี</label>
                        </div>
                    </div>
                    <!-- row 5 -->
                    <div class="row" style="
                        margin-top: 16px;
                        ">
                        <p class="col-md-5 fontTopicCheckBox">
                            5. ปริมาตรของเลือด
                        </p>
                        <div class="col-md-3">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 16px;">
                                        เหลือในถุง
                                    </p>
                                    <input class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                        v-model="BloodBagCharacteristic.volumeOfBag" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 16px;">
                                        เติมให้ผู้ป่วย
                                    </p>
                                    <input class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                        v-model="BloodBagCharacteristic.TransfusionVolume" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--pt3 Indicators-->
                <div class="card card-box-info-style">
                    <!-- header -->
                    <div class="col-md-12">
                        <p class="fontTopicBox" style="margin-top: 8px">
                            Indicators
                        </p>
                    </div>
                    <!-- row 1 -->
                    <div class="row" style="margin-top: 16px; align-items: center;">
                        <p class="indicators-box-name fontTopicCheckBox" style="margin: 0;">
                            1. CLerical Error
                        </p>
                        <div class="indicators-box">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Pre transfudion sample
                                    </p>
                                    <input class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                        v-model="indicator[0].PreTransfusionSample" />
                                </div>
                            </div>
                        </div>
                        <div class="indicators-box">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Post transfusion sample
                                    </p>
                                    <input class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                        v-model="indicator[0].PostTransfusionSample" />
                                </div>
                            </div>
                        </div>
                        <div class="indicators-box-space">
                        </div>
                        <div class="indicators-box-margin">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Blood bag
                                    </p>
                                    <input class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example" v-model="indicator[0].bloodBagNumber" />
                                </div>
                            </div>
                        </div>
                        <div class="indicators-box-margin">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Remarks
                                    </p>
                                    <input class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example" v-model="indicator[0].Remarks" />
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- row 2 -->
                    <div class="row" style="margin-top: 16px; align-items: center;">
                        <p class="indicators-box-name fontTopicCheckBox" style="margin: 0;">
                            2. Colour of plasma or serum
                        </p>
                        <div class="indicators-box">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Pre transfudion sample
                                    </p>
                                    <input class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                        v-model="indicator[0].PreTransfusionSample" />
                                </div>
                            </div>
                        </div>
                        <div class="indicators-box">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Post transfusion sample
                                    </p>
                                    <input class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                        v-model="indicator[0].PostTransfusionSample" />
                                </div>
                            </div>
                        </div>
                        <div class="indicators-box-space">
                        </div>
                        <div class="indicators-box-margin">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Blood bag
                                    </p>
                                    <input class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example" v-model="indicator[0].bloodBagNumber" />
                                </div>
                            </div>
                        </div>
                        <div class="indicators-box-margin">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Remarks
                                    </p>
                                    <input class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example" v-model="indicator[0].Remarks" />
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!--pt4 Gram stain&Culture-->
                <div class="card card-box-info-style">
                    <!-- row 1 -->
                    <div class="row" style="margin-top: 16px; ">
                        <p class=" fontTopicCheckBox col-md-2">
                            ผล Gram stain
                        </p>
                        <div class="col-md-3">
                            <input class="form-check-input" type="radio" name="isCorrectPatientName" id="inlineRadio1"
                                value="1" v-model="formData.isCorrectPatientName" />
                            <label class="form-check-label" for="inlineRadio1" style="margin-top: 2px">ส่งทำ Gram
                                stain</label>
                        </div>
                        <div class="col-md-2 ">
                            <input class="form-check-input" type="radio" name="isCorrectPatientName" id="inlineRadio2"
                                value="0" v-model="formData.isCorrectPatientName" />
                            <label class="form-check-label" for="inlineRadio2" style="margin-top: 2px">Negative</label>
                        </div>
                        <div class="col-md-2 ">
                            <input class="form-check-input" type="radio" name="isCorrectPatientName" id="inlineRadio2"
                                value="0" v-model="formData.isCorrectPatientName" />
                            <label class="form-check-label" for="inlineRadio2" style="margin-top: 2px">Positive</label>
                        </div>
                        <div class="col-md-3">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 16px;">
                                        ผล
                                    </p>
                                    <input class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example" v-model="formData.Rh_Patient" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- row 2 -->
                    <div class="row" style="margin-top: 16px; justify-content: flex-end">
                        <div class="col-md-3 ">
                            <input class="form-check-input" type="radio" name="isCorrectPatientName" id="inlineRadio2"
                                value="0" v-model="formData.isCorrectPatientName" />
                            <label class="form-check-label" for="inlineRadio2" style="margin-top: 2px">รอผลออกวันที่</label>
                        </div>
                        <div class="col-md-4">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <div class="card-box-info-row-component-style">
                                        <Icon icon="bx:calendar-event" style="
                                            width: 14%;
                                            height: 60%;
                                            margin-left: 16px;
                                            margin-top: 18px;
                                        " />
                                        <div style="display: inline; position: absolute; width: 80%">
                                            <p class="fontTopicInfo" style="margin-left: 16px; margin-top: 7px">
                                                วันที่
                                            </p>
                                            <input class="form-control typing-box-style" style="
                                        padding-right: 16px;
                                        padding-left: 16px;
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " type="text" :value="currentDate()" aria-label="readonly input example"
                                                readonly />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- row 3 -->
                    <div class="row" style="margin-top: 16px; ">
                        <p class=" col-md-2">
                        </p>
                        <div class="col-md-9 ">
                            <input class="form-check-input" type="radio" name="isCorrectPatientName" id="inlineRadio2"
                                value="0" v-model="formData.isCorrectPatientName" />
                            <label class="form-check-label" for="inlineRadio2" style="margin-top: 2px">Not done</label>
                        </div>
                    </div>
                </div>
                <!--pt5 Interpretation-->
                <div class="card card-box-info-style">
                    <div class="card-box-info-row-component-style" style="margin-top: 16px; height: 96px;">
                        <div style="display: inline; position: absolute; width: 100%">
                            <p class="fontTopicInfo" style="margin-left: 16px;">
                                Interpretation
                            </p>
                            <textarea class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        height: 72px;
                                        " aria-label="default input example" v-model="formData.Rh_Patient">
                            </textarea>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 16px;">
                        <div class="col-md-6">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 16px;">
                                        Test by
                                    </p>
                                    <input class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example" v-model="formData.Rh_Patient" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card-box-info-row-component-style">
                                <Icon icon="bx:calendar-event" style="
                                    width: 14%;
                                    height: 60%;
                                    margin-left: 16px;
                                    margin-top: 18px;
                                " />
                                <div style="display: inline; position: absolute; width: 80%">
                                    <p class="fontTopicInfo" style="margin-left: 16px; margin-top: 7px">
                                        วันที่
                                    </p>
                                    <input class="form-control typing-box-style" style="
                                        padding-right: 16px;
                                        padding-left: 16px;
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " type="text" :value="currentDate()" aria-label="readonly input example"
                                        readonly />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card-box-info-row-component-style">
                                <Icon icon="bx:alarm" style="
                                    width: 14%;
                                    height: 60%;
                                    margin-left: 16px;
                                    margin-top: 18px;
                                " />
                                <div style="display: inline; position: absolute">
                                    <p class="fontTopicInfo" style="margin-left: 16px; margin-top: 7px">
                                        เวลา
                                    </p>
                                    <input class="form-control typing-box-style" style="
                                        margin-left: 0px;
                                        margin-right: 16px;
                                        padding-left: 16px;
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " type="text"  aria-label="readonly input example"
                                        readonly />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--button-->
                <div class="card" style="border: 0px">
                    <div style="display: flex; justify-content: flex-end; gap: 2%">
                        <button class="btn button-style-close" style="margin-top: 32px">
                            ปิด
                        </button>
                        <button class="btn button-style-save" style="margin-top: 32px" type="submit">
                            บันทึก
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<style scoped>
input,
p,
button,
div {
    font-family: "IBM Plex Sans Thai";
}

.fontSize_header {
    font-size: 1.7rem;
    font-weight: 700;
}

.fontTopicBox {
    font-size: 1.2rem;
    font-weight: 600;
    margin-top: 30px;
    margin-bottom: 0;
    color: #3c3c3c;
}

.fontInsideBox {
    font-size: 1.1rem;
    font-weight: 400;
    color: #c4c4c4;
}

.fontTopicInfo {
    font-weight: 700;
    font-size: 0.9rem;
    color: #202124;
    display: inline;
}

.fontTopicCheckBox {
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

.container-md {
    margin-left: auto;
    margin-right: auto;
    margin-bottom: 24px;
}

.card-box-info-style {
    margin-top: 16px;
    width: auto;
    border: 0;
}

.card-box-style {
    width: 80%;
    height: 48px;
    border: 2px solid #dee0e6;
    border-radius: 8px;
    background-color: #fbfbfc;
}

.card-box-name {
    width: 100%;
    height: 48px;
    border: 2px solid #dee0e6;
    border-radius: 8px;
    background-color: #fbfbfc;
}

.card-box-body-style {
    width: 100%;
    height: 48px;
    padding: 12px 0px 0px 8px;
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

.button-style-save {
    width: 16%;
    height: 44px;
    border-radius: 100px;
    background-color: rgba(0, 191, 165, 1);
    font-size: 1.2rem;
    font-weight: 600;
    margin-top: 30px;
    margin-bottom: 0;
    color: rgba(255, 255, 255, 1);
    transition: background-color 0.3s ease, color 0.3s ease;
}

.button-style-save:hover {
    background-color: rgba(0, 191, 165, 0.8);
    color: rgba(255, 255, 255, 0.9);
}

.button-style-close {
    width: 16%;
    height: 44px;
    border-radius: 100px;
    background-color: transparent;
    border: 2px solid rgba(0, 191, 165, 1);
    font-size: 1.2rem;
    font-weight: 600;
    margin-top: 30px;
    margin-bottom: 0;
    color: rgba(255, 59, 48, 1);
    transition: background-color 0.3s ease, color 0.3s ease;
}

.button-style-close:hover {
    background-color: rgba(255, 59, 48, 1);
    color: rgba(255, 255, 255, 1);
}

.indicators-box {
    width: 20%;
}

.indicators-box-space {
    width: 0%;
    padding: 0;
}

.indicators-box-name {
    width: 20%;
}

.indicators-box-margin {
    width: 20%;
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

    .indicators-box {
        width: 33%;
    }

    .indicators-box-margin {
        width: 33%;
        margin-top: 16px;
    }

    .indicators-box-space {
        width: 33%;
    }

    .indicators-box-name {
        width: 33%;
    }

}
</style>
