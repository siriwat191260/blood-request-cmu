<script>
import { defineComponent } from "vue";
import DropDownSVGVue from "../general/DropDownSVG.vue";
import { Icon } from "@iconify/vue";

export default defineComponent( {
  name: 'BloodChecklist',
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
      const currentTime = new Date();
      const time =
        currentTime.getHours() +
        ":" +
        currentTime.getMinutes() +
        ":" +
        currentTime.getSeconds();
      return time;
    },
  },
  components: {
    DropDownSVGVue,
    Icon,
  },
});
</script>

<template>
  <div class="container">
    <form>
      <div
        class="card"
        style="border: 0px; width: 100%; justify-content: center"
      >
        <!-- Header -->
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
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 0 0 0 0
                  0 0
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
          <!-- row 1 -->
          <div
            class="row card-box-info-row"
            style="display: flex; justify-content: space-between"
          >
            <div class="col-md-2 card-box-info-layout-component-style">
              <div class="card-box-info-row-1-component-style">
                <Icon
                  icon="bx:calendar-event"
                  style="
                    width: 24px;
                    height: 24px;
                    margin-left: 16px;
                    margin-top: 18px;
                    padding-right: 0%;
                  "
                />
                <div style="display: inline; position: absolute">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    วันที่
                  </p>
                  <div style="position: relative">
                    <div style="display: inline; position: absolute">
                      <input
                        class="form-control typing-box-style"
                        style="
                          width: 178px;
                          margin-left: 0px;
                          margin-right: 16px;
                          padding-left: 16px;
                          padding-top: 0px;
                          padding-bottom: 0px;
                        "
                        type="text"
                        :value="currentDate()"
                        aria-label="readonly input example"
                        readonly
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-2 card-box-info-layout-component-style">
              <div class="card-box-info-row-1-component-style">
                <Icon
                  icon="bx:alarm"
                  style="
                    width: 24px;
                    height: 24px;
                    margin-left: 16px;
                    margin-top: 18px;
                  "
                />
                <!-- <i
                    class="fa-regular fa-clock"
                    style="font-size: 24px; margin-left: 16px; margin-top: 18px"
                  ></i> -->
                <div style="display: inline; position: absolute">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    เวลา
                  </p>
                  <!-- <p
                      style="
                        margin-left: 16px;
                        font-size: 16px;
                        font-weight: 400;
                      "
                    >
                      {{ currentTime() }}
                    </p> -->
                  <div style="position: relative">
                    <div style="display: inline; position: absolute">
                      <input
                        class="form-control typing-box-style"
                        style="
                          width: 178px;
                          margin-left: 0px;
                          margin-right: 16px;
                          padding-left: 16px;
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
            <div class="col-md-6" style="width: 312px; height: 71px">
              <div
                style="
                  width: 312px;
                  height: 50px;
                  background-color: rgba(213, 224, 224, 20%);
                  position: relative;
                  border-bottom: 2px solid rgba(219, 225, 230, 1);
                  border-radius: 5px 5px 0px 0px;
                "
              >
                <div style="display: inline; position: absolute">
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
                        /* margin-left: 16px; */
                        margin-right: 16px;
                        padding-left: 16px;
                        padding-top: 0px;
                        padding-bottom: 0px;
                      "
                      aria-label="Small select example"
                    >
                      <option value="" disabled selected>
                        กรุณาเลือกข้อมูล
                      </option>
                      <option value="1">One</option>
                      <option value="2">Two</option>
                      <option value="3">Three</option>
                    </select>
                  </div>

                  <!-- <DropDownSVGVue class="position-absolute  translate-middle-y" style="left: 276px; top:35px"/> -->
                </div>
              </div>
            </div>
            <div class="col-md-2 card-box-info-layout-component-style">
              <div class="card-box-info-row-1-component-style">
                <div style="display: inline; position: absolute">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    โทร
                  </p>
                  <!-- <p
                      style="
                        margin-left: 16px;
                        font-size: 16px;
                        font-weight: 400;
                      "
                    >
                      123-456-789
                    </p> -->
                  <div style="position: relative">
                    <div style="display: inline; position: absolute">
                      <input
                        class="form-control typing-box-style"
                        style="
                          width: 218px;
                          margin-left: 0px;
                          margin-right: 16px;
                          padding-left: 16px;
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
          </div>
          <!-- row 2 -->
          <div
            class="row card-box-info-row"
            style="display: flex; justify-content: space-between"
          >
            <div class="col-md-8" style="width: 800px; height: 71px">
              <div class="card-box-info-row-2-component-style">
                <div style="display: inline; position: absolute">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    การวินิจฉัย
                  </p>
                  <input
                    class="form-control typing-box-style"
                    style="
                      width: 800px;
                      margin-left: 0px;
                      margin-right: 16px;
                      padding-left: 16px;
                      padding-top: 0px;
                      padding-bottom: 0px;
                    "
                    type="text"
                    aria-label="default input example"
                  />
                  <!-- <div class="custom-select">
                      <select
                        class="form-select-sm select-box-style"
                        style="
                          margin-left: 16px;
                          margin-right: 16px;
                          padding-left: 0px;
                          padding-top: 0px;
                          padding-bottom: 0px;
                        "
                        aria-label="Small select example"
                      >
                        <option value="" disabled selected>กรุณาเลือกข้อมูล</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                      </select>
                    </div> -->
                </div>
                <!-- <div style="display: inline; position: absolute">
                    <p
                      class="fontTopicInfo"
                      style="margin-left: 16px; margin-top: 7px"
                    >
                      การวินิจฉัย
                    </p>
                    <input
                      class="form-control"
                      type="text"
                      placeholder="Default input"
                      aria-label="default input example"
                    />
                  </div> -->
              </div>
            </div>
            <div class="col-md-4" style="width: 312px; height: 71px">
              <div
                style="
                  width: 312px;
                  height: 50px;
                  background-color: rgb(213, 224, 224, 20%);
                  position: relative;
                  border-bottom: 2px solid #d5e0e0;
                "
              >
                <div style="display: inline; position: absolute">
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
                        margin-left: 0px;
                        margin-right: 16px;
                        padding-left: 16px;
                        padding-top: 0px;
                        padding-bottom: 0px;
                      "
                      aria-label="Small select example"
                    >
                      <option value="" disabled selected>
                        กรุณาเลือกข้อมูล
                      </option>
                      <option value="1">One</option>
                      <option value="2">Two</option>
                      <option value="3">Three</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- row 3 -->
          <div
            class="row card-box-info-row"
            style="display: flex; justify-content: space-between"
          >
            <div class="col-md-3" style="width: 196px; height: 71px">
              <div
                style="
                  width: 196px;
                  height: 50px;
                  background-color: rgb(213, 224, 224, 20%);
                  position: relative;
                  border-bottom: 2px solid #d5e0e0;
                "
              >
                <div style="display: inline; position: absolute">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    หมู่เลือดผู้ป่วย
                  </p>

                  <div class="custom-select">
                    <select
                      class="form-select-sm"
                      style="
                        margin-left: 0px;
                        margin-right: 16px;
                        padding-left: 16px;
                        padding-top: 0px;
                        padding-bottom: 0px;
                        background-color: rgb(213, 224, 224, 0);
                        border: rgb(213, 224, 224, 0);
                        width: 180px;
                      "
                      aria-label="Small select example"
                    >
                      <option value="" disabled selected>
                        กรุณาเลือกข้อมูล
                      </option>
                      <option value="1">One</option>
                      <option value="2">Two</option>
                      <option value="3">Three</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3" style="width: 196px; height: 71px">
              <div
                style="
                  width: 196px;
                  height: 50px;
                  background-color: rgb(213, 224, 224, 20%);
                  position: relative;
                  border-bottom: 2px solid #d5e0e0;
                "
              >
                <div style="display: inline; position: absolute">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    RH
                  </p>

                  <div class="custom-select">
                    <select
                      class="form-select-sm"
                      style="
                        margin-left: 0px;
                        margin-right: 16px;
                        padding-left: 16px;
                        padding-top: 0px;
                        padding-bottom: 0px;
                        background-color: rgb(213, 224, 224, 0);
                        border: rgb(213, 224, 224, 0);
                        width: 180px;
                      "
                      aria-label="Small select example"
                    >
                      <option value="" disabled selected>
                        กรุณาเลือกข้อมูล
                      </option>
                      <option value="1">One</option>
                      <option value="2">Two</option>
                      <option value="3">Three</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3" style="width: 196px; height: 71px">
              <div
                style="
                  width: 196px;
                  height: 50px;
                  background-color: rgb(213, 224, 224, 20%);
                  position: relative;
                  border-bottom: 2px solid #d5e0e0;
                "
              >
                <div style="display: inline; position: absolute">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    ชนิดของเลือดที่ให้
                  </p>

                  <div class="custom-select">
                    <select
                      class="form-select-sm"
                      style="
                        margin-left: 0px;
                        margin-right: 16px;
                        padding-left: 16px;
                        padding-top: 0px;
                        padding-bottom: 0px;
                        background-color: rgb(213, 224, 224, 0);
                        border: rgb(213, 224, 224, 0);
                        width: 180px;
                      "
                      aria-label="Small select example"
                    >
                      <option value="" disabled selected>
                        กรุณาเลือกข้อมูล
                      </option>
                      <option value="1">One</option>
                      <option value="2">Two</option>
                      <option value="3">Three</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3" style="width: 196px; height: 71px">
              <div
                style="
                  width: 196px;
                  height: 50px;
                  background-color: rgb(213, 224, 224, 20%);
                  position: relative;
                  border-bottom: 2px solid #d5e0e0;
                "
              >
                <div style="display: inline; position: absolute">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    หมู่เลือดผู้บริจาค
                  </p>

                  <div class="custom-select">
                    <select
                      class="form-select-sm"
                      style="
                        margin-left: 0px;
                        margin-right: 16px;
                        padding-left: 16px;
                        padding-top: 0px;
                        padding-bottom: 0px;
                        background-color: rgb(213, 224, 224, 0);
                        border: rgb(213, 224, 224, 0);
                        width: 180px;
                      "
                      aria-label="Small select example"
                    >
                      <option value="" disabled selected>
                        กรุณาเลือกข้อมูล
                      </option>
                      <option value="1">One</option>
                      <option value="2">Two</option>
                      <option value="3">Three</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3" style="width: 196px; height: 71px">
              <div
                style="
                  width: 196px;
                  height: 50px;
                  background-color: rgb(213, 224, 224, 20%);
                  position: relative;
                  border-bottom: 2px solid #d5e0e0;
                "
              >
                <div style="display: inline; position: absolute">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    RH
                  </p>

                  <div class="custom-select">
                    <select
                      class="form-select-sm"
                      style="
                        margin-left: 0px;
                        margin-right: 16px;
                        padding-left: 16px;
                        padding-top: 0px;
                        padding-bottom: 0px;
                        background-color: rgb(213, 224, 224, 0);
                        border: rgb(213, 224, 224, 0);
                        width: 180px;
                      "
                      aria-label="Small select example"
                    >
                      <option value="" disabled selected>
                        กรุณาเลือกข้อมูล
                      </option>
                      <option value="1">One</option>
                      <option value="2">Two</option>
                      <option value="3">Three</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- row 4 -->
          <div
            class="row card-box-info-row"
            style="display: flex; justify-content: space-between"
          >
            <div class="col-md-3" style="width: 234px; height: 71px">
              <div class="card-box-info-row-4-component-style">
                <div style="display: inline; position: absolute">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    หมายเลขถุงเลือด
                  </p>
                  <input
                    class="form-control typing-box-style"
                    style="
                      width: 234px;
                      margin-left: 0px;
                      margin-right: 16px;
                      padding-left: 16px;
                      padding-top: 0px;
                      padding-bottom: 0px;
                    "
                    type="text"
                    aria-label="default input example"
                  />
                </div>
              </div>
            </div>
            <div class="col-md-3" style="width: 234px; height: 71px">
              <div class="input-group card-box-info-row-4-component-style">
                <div style="display: inline; position: absolute">
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
                        width: 200px;
                        margin-left: 0px;
                        margin-right: 0px;
                        padding-left: 16px;
                        padding-top: 0px;
                        padding-bottom: 0px;
                      "
                      type="number"
                      pattern="[0-9]*"
                      onkeypress="return event.charCode != 45"
                      min="0"
                      inputmode="numeric"
                      aria-label="default input example"
                    />
                    <span
                      class="input-group-text typing-box-style"
                      style="
                        width: 49px;
                        padding-left: 0px;
                        margin-bottom: 2px;
                        font-weight: 700;
                        font-size: 12px;
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
            <div class="col-md-6" style="width: 590px; height: 71px">
              <div
                style="
                  width: 590px;
                  height: 50px;
                  background-color: rgb(213, 224, 224, 20%);
                  position: relative;
                  border-bottom: 2px solid #d5e0e0;
                  border-radius: 5px 5px 0px 0px;
                "
              >
                <div style="display: inline; position: absolute">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    ยาที่ใช้ในปัจจุบัน
                  </p>
                  <input
                    class="form-control typing-box-style"
                    style="
                      width: 590px;
                      margin-left: 0px;
                      margin-right: 16px;
                      padding-left: 16px;
                      padding-top: 0px;
                      padding-bottom: 0px;
                    "
                    type="text"
                    aria-label="default input example"
                  />
                </div>
              </div>
            </div>
          </div>
          <!-- row 5 -->
          <div
            class="row card-box-info-row"
            style="display: flex; justify-content: space-between"
          >
            <div class="col-md-5">
              <div style="display: flex">
                <p
                  class="fontTopicInfo"
                  style="margin-left: 16px; margin-top: 26.5px; display: block"
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
                      name="inlineRadioOptions"
                      id="inlineRadio1"
                      value="option1"
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
                      name="inlineRadioOptions"
                      id="inlineRadio2"
                      value="option2"
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
            <div class="col-md-7" style="width: 751px; height: 71px">
              <div
                style="
                  width: 751px;
                  height: 50px;
                  background-color: rgb(213, 224, 224, 20%);
                  position: relative;
                  border-bottom: 2px solid #d5e0e0;
                  border-radius: 5px 5px 0px 0px;
                "
              >
                <div style="display: inline; position: absolute">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    ชนิดของปฏิกิริยา
                  </p>
                  <input
                    class="form-control typing-box-style"
                    style="
                      width: 751px;
                      margin-left: 0px;
                      margin-right: 16px;
                      padding-left: 16px;
                      padding-top: 0px;
                      padding-bottom: 0px;
                    "
                    type="text"
                    aria-label="default input example"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
        <hr class="dashed" />
      </div>
    </form>
  </div>
</template>

<style scoped>
.container {
  width: fit-content;
  max-width: calc(100% - 20px);
  margin: 0 auto;
  padding: 0 10px;
}
@media (min-width: 768px) and (max-width: 1024px) {
  /* Styles for iPad landscape mode */
  /* Adjust styles as needed for landscape view */
}

.fontSize_header {
  font-size: 24px;
  font-weight: 700;
  font-family: "IBM Plex Sans Thai";
}
.fontTopicBox {
  font-family: "IBM Plex Sans Thai";
  font-size: 18px;
  font-weight: 600;
  margin-top: 30px;
  margin-bottom: 0;
  color: #3c3c3c;
}
.fontInsideBox {
  font-family: "IBM Plex Sans Thai";
  font-size: 16px;
  font-weight: 400;
  color: #c4c4c4;
}
.fontTopicInfo {
  font-family: "IBM Plex Sans Thai";
  font-weight: 700;
  font-size: 12px;
  color: #202124;
  display: inline;
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

.card-box-info-style {
  margin-top: 16px;
  width: auto;
  height: 375px;
  border: 0;
}
.card-box-info-layout-component-style {
  width: 23%;
  height: 71px;
}
.card-box-info-row {
  height: 71px;
  display: "flex";
  justify-content: "space-between";
}
.card-box-info-row-1-component-style {
  width: 100%;
  height: 50px;
  background-color: rgb(213, 224, 224, 20%);
  position: relative;
  border-bottom: 2px solid #d5e0e0;
  border-radius: 5px 5px 0px 0px;
}
.card-box-info-row-2-component-style {
  width: 800px;
  height: 50px;
  background-color: rgb(213, 224, 224, 20%);
  position: relative;
  border-bottom: 2px solid #d5e0e0;
  border-radius: 5px 5px 0px 0px;
}
.card-box-info-row-4-component-style {
  width: 234px;
  height: 50px;
  background-color: rgb(213, 224, 224, 20%);
  position: relative;
  border-bottom: 2px solid #d5e0e0;
  border-radius: 5px 5px 0px 0px;
}

.select-box-style {
  background-color: rgb(213, 224, 224, 0);
  border: rgb(213, 224, 224, 0);
  width: 296px;
}

.typing-box-style {
  background-color: rgb(213, 224, 224, 0);
  border: rgb(213, 224, 224, 0);
  font-family: "Noto Looped Thai";
  font-weight: 400;
  font-size: 16px;
  color: #202124;
}

.custom-select select {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  /* background-color: rgba(213, 224, 224, 100%); */
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'%3E%3Cpath d='M12 2C6.486 2 2 6.486 2 12C2 17.514 6.486 22 12 22C17.514 22 22 17.514 22 12C22 6.486 17.514 2 12 2ZM12 16.414L6.293 10.707L7.707 9.293L12 13.586L16.293 9.293L17.707 10.707L12 16.414Z' fill='%2300BFA5'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 8px center;
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

hr.dashed {
  border-top: 2px dashed #999;
  width: 100%;
  margin-left: 3px;
}
.col-md-5 {
  flex: 0 0 auto;
  width: 35.8%;
}
</style>
