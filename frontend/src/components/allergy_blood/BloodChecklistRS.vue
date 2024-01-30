<script>
import { defineComponent } from "vue";
import DropDownSVGVue from "../general/DropDownSVG.vue";
import { Icon } from "@iconify/vue";

export default defineComponent({
  name: "BloodChecklistRS",
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
  <!-- <div>
        <h1>HELLO WORLD</h1>
    </div> -->
  <div class="container">
    <form>
      <div class="card" style="border: 0px" justify-content: center>
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
        <!-- <div class="card card-box-info-style">
          <div class="row">
            <div class="col-md-3">
              <div class="card-box-info-row-1-component-style">
                <Icon
                  icon="bx:calendar-event"
                  style="
                    width: 24px;
                    height: 24px;
                    margin-left: 16px;
                    margin-top: 18px;
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
                          width: auto;
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
            <div class="col-md-3">
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
                <div style="display: inline; position: absolute">
                  <p
                    class="fontTopicInfo"
                    style="margin-left: 16px; margin-top: 7px"
                  >
                    เวลา
                  </p>
                  <div style="position: relative">
                    <div style="display: inline; position: absolute">
                      <input
                        class="form-control typing-box-style"
                        style="
                          width: auto;
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
            <div class="col-md-3">
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
                          width: auto;
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
            <div class="col-md-3">
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
                          width: auto;
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
          </div>
        </div> -->
      </div>
    </form>
  </div>
</template>
<style scoped>
.fontSize_header {
  font-size: 24px;
  font-weight: 700;
  font-family: "IBM Plex Sans Thai";
}

@media only screen and (min-device-width: 768px) and (max-device-width: 1100px) {
  .fontSize_header {
    font-size: 20px;
  }
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
  padding: 12px 0px 0px 16px;
}
.card-box-info-style {
  margin-top: 16px;
  width: auto;
  height: 375px;
  border: 0;
}
.card-box-info-row {
  width: 100%;
  height: 71px;
  display: "flex";
  justify-content: "space-between";
}
.card-box-info-layout-component-style {
  width: 20%;
  height: 71px;
}
.card-box-info-row-1-component-style {
  width: 100%;
  height: 50px;
  background-color: rgb(213, 224, 224, 20%);
  position: relative;
  border-bottom: 2px solid #d5e0e0;
  border-radius: 5px 5px 0px 0px;
}

.typing-box-style {
  background-color: rgb(213, 224, 224, 0);
  border: rgb(213, 224, 224, 0);
  font-family: "Noto Looped Thai";
  font-weight: 400;
  font-size: 16px;
  color: #202124;
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
</style>
