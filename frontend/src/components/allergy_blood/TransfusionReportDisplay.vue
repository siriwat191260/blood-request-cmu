<script>
import { defineComponent } from "vue";
import DropDownSVGVue from "../general/DropDownSVG.vue";
import { Icon } from "@iconify/vue";
import {
    parseDate,
    parseTime,
    currentDate,
    currentTime,
} from "../general/dateUtils"
import axios from "axios";

export default defineComponent({
    name: "TransfusionReport",
    data() {
        return {
            formData: {
                data: {
                    title: "",
                    firstName: "",
                    lastName: "",
                    HN: "",
                    createdDate: "",
                    createdTime: "",
                    createdDateTime: "",
                    ward: "",
                    bloodGroup_Patient: "",
                    blood_component: "",
                    bloodGroup_Donor: "",
                    bloodBagNumber: "",
                    primaryPhysicianName: "",
                    nurse: "",
                    interpretation: "",
                    testedBy: "",
                    testedDate: "",
                    testedTime: "",
                    reportedBy: "",
                    reportedDate: "",
                    reportedTime: "",
                },
                BloodBagCharacteristic: {
                    isTransfusionSet: "",
                    needleStatus: "",
                    plasmaCharacteristicStatus: "",
                    isLeakagePosition: "",
                    leakagePosition: "",
                    volumeOfBag: "",
                    TransfusionVolume: "",
                },
                indicator: [{ idIndicatorName: 0, PreTransfusionSample: "", PostTransfusionSample: "", bloodBagNumber: "", Remarks: "" },
                { idIndicatorName: 1, PreTransfusionSample: "", PostTransfusionSample: "", bloodBagNumber: "", Remarks: "" },
                { idIndicatorName: 2, PreTransfusionSample: "", PostTransfusionSample: "", bloodBagNumber: "", Remarks: "" },
                { idIndicatorName: 3, PreTransfusionSample: "", PostTransfusionSample: "", bloodBagNumber: "", Remarks: "" },
                { idIndicatorName: 4, PreTransfusionSample: "", PostTransfusionSample: "", bloodBagNumber: "", Remarks: "" },
                { idIndicatorName: 5, PreTransfusionSample: "", PostTransfusionSample: "", bloodBagNumber: "", Remarks: "" },
                { idIndicatorName: 6, PreTransfusionSample: "", PostTransfusionSample: "", bloodBagNumber: "", Remarks: "" },
                { idIndicatorName: 7, PreTransfusionSample: "", PostTransfusionSample: "", bloodBagNumber: "", Remarks: "" },
                { idIndicatorName: 8, PreTransfusionSample: "", PostTransfusionSample: "", bloodBagNumber: "", Remarks: "" },
                { idIndicatorName: 9, PreTransfusionSample: "", PostTransfusionSample: "", bloodBagNumber: "", Remarks: "" },
                ],
                GramStainAndCulture: {
                    isSubmittingGramStain: "",
                    gramNegativeOrPositive: "",
                    resultGramStain: "",
                    toDateGram: "",
                    isSubmittingCulture: "",
                    cultureNegativeOrPositive: "",
                    resultCulture: "",
                    toDateCulture: "",
                },
            },
            showResultsTest: false,
            showResultsReport: false,
            userInfo: [],
            tr_report: {},
            userBloodbank: {},
            baseURL: import.meta.env.VITE_BASE_URL,
        };
    },
    async mounted() {
        const idTR_Report = this.$route.params.id;
        await this.fetchTRReport(idTR_Report);
    },
    methods: {
        importData() {
            this.formData.data.title = this.tr_report.data.title
            this.formData.data.firstName = this.tr_report.data.firstName
            this.formData.data.lastName = this.tr_report.data.lastName
            this.formData.data.HN = this.tr_report.data.HN
            this.formData.data.createdDate = new Date(this.tr_report.data.createdDate).toISOString().split("T")[0];
            this.formData.data.createdTime = parseTime(new Date(this.tr_report.data.createdDate));
            this.formData.data.createdDateTime = this.tr_report.data.createdDate
            this.formData.data.ward = this.tr_report.data.ward
            this.formData.data.bloodGroup_Patient = this.tr_report.data.bloodGroup_Patient
            this.formData.data.blood_component = this.tr_report.data.blood_component
            this.formData.data.bloodGroup_Donor = this.tr_report.data.bloodGroup_Donor
            this.formData.data.bloodBagNumber = this.tr_report.data.bloodBagNumber
            this.formData.data.primaryPhysicianName = this.tr_report.data.primaryPhysicianName
            this.formData.data.nurse = this.tr_report.data.nurse
            this.formData.data.interpretation = this.tr_report.data.interpretation
            this.formData.data.testedBy = this.tr_report.data.testedBy ? this.tr_report.data.testedBy : ""
            this.formData.data.reportedBy = this.tr_report.data.reportedBy ? this.tr_report.data.reportedBy : ""
            this.formData.data.testedDate = this.tr_report.data.testedDateTime ? new Date(this.tr_report.data.testedDateTime).toISOString().split("T")[0] : ""
            this.formData.data.testedTime = this.tr_report.data.testedDateTime ? parseTime(new Date(this.tr_report.data.testedDateTime)) : ""
            this.formData.data.reportedDate = this.tr_report.data.reportedDateTime ? new Date(this.tr_report.data.reportedDateTime).toISOString().split("T")[0] : ""
            this.formData.data.reportedTime = this.tr_report.data.reportedDateTime ? parseTime(new Date(this.tr_report.data.reportedDateTime)) : ""
            //BloodBagCharacteristic
            this.formData.BloodBagCharacteristic.isTransfusionSet = this.tr_report.BloodBagCharacteristic.isTransfusionSet
            this.formData.BloodBagCharacteristic.needleStatus = this.tr_report.BloodBagCharacteristic.needleStatus
            this.formData.BloodBagCharacteristic.plasmaCharacteristicStatus = this.tr_report.BloodBagCharacteristic.plasmaCharacteristicStatus
            this.formData.BloodBagCharacteristic.isLeakagePosition = this.tr_report.BloodBagCharacteristic.isLeakagePosition
            this.formData.BloodBagCharacteristic.leakagePosition = this.tr_report.BloodBagCharacteristic.leakagePosition
            this.formData.BloodBagCharacteristic.volumeOfBag = this.tr_report.BloodBagCharacteristic.volumeOfBag
            this.formData.BloodBagCharacteristic.TransfusionVolume = this.tr_report.BloodBagCharacteristic.TransfusionVolume
            //indicator
            this.formData.indicator[0].PreTransfusionSample = this.tr_report.indicator[0].PreTransfusionSample
            this.formData.indicator[0].PostTransfusionSample = this.tr_report.indicator[0].PostTransfusionSample
            this.formData.indicator[0].bloodBagNumber = this.tr_report.indicator[0].bloodBagNumber
            this.formData.indicator[0].Remarks = this.tr_report.indicator[0].Remarks

            this.formData.indicator[1].PreTransfusionSample = this.tr_report.indicator[1].PreTransfusionSample
            this.formData.indicator[1].PostTransfusionSample = this.tr_report.indicator[1].PostTransfusionSample
            this.formData.indicator[1].bloodBagNumber = this.tr_report.indicator[1].bloodBagNumber
            this.formData.indicator[1].Remarks = this.tr_report.indicator[1].Remarks

            this.formData.indicator[2].PreTransfusionSample = this.tr_report.indicator[2].PreTransfusionSample
            this.formData.indicator[2].PostTransfusionSample = this.tr_report.indicator[2].PostTransfusionSample
            this.formData.indicator[2].bloodBagNumber = this.tr_report.indicator[2].bloodBagNumber
            this.formData.indicator[2].Remarks = this.tr_report.indicator[2].Remarks

            this.formData.indicator[3].PreTransfusionSample = this.tr_report.indicator[3].PreTransfusionSample
            this.formData.indicator[3].PostTransfusionSample = this.tr_report.indicator[3].PostTransfusionSample
            this.formData.indicator[3].bloodBagNumber = this.tr_report.indicator[3].bloodBagNumber
            this.formData.indicator[3].Remarks = this.tr_report.indicator[3].Remarks

            this.formData.indicator[4].PreTransfusionSample = this.tr_report.indicator[4].PreTransfusionSample
            this.formData.indicator[4].PostTransfusionSample = this.tr_report.indicator[4].PostTransfusionSample
            this.formData.indicator[4].bloodBagNumber = this.tr_report.indicator[4].bloodBagNumber
            this.formData.indicator[4].Remarks = this.tr_report.indicator[4].Remarks

            this.formData.indicator[5].PreTransfusionSample = this.tr_report.indicator[5].PreTransfusionSample
            this.formData.indicator[5].PostTransfusionSample = this.tr_report.indicator[5].PostTransfusionSample
            this.formData.indicator[5].bloodBagNumber = this.tr_report.indicator[5].bloodBagNumber
            this.formData.indicator[5].Remarks = this.tr_report.indicator[5].Remarks

            this.formData.indicator[6].PreTransfusionSample = this.tr_report.indicator[6].PreTransfusionSample
            this.formData.indicator[6].PostTransfusionSample = this.tr_report.indicator[6].PostTransfusionSample
            this.formData.indicator[6].bloodBagNumber = this.tr_report.indicator[6].bloodBagNumber
            this.formData.indicator[6].Remarks = this.tr_report.indicator[6].Remarks

            this.formData.indicator[7].PreTransfusionSample = this.tr_report.indicator[7].PreTransfusionSample
            this.formData.indicator[7].PostTransfusionSample = this.tr_report.indicator[7].PostTransfusionSample
            this.formData.indicator[7].bloodBagNumber = this.tr_report.indicator[7].bloodBagNumber
            this.formData.indicator[7].Remarks = this.tr_report.indicator[7].Remarks

            this.formData.indicator[8].PreTransfusionSample = this.tr_report.indicator[8].PreTransfusionSample
            this.formData.indicator[8].PostTransfusionSample = this.tr_report.indicator[8].PostTransfusionSample
            this.formData.indicator[8].bloodBagNumber = this.tr_report.indicator[8].bloodBagNumber
            this.formData.indicator[8].Remarks = this.tr_report.indicator[8].Remarks

            this.formData.indicator[9].PreTransfusionSample = this.tr_report.indicator[9].PreTransfusionSample
            this.formData.indicator[9].PostTransfusionSample = this.tr_report.indicator[9].PostTransfusionSample
            this.formData.indicator[9].bloodBagNumber = this.tr_report.indicator[9].bloodBagNumber
            this.formData.indicator[9].Remarks = this.tr_report.indicator[9].Remarks

            this.formData.GramStainAndCulture.isSubmittingGramStain = this.tr_report.GramStainAndCulture.isSubmittingGramStain
            this.formData.GramStainAndCulture.gramNegativeOrPositive = this.tr_report.GramStainAndCulture.gramNegativeOrPositive
            this.formData.GramStainAndCulture.resultGramStain = this.tr_report.GramStainAndCulture.resultGramStain
            this.formData.GramStainAndCulture.toDateGram = this.tr_report.GramStainAndCulture.toDateGram

            this.formData.GramStainAndCulture.isSubmittingCulture = this.tr_report.GramStainAndCulture.isSubmittingCulture
            this.formData.GramStainAndCulture.cultureNegativeOrPositive = this.tr_report.GramStainAndCulture.cultureNegativeOrPositive
            this.formData.GramStainAndCulture.resultCulture = this.tr_report.GramStainAndCulture.resultCulture
            this.formData.GramStainAndCulture.toDateCulture = this.tr_report.GramStainAndCulture.toDateCulture

        },
        async fetchTRReport(idTR_Report) {
            try {
                const response = await axios.get(this.baseURL + "get-transfusion-report/" + idTR_Report);
                this.tr_report = response.data;
                this.importData()
            } catch (error) {
                console.error("Error fetching List Blood Transfusion data:", error);
            }
        },
        currentDate,
        currentTime,
        parseDate,
        parseTime,
        navigateToPreviousPage() {
            this.$router.push(`/mainBloodChecklist`);
            console.log("click")
            $('#CloseButton').modal('hide');
        },
    },
    components: {
        DropDownSVGVue,
        Icon,
    },
    computed: {
        HNWidth() {
        return () => {
            const name =
            this.formData.data.title +
            " " +
            this.formData.data.firstName +
            " " +
            this.formData.data.lastName;
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
            this.formData.data.title +
            " " +
            this.formData.data.firstName +
            " " +
            this.formData.data.lastName;
            const length = name.length;
            if (length > 20) {
            this.styleName2Line = true;
            return "NameWidth";
            } else {
            return "";
            }
        };
        },
    }
});
</script>
<template>
    <div class="container-md">
        <form>
            <div class="card" style="border: 0px; justify-content: center">
                <!-- header -->
                <div class="row">
                        <div class="col-md-6 vertical-style-100w">
                            <div style="margin-top: 60px">
                                <p class="fontSize_header">
                                    รายงานการตรวจการเกิดปฏิกิริยาจากการรับเลือด  
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3 vertical-style-33w" :class="HNWidth()">
                            <p class="fontTopicBox">HN</p>
                            <div class="card card-box-style">
                                <div class="card-body card-box-body-style">
                                    <!-- HN value -->
                                    <p class="fontInsideBox">
                                        <Icon icon="bx:id-card"
                                            style="color: #00bfa5; width: 32; height: 32; margin-bottom: 4px;"></Icon>
                                        &nbsp; {{ formData.data.HN }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- ชื่อผู้ป่วย -->
                        <div class="col-md-3 vertical-style-66w" :class="NameWidth()">
                            <div>
                                <p class="fontTopicBox">ชื่อผู้ป่วย</p>
                                <div class="card card-box-style">
                                    <div class="card-body card-box-body-style" style="margin-top: 6px;">
                                        <p class="fontInsideBox">
                                            &nbsp;
                                            {{
                                                formData.data.title +
                                                " " +
                                                formData.data.firstName +
                                                " " +
                                                formData.data.lastName
                                            }}
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
                                        " type="text" :value="formData.data.createdDate"
                                        aria-label="readonly input example" readonly />
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
                                        " type="text" :value="formData.data.createdTime"
                                        aria-label="readonly input example" readonly />
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
                                        " aria-label="Small select example"  :value="formData.data.ward">
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
                                        " aria-label="Small select example"  :value="formData.data.bloodGroup_Patient">
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
                                        " aria-label="Small select example"  :value="formData.data.blood_component">
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
                                        " aria-label="Small select example"  :value="formData.data.bloodGroup_Donor">
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
                                        " aria-label="Small select example"  :value="formData.data.bloodBagNumber">
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
                                        " aria-label="Small select example"
                                             :value="formData.data.primaryPhysicianName">
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
                                        " aria-label="Small select example"  :value="formData.data.nurse">
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
                            <input :disabled="formData.BloodBagCharacteristic.isTransfusionSet !== 1"
                                class="form-check-input" type="radio" name="isTransfusionSet" id="isTransfusionSet1"
                                :checked="formData.BloodBagCharacteristic.isTransfusionSet === 1" />
                            <label class="form-check-label" for="isTransfusionSet1" style=" margin-top: 2px; margin-left: 10px">มี</label>
                        </div>
                        <div class="col-md-3 form-check-inline">
                            <input :disabled="formData.BloodBagCharacteristic.isTransfusionSet !== 0"
                                class="form-check-input" type="radio" name="isTransfusionSet" id="isTransfusionSet2"
                                :checked="formData.BloodBagCharacteristic.isTransfusionSet !== 1" />
                            <label class="form-check-label" for="isTransfusionSet2" style=" margin-top: 2px; margin-left: 10px">ไม่มี</label>
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
                            <input class="form-check-input" type="radio" name="needleStatus" id="needleStatus1"
                                :disabled="formData.BloodBagCharacteristic.needleStatus !== 1"
                                :checked="formData.BloodBagCharacteristic.needleStatus == 1" />
                            <label class="form-check-label" for="needleStatus1" style=" margin-top: 2px; margin-left: 10px">มี ปิดสนิท</label>
                        </div>
                        <div class="col-md-3 ">
                            <input class="form-check-input" type="radio" name="needleStatus" id="needleStatus2"
                                :disabled="formData.BloodBagCharacteristic.needleStatus !== 2"
                                :checked="formData.BloodBagCharacteristic.needleStatus == 2" />
                            <label class="form-check-label" for="needleStatus2" style=" margin-top: 2px; margin-left: 10px">มี ปิดไม่สนิท</label>
                        </div>
                        <div class="col-md-2">
                            <input class="form-check-input" type="radio" name="needleStatus" id="needleStatus3"
                                :disabled="formData.BloodBagCharacteristic.needleStatus !== 3"
                                :checked="formData.BloodBagCharacteristic.needleStatus == 3" />
                            <label class="form-check-label" for="needleStatus3" style=" margin-top: 2px; margin-left: 10px">ไม่มี</label>
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
                            <input class="form-check-input" type="radio" name="plasmaCharacteristicStatus"
                                id="plasmaCharacteristicStatus1" :disabled="formData.BloodBagCharacteristic.plasmaCharacteristicStatus !== 1"
                                :checked="formData.BloodBagCharacteristic.plasmaCharacteristicStatus === 1 " />
                            <label class="form-check-label" for="plasmaCharacteristicStatus1" style=" margin-top: 2px; margin-left: 10px">มี Fibrin</label>
                        </div>
                        <div class="col-md-3 ">
                            <input class="form-check-input" type="radio" name="plasmaCharacteristicStatus"
                                id="plasmaCharacteristicStatus2" :disabled="formData.BloodBagCharacteristic.plasmaCharacteristicStatus !== 2"
                                :checked="formData.BloodBagCharacteristic.plasmaCharacteristicStatus === 2" />
                            <label class="form-check-label" for="plasmaCharacteristicStatus2" style=" margin-top: 2px; margin-left: 10px">ใส</label>
                        </div>
                        <div class="col-md-2">
                            <input class="form-check-input" type="radio" name="plasmaCharacteristicStatus"
                                id="plasmaCharacteristicStatus3" :disabled="formData.BloodBagCharacteristic.plasmaCharacteristicStatus !== 3"
                                :checked="formData.BloodBagCharacteristic.plasmaCharacteristicStatus === 3" />
                            <label class="form-check-label" for="plasmaCharacteristicStatus3" style=" margin-top: 2px; margin-left: 10px">ขุ่น</label>
                        </div>
                    </div>
                    <!-- row 4 -->
                    <div class="row" style="
                        margin-top: 16px;
                        ">
                        <p class="col-md-5 fontTopicCheckBox">
                            4. ตำแหน่งที่เกิดรอยรั่ว
                        </p>
                        <div class="col-md-1" style="padding-right: 0px">
                            <input class="form-check-input" type="radio" name="isLeakagePosition" id="isLeakagePosition1"
                                :disabled="formData.BloodBagCharacteristic.isLeakagePosition !== 1"
                                :checked="formData.BloodBagCharacteristic.isLeakagePosition == 1" />
                            <label class="form-check-label" for="isLeakagePosition1" style=" margin-top: 2px; margin-left: 10px">มี </label>
                        </div>
                        <div class="col-md-4">
                            <div class="card-box-info-row-component-style"
                                v-if="parseInt(formData.BloodBagCharacteristic.isLeakagePosition) === 1">
                                <div style="display: inline; position: absolute; width: 100% ">
                                    <p class="fontTopicInfo" style="margin-left: 16px;">
                                        บริเวณ
                                    </p>
                                    <input class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                         :value="formData.BloodBagCharacteristic.leakagePosition" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <input class="form-check-input" type="radio" name="isLeakagePosition" id="isLeakagePosition2"
                            :disabled="formData.BloodBagCharacteristic.isLeakagePosition !== 0"
                                :checked="formData.BloodBagCharacteristic.isLeakagePosition == 0" />
                            <label class="form-check-label" for="isLeakagePosition2" style=" margin-top: 2px; margin-left: 10px">ไม่มี</label>
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
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example" type="number" pattern="[0-9]*"
                                        onkeypress="return event.charCode != 45" min="0"
                                         :value="formData.BloodBagCharacteristic.volumeOfBag" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 16px;">
                                        เติมให้ผู้ป่วย
                                    </p>
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example" type="number" pattern="[0-9]*"
                                        onkeypress="return event.charCode != 45" min="0"
                                         :value="formData.BloodBagCharacteristic.TransfusionVolume" />
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
                                        Pre transfusion sample
                                    </p>
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                         :value="formData.indicator[0].PreTransfusionSample" />
                                </div>
                            </div>
                        </div>
                        <div class="indicators-box">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Post transfusion sample
                                    </p>
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                         :value="formData.indicator[0].PostTransfusionSample" />
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
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                         :value="formData.indicator[0].bloodBagNumber" />
                                </div>
                            </div>
                        </div>
                        <div class="indicators-box-margin">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Remarks
                                    </p>
                                    <inputdisabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"  :value="formData.indicator[0].Remarks" />
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
                                        Pre transfusion sample
                                    </p>
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                         :value="formData.indicator[1].PreTransfusionSample" />
                                </div>
                            </div>
                        </div>
                        <div class="indicators-box">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Post transfusion sample
                                    </p>
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                         :value="formData.indicator[1].PostTransfusionSample" />
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
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                         :value="formData.indicator[1].bloodBagNumber" />
                                </div>
                            </div>
                        </div>
                        <div class="indicators-box-margin">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Remarks
                                    </p>
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"  :value="formData.indicator[1].Remarks" />
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- row 3 -->
                    <div class="row" style="margin-top: 16px; align-items: center;">
                        <p class="indicators-box-name fontTopicCheckBox" style="margin: 0;">
                            3. Hemolysis (%)
                        </p>
                        <div class="indicators-box">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Pre transfusion sample
                                    </p>
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                         :value="formData.indicator[2].PreTransfusionSample" />
                                </div>
                            </div>
                        </div>
                        <div class="indicators-box">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Post transfusion sample
                                    </p>
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                         :value="formData.indicator[2].PostTransfusionSample" />
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
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                         :value="formData.indicator[2].bloodBagNumber" />
                                </div>
                            </div>
                        </div>
                        <div class="indicators-box-margin">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Remarks
                                    </p>
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"  :value="formData.indicator[2].Remarks" />
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- row 4 -->
                    <div class="row" style="margin-top: 16px; align-items: center;">
                        <p class="indicators-box-name fontTopicCheckBox" style="margin: 0;">
                            4. Clot investigtion
                        </p>
                        <div class="indicators-box">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Pre transfusion sample
                                    </p>
                                    <input class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example" disabled value="-" />
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
                                        " aria-label="default input example" disabled value="-" />
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
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                         :value="formData.indicator[3].bloodBagNumber" />
                                </div>
                            </div>
                        </div>
                        <div class="indicators-box-margin">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Remarks
                                    </p>
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"  :value="formData.indicator[3].Remarks" />
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- row 5 -->
                    <div class="row" style="margin-top: 16px; align-items: center;">
                        <p class="indicators-box-name fontTopicCheckBox" style="margin: 0;">
                            5. ABO
                        </p>
                        <div class="indicators-box">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Pre transfusion sample
                                    </p>
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                         :value="formData.indicator[4].PreTransfusionSample" />
                                </div>
                            </div>
                        </div>
                        <div class="indicators-box">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Post transfusion sample
                                    </p>
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                         :value="formData.indicator[4].PostTransfusionSample" />
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
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                         :value="formData.indicator[4].bloodBagNumber" />
                                </div>
                            </div>
                        </div>
                        <div class="indicators-box-margin">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Remarks
                                    </p>
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"  :value="formData.indicator[4].Remarks" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- row 6 -->
                    <div class="row" style="margin-top: 16px; align-items: center;">
                        <p class="indicators-box-name fontTopicCheckBox" style="margin: 0;">
                            6. Rh
                        </p>
                        <div class="indicators-box">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Pre transfusion sample
                                    </p>
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                         :value="formData.indicator[5].PreTransfusionSample" />
                                </div>
                            </div>
                        </div>
                        <div class="indicators-box">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Post transfusion sample
                                    </p>
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                         :value="formData.indicator[5].PostTransfusionSample" />
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
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                         :value="formData.indicator[5].bloodBagNumber" />
                                </div>
                            </div>
                        </div>
                        <div class="indicators-box-margin">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Remarks
                                    </p>
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"  :value="formData.indicator[5].Remarks" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- row 7 -->
                    <div class="row" style="margin-top: 16px; align-items: center;">
                        <p class="indicators-box-name fontTopicCheckBox" style="margin: 0;">
                            7. Ab Screening
                        </p>
                        <div class="indicators-box">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Pre transfusion sample
                                    </p>
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                         :value="formData.indicator[6].PreTransfusionSample" />
                                </div>
                            </div>
                        </div>
                        <div class="indicators-box">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Post transfusion sample
                                    </p>
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                         :value="formData.indicator[6].PostTransfusionSample" />
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
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                         :value="formData.indicator[6].bloodBagNumber" />
                                </div>
                            </div>
                        </div>
                        <div class="indicators-box-margin">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Remarks
                                    </p>
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"  :value="formData.indicator[6].Remarks" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- row 8 -->
                    <div class="row" style="margin-top: 16px; align-items: center;">
                        <p class="indicators-box-name fontTopicCheckBox" style="margin: 0;">
                            8. DAT
                        </p>
                        <div class="indicators-box">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Pre transfusion sample
                                    </p>
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                         :value="formData.indicator[7].PreTransfusionSample" />
                                </div>
                            </div>
                        </div>
                        <div class="indicators-box">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Post transfusion sample
                                    </p>
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                         :value="formData.indicator[7].PostTransfusionSample" />
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
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                         :value="formData.indicator[7].bloodBagNumber" />
                                </div>
                            </div>
                        </div>
                        <div class="indicators-box-margin">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Remarks
                                    </p>
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"  :value="formData.indicator[7].Remarks" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- row 9 -->
                    <div class="row" style="margin-top: 16px; align-items: center;">
                        <p class="indicators-box-name fontTopicCheckBox" style="margin: 0;">
                            9. XM
                        </p>
                        <div class="indicators-box">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Pre transfusion sample
                                    </p>
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                         :value="formData.indicator[8].PreTransfusionSample" />
                                </div>
                            </div>
                        </div>
                        <div class="indicators-box">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Post transfusion sample
                                    </p>
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                         :value="formData.indicator[8].PostTransfusionSample" />
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
                                        " aria-label="default input example" disabled value="-" />
                                </div>
                            </div>
                        </div>
                        <div class="indicators-box-margin">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Remarks
                                    </p>
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"  :value="formData.indicator[8].Remarks" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- row 10 -->
                    <div class="row" style="margin-top: 16px; align-items: center;">
                        <p class="indicators-box-name fontTopicCheckBox" style="margin: 0;">
                            10. Culture
                        </p>
                        <div class="indicators-box">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Pre transfusion sample
                                    </p>
                                    <input class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example" disabled value="-" />
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
                                        " aria-label="default input example" disabled value="-" />
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
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                         :value="formData.indicator[9].bloodBagNumber" />
                                </div>
                            </div>
                        </div>
                        <div class="indicators-box-margin">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 10px;">
                                        Remarks
                                    </p>
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"  :value="formData.indicator[9].Remarks" />
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
                            <input class="form-check-input" type="radio" name="isSubmittingGramStain"
                                id="isSubmittingGramStain1" :disabled="formData.GramStainAndCulture.isSubmittingGramStain !== 1"
                                :checked="formData.GramStainAndCulture.isSubmittingGramStain === 1" />
                            <label class="form-check-label" for="isSubmittingGramStain1" style=" margin-top: 2px; margin-left: 10px">ส่งทำ Gram
                                stain</label>
                        </div>
                        <div class="col-md-2 " v-if="parseInt(formData.GramStainAndCulture.isSubmittingGramStain) === 1">
                            <input class="form-check-input" type="radio" name="gramNegativeOrPositive"
                            :disabled="formData.GramStainAndCulture.isSubmittingGramStain !== 0"
                                :checked="formData.GramStainAndCulture.isSubmittingGramStain === 0" />
                            <label class="form-check-label" for="gramNegativeOrPositive1" style=" margin-top: 2px; margin-left: 10px">Negative</label>
                        </div>
                        <div class="col-md-2 " v-if="parseInt(formData.GramStainAndCulture.gramNegativeOrPositive) === 1">
                            <input class="form-check-input" type="radio" name="gramNegativeOrPositive"
                            :disabled="formData.GramStainAndCulture.gramNegativeOrPositive !== 1"
                                :checked="formData.GramStainAndCulture.gramNegativeOrPositive === 1" />
                            <label class="form-check-label" for="gramNegativeOrPositive2" style=" margin-top: 2px; margin-left: 10px">Positive</label>
                        </div>
                        <div class="col-md-3" v-if="parseInt(formData.GramStainAndCulture.gramNegativeOrPositive) === 1">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 16px;">
                                        ผล
                                    </p>
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                         :value="formData.GramStainAndCulture.resultGramStain" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- row 2 -->
                    <div class="row" style="margin-top: 16px; justify-content: flex-end"
                        v-if="parseInt(formData.GramStainAndCulture.gramNegativeOrPositive) === 2">
                        <div class="col-md-3 ">
                            <input class="form-check-input" type="radio" name="gramNegativeOrPositive"
                            :disabled="formData.GramStainAndCulture.gramNegativeOrPositive !== 2"
                                :checked="formData.GramStainAndCulture.gramNegativeOrPositive === 2" />
                            <label class="form-check-label" for="gramNegativeOrPositive3" style=" margin-top: 2px; margin-left: 10px">รอผลออกวันที่</label>
                        </div>
                        <div class="col-md-4">
                            <div class="card-box-info-row-component-style"
                                v-if="parseInt(formData.GramStainAndCulture.gramNegativeOrPositive) === 2">
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
                                    <div style="position: relative">
                                        <div style="display: inline; position: absolute; width: 100%">
                                            <input disabled class="form-control typing-box-style" style="
                                                padding-left: 16px;
                                                padding-right: 16px;
                                                padding-top: 0px;
                                                padding-bottom: 0px;
                                                " type="date"  :value="formData.GramStainAndCulture.toDateGram"
                                                aria-label="readonly input example" id="toDateGram" name="toDateGram" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- row 3 -->
                    <div class="row" style="margin-top: 16px; "  v-if="parseInt(formData.GramStainAndCulture.isSubmittingGramStain) === 0">
                        <p class=" col-md-2">
                        </p>
                        <div class="col-md-9 ">
                            <input class="form-check-input" type="radio" name="isSubmittingGramStain"
                            :disabled="formData.GramStainAndCulture.isSubmittingGramStain !== 0"
                                :checked="formData.GramStainAndCulture.isSubmittingGramStain === 0" />
                            <label class="form-check-label" for="isSubmittingGramStain2" style=" margin-top: 2px; margin-left: 10px">Not done</label>
                        </div>
                    </div>
                </div>
                <div class="card card-box-info-style">
                    <!-- row 1 -->
                    <div class="row" style="margin-top: 16px; ">
                        <p class=" fontTopicCheckBox col-md-2">
                            ผล Culture
                        </p>
                        <div class="col-md-3">
                            <input class="form-check-input" type="radio" name="isSubmittingCulture"
                            :disabled="formData.GramStainAndCulture.isSubmittingCulture !== 1"
                                :checked="formData.GramStainAndCulture.isSubmittingCulture === 1" />
                            <label class="form-check-label" for="isSubmittingCulture1" style=" margin-top: 2px; margin-left: 10px">ส่งทำ Culture</label>
                        </div>
                        <div class="col-md-2 " v-if="parseInt(formData.GramStainAndCulture.isSubmittingCulture) === 1">
                            <input class="form-check-input" type="radio" name="cultureNegativeOrPositive"
                            :disabled="formData.GramStainAndCulture.cultureNegativeOrPositive !== 0"
                                :checked="formData.GramStainAndCulture.cultureNegativeOrPositive === 0" />
                            <label class="form-check-label" for="cultureNegativeOrPositive1" style=" margin-top: 2px; margin-left: 10px">Negative</label>
                        </div>
                        <div class="col-md-2 " v-if="parseInt(formData.GramStainAndCulture.isSubmittingCulture) === 1">
                            <input class="form-check-input" type="radio" name="cultureNegativeOrPositive"
                               :disabled="formData.GramStainAndCulture.cultureNegativeOrPositive !== 1"
                                :checked="formData.GramStainAndCulture.cultureNegativeOrPositive === 1" />
                            <label class="form-check-label" for="cultureNegativeOrPositive2" style=" margin-top: 2px; margin-left: 10px">Positive</label>
                        </div>
                        <div class="col-md-3" v-if="parseInt(formData.GramStainAndCulture.cultureNegativeOrPositive) === 1">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 16px;">
                                        ผล
                                    </p>
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " aria-label="default input example"
                                         :value="formData.GramStainAndCulture.resultCulture" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- row 2 -->
                    <div class="row" style="margin-top: 16px; justify-content: flex-end"
                        v-if="parseInt(formData.GramStainAndCulture.gramNegativeOrPositive) === 2">
                        <div class="col-md-3 ">
                            <input class="form-check-input" type="radio" name="cultureNegativeOrPositive"
                            :disabled="formData.GramStainAndCulture.cultureNegativeOrPositive !== 2"
                                :checked="formData.GramStainAndCulture.cultureNegativeOrPositive === 2" />
                            <label class="form-check-label" for="cultureNegativeOrPositive3" style=" margin-top: 2px; margin-left: 10px">รอผลออกวันที่</label>
                        </div>
                        <div class="col-md-4">
                            <div class="card-box-info-row-component-style"
                                v-if="parseInt(formData.GramStainAndCulture.cultureNegativeOrPositive) === 2">
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
                                    <div style="position: relative">
                                        <div style="display: inline; position: absolute; width: 100%">
                                            <input class="form-control typing-box-style" style="
                                                padding-left: 16px;
                                                padding-right: 16px;
                                                padding-top: 0px;
                                                padding-bottom: 0px;
                                                " type="date"  :value="formData.GramStainAndCulture.toDateCulture"
                                                aria-label="readonly input example" id="toDateCulture" name="toDateCulture" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- row 3 -->
                    <div class="row" style="margin-top: 16px; " v-if="parseInt(formData.GramStainAndCulture.isSubmittingCulture) === 0">
                        <p class=" col-md-2">
                        </p>
                        <div class="col-md-9 ">
                            <input class="form-check-input" type="radio" name="isSubmittingCulture"
                            :disabled="formData.GramStainAndCulture.isSubmittingCulture !== 0"
                                :checked="formData.GramStainAndCulture.isSubmittingCulture === 0" />
                            <label class="form-check-label" for="isSubmittingCulture2" style=" margin-top: 2px; margin-left: 10px">Not done</label>
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
                                " aria-label="default input example"  :value="formData.data.interpretation">
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
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-left: 16px;
                                        padding-right: 16px;
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " type="text" aria-label="default input example" @input="handleInput('test')"
                                         :value="formData.data.testedBy" />
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
                                    <div style="position: relative">
                                        <div style="display: inline; position: absolute; width: 100%">
                                            <input disabled class="form-control typing-box-style" style="
                                                padding-left: 16px;
                                                padding-right: 16px;
                                                padding-top: 0px;
                                                padding-bottom: 0px;
                                                " type="date"  :value="formData.data.testedDate"
                                                aria-label="readonly input example" id="testedDate" name="testedDate"/>
                                        </div>
                                    </div>
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
                                <div style="display: inline; position: absolute; width: 80%">
                                    <p class="fontTopicInfo" style="margin-left: 16px; margin-top: 7px">
                                        เวลา
                                    </p>
                                    <div style="position: relative">
                                        <div style="display: inline; position: absolute; width: 100%">
                                            <input class="form-control typing-box-style" style="
                                                padding-left: 16px;
                                                padding-right: 16px;
                                                padding-top: 0px;
                                                padding-bottom: 0px;
                                                " type="time"  :value="formData.data.testedTime"
                                                aria-label="readonly input example" id="testedTime" name="testedTime" disabled/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 16px;">
                        <div class="col-md-6">
                            <div class="card-box-info-row-component-style">
                                <div style="display: inline; position: absolute; width: 100%">
                                    <p class="fontTopicInfo" style="margin-left: 16px;">
                                        Report by
                                    </p>
                                    <input disabled class="form-control typing-box-style" style="
                                        padding-left: 16px;
                                        padding-right: 16px;
                                        padding-top: 0px;
                                        padding-bottom: 0px;
                                        " type="text" aria-label="default input example" @input="handleInput('report')"
                                         :value="formData.data.reportedBy" />
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
                                    <div style="position: relative">
                                        <div style="display: inline; position: absolute; width: 100%">
                                            <input class="form-control typing-box-style" style="
                                                padding-left: 16px;
                                                padding-right: 16px;
                                                padding-top: 0px;
                                                padding-bottom: 0px;
                                                " type="date"  :value="formData.data.reportedDate"
                                                aria-label="readonly input example" id="reportedDate" name="reportedDate" disabled/>
                                        </div>
                                    </div>
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
                                <div style="display: inline; position: absolute; width: 80%">
                                    <p class="fontTopicInfo" style="margin-left: 16px; margin-top: 7px">
                                        เวลา
                                    </p>
                                    <div style="position: relative">
                                        <div style="display: inline; position: absolute; width: 100%">
                                            <input disabled class="form-control typing-box-style" style="
                                                padding-left: 16px;
                                                padding-right: 16px;
                                                padding-top: 0px;
                                                padding-bottom: 0px;
                                                " type="time"  :value="formData.data.reportedTime"
                                                aria-label="readonly input example" id="reportedTime" name="reportedTime" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--button-->
                <div class="card" style="border: 0px; margin-bottom: 32px">
                    <div style="display: flex; justify-content: flex-end; gap: 2%">
                        <button class="btn button-style-close" data-bs-toggle="modal" data-bs-target="#CloseButton"
                            style="margin-top: 32px" @click="navigateToPreviousPage">
                            ปิด
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
    color: #000000;
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
    width: 100%;
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
    font-weight: 400;
    font-size: 16px;
    color: #202124;
}

.select-box-style {
    width: 100%;
    background-color: rgb(213, 224, 224, 0);
    border: rgb(213, 224, 224, 0);
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
    z-index: 999;
    /* Ensure it appears above other elements */
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
    .vertical-style-100w {
    width: 100%;
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

}
</style>
