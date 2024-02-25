<script>
import { Icon } from "@iconify/vue";
import { parseDate,parseTime } from "../general/dateUtils"
export default {
    name: "MedList",
    props: {
        userInfo: Object,
        tableData: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            sortByField: '',
            sortDirection: 'asc',
            currentPage: 1,
            rowsPerPage: 13
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
            return this.sortedRows.slice(startIndex, endIndex);
        },
        paginationRange() {
            const start = (this.currentPage - 1) * this.rowsPerPage + 1;
            const end = Math.min(this.currentPage * this.rowsPerPage, this.sortedRows.length);
            return `${start}-${end} of ${this.sortedRows.length}`;
        }
    },
    methods: {
        sortBy(field) {
            if (field === this.sortByField) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortByField = field;
                this.sortDirection = 'asc';
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
        addTransFusionForm(id) {
            this.$router.push(`/transfusion-form/${id}`);
        },
        parseDate,
        parseTime
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
                    <p>{{ userInfo.title + " " + userInfo.firstName + " " + userInfo.lastName }}</p>
                    <p>{{ userInfo.roleName }}</p>
                </div>
                <!-- <button v-if="userInfo" @click="logout">Logout</button> -->
            </div>
        </nav>
        <div class="container-md">
            <div class="card" style="border: 0px; justify-content: center; margin: 30px 0px;">
                <p class="fontSize_header">ประวัติการรับเลือดทั้งหมด</p>
            </div>
            <table>
                <thead>
                    <tr>
                        <th @click="sortBy('date')">วันที่
                            <i v-if="sortByField === 'date'"
                                :class="sortDirection === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'"></i>
                        </th>
                        <th @click="sortBy('time')">เวลา
                            <i v-if="sortByField === 'time'"
                                :class="sortDirection === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'"></i>
                        </th>
                        <th @click="sortBy('bloodBagNum')">หมายเลขถุงเลือด
                            <i v-if="sortByField === 'bloodBagNum'"
                                :class="sortDirection === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'"></i>
                        </th>
                        <th @click="sortBy('name')">ชื่อ-สกุล
                            <i v-if="sortByField === 'name'"
                                :class="sortDirection === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'"></i>
                        </th>
                        <th @click="sortBy('HN')">HN
                            <i v-if="sortByField === 'HN'"
                                :class="sortDirection === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'"></i>
                        </th>
                        <th @click="sortBy('status')">สถานะ
                            <i v-if="sortByField === 'status'"
                                :class="sortDirection === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'"></i>
                        </th>
                        <th @click="sortBy('transfusion')">Transfusion
                            <i v-if="sortByField === 'transfusion'"
                                :class="sortDirection === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'"></i>
                        </th>
                        <th @click="sortBy('reaction')">Transfusion reaction
                            <i v-if="sortByField === 'reaction'"
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
                        <td v-if="row.status === 1" class="wait">รอ</td>
                        <td v-else-if="row.status === 2" class="done">สำเร็จ</td>
                        <td v-else class="wait"> ไม่มีปฏิกิริยา</td>

                        <td v-if="!row.TRForm" @click="addTransFusionForm(row.blood_transf_id)">
                            <div class="add">
                                <Icon icon="material-symbols:note-add-outline" class="icon-add" />
                                <p class="done">เพิ่มฟอร์ม</p>
                            </div>

                        </td>
                        <td v-else>ฟอร์มนำส่งตรวจ</td>

                        <td v-if="row.reaction === 1" class="wait">รายงานการตรวจ</td>
                        <td v-else-if="row.reaction === 2">รายงานการตรวจ</td>
                        <td v-else>-</td>

                        <td v-if="row.status === 1" class="wait">รอ</td>
                        <td v-else-if="row.status === 2" class="done">สำเร็จ</td>
                        <td v-else>-</td>
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
                    <span v-for="pageNumber in totalPages" :key="pageNumber">
                        <button @click="goToPage(pageNumber)" :style="{
                            fontWeight: 1000,
                            color: currentPage === pageNumber ? '#9D9D9D' : 'black'
                        }">{{ pageNumber }}
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