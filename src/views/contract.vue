<template>
  <div class="contract-container">
    <div class="page-header">
      <h2 class="mb-3">ข้อมูลสการติดต่อ</h2>
      <router-link class="add-button btn btn-primary" to="/add_contract">Add Contract</router-link>
    </div>

    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>ลำดับที่</th>
          <th>รหัสนักศึกษา</th>
          <th>ประเภทสผู้ใช่</th>
          <th>เบอร์โทร</th>
          <th>วันเวลาที่สร้าง</th>
          <th>Email</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="(item, index) in contracts" :key="item.contract_id ?? item.id ?? index">
          <td>{{ index + 1 }}</td>
          <td>{{ item.studentId }}</td>
          <td>{{ item.contractType }}</td>
          <td>{{ item.phone }}</td>
          <td>{{ item.startDate }}</td>
          <td>{{ item.email }}</td>
        </tr>
      </tbody>
    </table>

    <div v-if="loading" class="text-center">
      <p>กำลังโหลดข้อมูล...</p>
    </div>

    <div v-if="error" class="alert alert-danger">
      {{ error }}
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from "vue";

export default {
  name: "ContractList",

  setup() {
    const contracts = ref([]);
    const loading = ref(true);
    const error = ref(null);

    const fetchData = async () => {
      try {
        const response = await fetch("http://localhost/Praject-V.2/php_api/showcontract.php");

        if (!response.ok) {
          throw new Error("ไม่สามารถดึงข้อมูลได้");
        }

        const result = await response.json();

        if (!result.success || !Array.isArray(result.data)) {
          throw new Error(result.message || "รูปแบบข้อมูลไม่ถูกต้อง");
        }

        contracts.value = result.data;
      } catch (err) {
        error.value = err.message;
      } finally {
        loading.value = false;
      }
    };

    onMounted(() => {
      fetchData();
    });

    return {
      contracts,
      loading,
      error
    };
  }
};
</script>

<style scoped>
.contract-container {
  width: min(900px, calc(100% - 32px));
  margin: 40px auto;
  text-align: center;
}

.page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
}

.add-button {
  padding: 9px 14px;
  border-radius: 4px;
  background: #198754;
  color: #fff;
  text-decoration: none;
}

table {
  width: 100%;
  margin: 0 auto;
  border-collapse: collapse;
}

th,
td {
  padding: 12px;
  border: 1px solid #d9d9d9;
  text-align: center;
}

th {
  color: #fff;
  background: #2c3e50;
}

tbody tr:nth-child(even) {
  background: #f7f7f7;
}
</style>
