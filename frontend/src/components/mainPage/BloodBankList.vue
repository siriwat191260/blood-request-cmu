<script>
import { Icon } from "@iconify/vue";
import { parseDate, parseTime } from "../general/dateUtils"
export default {
    name: "MedList",
    props: {
        userInfo: Object,
        tableData: {
            type: Array,
            required: true
        },
        userApprove: {
            type: Array,
            required: true
        },
    },
    data() {
        return {
            searchHN: null,
            sortByField: '',
            sortDirection: 'asc',
            currentPage: 1,
            rowsPerPage: 10,
        }
    },
    computed: {
        sortedRows() {
            if (!this.sortByField) return this.tableData;

            const sortedRows = [...this.tableData];
            sortedRows.sort((a, b) => {
                const valA = a[this.sortByField];
                const valB = b[this.sortByField];
                if (valA < valB) return this.sortDirection === 'asc' ? -1 : 1;
                if (valA > valB) return this.sortDirection === 'asc' ? 1 : -1;
                return 0;
            });
            return sortedRows;
        },
        totalPages() {
            return Math.ceil(this.sortedRows.length / this.rowsPerPage);
        },
        displayedRows() {
            const startIndex = (this.currentPage - 1) * this.rowsPerPage;
            const endIndex = startIndex + this.rowsPerPage;
            let data = this.sortedRows;
            if (this.searchHN) {
                data = data.filter(row => {
                    return row.hn.includes(this.searchHN);
                });
            } 
            return data.slice(startIndex, endIndex)
        },
        paginationRange() {
            const start = (this.currentPage - 1) * this.rowsPerPage + 1;
            const end = Math.min(this.currentPage * this.rowsPerPage, this.sortedRows.length);
            return `${start}-${end} of ${this.sortedRows.length}`;
        },
        visiblePages() {
            const totalPages = this.totalPages;
            const currentPage = this.currentPage;
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, startPage + 4); // Ensure 5 pages are always shown

            if (endPage - startPage < 4) {
                startPage = Math.max(1, endPage - 4);
            }

            return Array.from({ length: endPage - startPage + 1 }, (_, i) => startPage + i);
        },
    },
    methods: {
        sortBy(field) {
            if (field === this.sortByField) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortByField = field;
                this.sortDirection = 'asc';
            }

            if (typeof this.sortedRows[0][field] === 'string') {
                this.sortedRows.sort((a, b) => {
                    const valA = a[field].toLowerCase();
                    const valB = b[field].toLowerCase();
                    if (valA < valB) return this.sortDirection === 'asc' ? -1 : 1;
                    if (valA > valB) return this.sortDirection === 'asc' ? 1 : -1;
                    return 0;
                });
            } else {
                this.sortedRows.sort((a, b) => {
                    const valA = a[field];
                    const valB = b[field];
                    if (valA < valB) return this.sortDirection === 'asc' ? -1 : 1;
                    if (valA > valB) return this.sortDirection === 'asc' ? 1 : -1;
                    return 0;
                });
            }
        },
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        },
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },
        goToFirstPage() {
            this.currentPage = 1;
        },
        goToLastPage() {
            this.currentPage = this.totalPages;
        },
        goToPage(pageNumber) {
            this.currentPage = pageNumber;
        },
        getTransFusionForm(id) {
            this.$router.push(`/get-transfusion-form/${id}`);
        },
        addTransFusionReport(id) {
            this.$router.push(`/transfusion-report/${id}`);
        },
        editTransFusionReport(id) {
            this.$router.push(`/edit-transfusion-report/${id}`);
        },
        getApprove(id) {
            this.$router.push(`/get-approve/${id}`);
        },
        addApprove(id) {
            this.$router.push(`/approve/${id}`);
        },
        parseDate,
        parseTime,
        isUserApproved() {
            for (let i = 0; i < this.userApprove.length; i++) {
                if (this.userApprove[i].id && this.userApprove[i].id.toString() === this.userInfo.s_uid) {
                    return true
                }
            }
            return false;
        }
    },
    components: {
        Icon
    },
};
</script>

<template>
    <div>
        <nav class="navbar">
            <div class="left">
                <p class="fontSize_header">Transfusion</p>
            </div>
            <div class="right">
                <div v-if="userInfo" class="user-info">
                    <p>{{ userInfo.name }}</p>
                    <p>{{ userInfo.role }}</p>
                </div>
                <!-- <button v-if="userInfo" @click="logout">Logout</button> -->
            </div>
        </nav>
        <div class="container-md">
            <div class="card"
                style="border: 0px; justify-content: center; margin: 20px 0px; display: flex; align-items: center; flex-direction: row;">
                <p style="font-size: 1.2rem; font-weight: 600; margin-top: 30px; margin-bottom: 0; color: #3c3c3c; flex: 1;">
                    การเกิดปฏิกิริยาจากการรับเลือดทั้งหมด</p>
                <div class="col-md-3">
                    <p class="fontTopicBox">ค้นหา HN</p>
                    <div class="card search-card">
                        <div class="card-body search-input">
                            <!-- Icon -->
                            <Icon icon="ion:search" class="search-icon"></Icon>
                            <!-- Input field -->
                            <input class="form-control typing-box-style search-input-field" v-model="searchHN" type="number"
                                pattern="[0-9]*" />
                        </div>
                    </div>
                </div>

            </div>
            <table>
                <thead>
                    <tr>
                        <th @click="sortBy('dtm')">วันที่
                            <i v-if="sortByField === 'dtm'"
                                :class="sortDirection === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'"></i>
                        </th>
                        <th @click="sortBy('dtm')">
                            เวลา
                            <i v-if="sortByField === 'dtm'"
                                :class="sortDirection === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'"></i>
                        </th>
                        <th @click="sortBy('packid')">หมายเลขถุงเลือด
                            <i v-if="sortByField === 'packid'"
                                :class="sortDirection === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'"></i>
                        </th>
                        <th @click="sortBy('name')">ชื่อ-สกุล
                            <i v-if="sortByField === 'name'"
                                :class="sortDirection === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'"></i>
                        </th>
                        <th @click="sortBy('hn')">HN
                            <i v-if="sortByField === 'hn'"
                                :class="sortDirection === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'"></i>
                        </th>
                        <th @click="sortBy('status')">สถานะ
                            <i v-if="sortByField === 'approve'"
                                :class="sortDirection === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'"></i>
                        </th>
                        <th @click="sortBy('TRForm')">Transfusion
                            <i v-if="sortByField === 'TRForm'"
                                :class="sortDirection === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'"></i>
                        </th>
                        <th @click="sortBy('TRReport')">Transfusion reaction
                            <i v-if="sortByField === 'TRReport'"
                                :class="sortDirection === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'"></i>
                        </th>
                        <th @click="sortBy('approve')">Approve
                            <i v-if="sortByField === 'approve'"
                                :class="sortDirection === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'"></i>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(row, index) in displayedRows" :key="index">
                        <td>{{ parseDate(row.dtm) }}</td>
                        <td>{{ parseTime(row.dtm) }}</td>
                        <td>{{ row.packid }}</td>
                        <td>{{ row.ttl + " " + row.name + " " + row.lname }}</td>
                        <td>{{ row.hn }}</td>
                        <td v-if="row.approve == 1" class="done">สำเร็จ</td>
                        <td v-else class="wait"> รอ</td>

                        <td v-if="row.TRForm == 100" @click="getTransFusionForm(row.idTR_Form)" class="click ">
                            ฟอร์มนำส่งตรวจ </td>
                        <td v-else class="wait">ฟอร์มนำส่งตรวจ</td>

                        <td v-if="row.TRForm !== 100">-</td>
                        <td v-else-if="!row.idTR_Report " @click="addTransFusionReport(row.idTR_Form)">
                            <div class="add">
                                <Icon icon="material-symbols:note-add-outline" class="icon-add" />
                                <p class="done">เพิ่มรายงาน</p>
                            </div>
                        </td>
                        <td v-else-if="row.TRReport === 100" @click="editTransFusionReport(row.idTR_Report)" class="click">รายงานการตรวจ</td>
                        <td v-else class="wait click" @click="editTransFusionReport(row.idTR_Report)" >รายงานการตรวจ {{ row.TRReport }}%</td>

                        <td v-if="!row.approve && isUserApproved() && row.TRReport === 100"
                            @click="addApprove(row.idTR_Report)">
                            <div class="add">
                                <Icon icon="material-symbols:note-add-outline" class="icon-add" />
                                <p class="done">เพิ่ม review</p>
                            </div>
                        </td>
                        <td v-else-if="row.approve === 1" class="done click" @click="getApprove(row.idTR_Report)">สำเร็จ</td>
                        <td v-else class="wait">รอ</td>
                    </tr>
                </tbody>
            </table>
            <div class="pagination">
                <div class="left">
                    <p>
                        {{ paginationRange }}
                    </p>
                </div>
                <div class="right">
                    <button @click="goToFirstPage">
                        <Icon icon="bx:arrow-to-left" :style="{
                            width: '150%', height: '100%',
                            color: currentPage === 1 ? '#9D9D9D' : 'orange'
                        }" />
                    </button>
                    <button @click="prevPage">
                        <Icon icon="bx:left-arrow-alt" :style="{
                            width: '150%', height: '100%',
                            color: currentPage === 1 ? '#9D9D9D' : 'orange'
                        }" />
                    </button>
                    <span>
                        <button v-for="pageNumber in visiblePages" :key="pageNumber" @click="goToPage(pageNumber)"
                            :style="{ fontWeight: 1000, color: currentPage === pageNumber ? '#9D9D9D' : 'black' }">
                            {{ pageNumber }}
                        </button>
                    </span>

                    <button @click="nextPage">
                        <Icon icon="bx:right-arrow-alt" :style="{
                            width: '150%', height: '100%',
                            color: currentPage === totalPages ? '#9D9D9D' : 'orange'
                        }" />
                    </button>
                    <button @click="goToLastPage">
                        <Icon icon="bx:arrow-to-right" :style="{
                            width: '150%', height: '100%',
                            color: currentPage === totalPages ? '#9D9D9D' : 'orange'
                        }" />
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
  
<style scoped>
.container-md {
    margin-left: auto;
    margin-right: auto;
    margin-bottom: 24px;
}

p {
    font-family: "IBM Plex Sans Thai";
    font-size: 0.8rem;
    color: #4F4F4F;
    margin: 0%;
}

/* Navbar styles */
.fontSize_header {
    font-size: 1.7rem;
    font-weight: 700;
    color: black;
    display: contents;
}

.wait {
    color: #9D9D9D;
}

.done {
    color: #008E76;
}

.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #E4F6F4;
    color: white;
    height: 5rem;
    padding: 0.5rem 2.5rem;
}

.user-info {
    align-items: center;
}

.left {
    display: flex;
    align-items: center;
}

.right {
    display: flex;
    align-items: center;
}

.add {
    display: flex;
    align-items: center;
    border: 0;
}
.add:hover {
    cursor: pointer;
}
.click:hover {
    cursor: pointer;
}

button {
    background-color: transparent;
    color: #4F4F4F;
    border: none;
    cursor: pointer;
    font-family: "IBM Plex Sans Thai";
    font-size: 0.8rem;
    margin: 0%;
}

button:hover {
    text-decoration: underline;
}

/* table styles */
table {
    width: 100%;
    border-radius: 10px;
    overflow: hidden;
}

th,
td {
    border: 1px solid #f0f0f0;
    padding: 8px;
    text-align: left;
    font-family: "IBM Plex Sans Thai";
    font-size: 0.8rem;
}

th {
    border: 2px solid #ffffff;
    cursor: pointer;
    background-color: #EFF0F3;
    justify-content: center;
}

.pagination {
    margin-top: 30px;
    display: flex;
    justify-content: space-between;
}

.pagination button {
    margin-right: 5px;
    cursor: pointer;
}

.disabled .icon {
    color: gray;
    /* Color when button is disabled */
}

.active {
    color: red;
    /* Change color as needed */
}

.icon-add {
    width: 15%;
    height: 100%;
    margin-right: 6px;
    color: #008E76;
}

.search-card {
    width: 100%;
    height: 48px;
    border: 2px solid #dee0e6;
    border-radius: 8px;
    background-color: #fbfbfc;
}

.search-input {
    display: flex;
    width: 100%;
    height: 100%;
    padding: 0px 0px 0px 6px;
    align-items: center;
}

.search-icon {
    color: #00bfa5;
    width: 25px;
    height: 25px;
}

.search-input-field {
    padding-left: 16px;
    padding-right: 16px;
    padding-top: 0px;
    padding-bottom: 0px;
    flex: 1;
    border: none;
    outline: none;
    background: transparent;
}

@media only screen and (min-device-width: 768px) and (max-device-width: 1100px) {
    .icon-add {
        width: 20%;
    }
}

@media only screen and ((max-device-width: 768px) or (max-device-width: 810px)) {
    .icon-add {
        width: 27%;
        margin-right: 3px;
    }
}
</style>