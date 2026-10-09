<template>
  <div class="contract-container">
    <div class="page-header">
      <h2 class="mb-3">ข้อมูล Contract</h2>
      <router-link class="add-button btn btn-primary" to="/add_contract">Add Contract</router-link>
    </div>

    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>ชื่อ</th>
          <th>ประเภทผู้ใช้</th>
          <th>เบอร์โทร</th>
          <th>อีเมล</th>
          <th>ข้อเสนอที่จะเสนอ</th>
          <th>แก้ไข/ลบ</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="item in contracts" :key="item.contract_id">
          <td>{{ item.name }}</td>
          <td>{{ item.contractType }}</td>
          <td>{{ item.phone }}</td>
          <td>{{ item.email }}</td>
          <td>{{ item.proposal }}</td>
          <td>
            <button class="btn btn-warning btn-sm" @click="openEditModal(item)">
              แก้ไข
            </button>
            |
            <button class="btn btn-danger btn-sm" @click="deleteContract(item.contract_id)">
              ลบ
            </button>
          </td>
        </tr>
        <tr v-if="!loading && !error && contracts.length === 0">
          <td colspan="6">ยังไม่มีข้อมูล Contract</td>
        </tr>
      </tbody>
    </table>

    <div v-if="loading" class="text-center">
      <p>กำลังโหลดข้อมูล...</p>
    </div>

    <div v-if="error" class="alert alert-danger">
      {{ error }}
    </div>

    <div class="modal fade" id="editContractModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">แก้ไขข้อมูล Contract</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="saveContract">
              <div class="mb-3">
                <label class="form-label">ชื่อ</label>
                <input v-model.trim="editContract.name" type="text" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">ประเภทผู้ใช้</label>
                <input v-model.trim="editContract.contractType" type="text" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">เบอร์โทร</label>
                <input v-model.trim="editContract.phone" type="tel" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">อีเมล</label>
                <input v-model.trim="editContract.email" type="email" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">ข้อเสนอที่จะเสนอ</label>
                <textarea v-model.trim="editContract.proposal" class="form-control" rows="4" required></textarea>
              </div>
              <button type="submit" class="btn btn-success">บันทึกการแก้ไข</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from "vue";

const API_URL = "http://localhost/Praject-V.2/php_api/showcontract.php";

export default {
  name: "ContractList",

  setup() {
    const contracts = ref([]);
    const loading = ref(true);
    const error = ref(null);
    const editContract = ref({});
    let editModal = null;

    const fetchData = async () => {
      try {
        const response = await fetch(API_URL);

        if (!response.ok) {
          throw new Error("ไม่สามารถดึงข้อมูลได้");
        }

        const result = await response.json();

        if (!result.success || !Array.isArray(result.data)) {
          throw new Error(result.message || "รูปแบบข้อมูลไม่ถูกต้อง");
        }

        contracts.value = result.data.map(item => ({
          ...item,
          name: item.name || "",
          proposal: item.proposal || ""
        }));
      } catch (err) {
        error.value = err.message;
      } finally {
        loading.value = false;
      }
    };

    onMounted(() => {
      fetchData();
      const modalEl = document.getElementById("editContractModal");
      editModal = new window.bootstrap.Modal(modalEl);
    });

    const openEditModal = (contract) => {
      editContract.value = { ...contract };
      editModal.show();
    };

    const saveContract = async () => {
      try {
        const response = await fetch(API_URL, {
          method: "PUT",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(editContract.value)
        });
        const result = await response.json();

        if (!response.ok || !result.success) {
          throw new Error(result.message || "แก้ไขข้อมูลไม่สำเร็จ");
        }

        await fetchData();
        editModal.hide();
        alert(result.message);
      } catch (err) {
        alert("เกิดข้อผิดพลาด: " + err.message);
      }
    };

    const deleteContract = async (id) => {
      if (!confirm("คุณต้องการลบข้อมูลนี้ใช่หรือไม่?")) return;

      try {
        const response = await fetch(API_URL, {
          method: "DELETE",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ contract_id: id })
        });
        const result = await response.json();

        if (!response.ok || !result.success) {
          throw new Error(result.message || "ลบข้อมูลไม่สำเร็จ");
        }

        contracts.value = contracts.value.filter(contract => String(contract.contract_id) !== String(id));
        alert(result.message);
      } catch (err) {
        alert("เกิดข้อผิดพลาด: " + err.message);
      }
    };

    return {
      contracts,
      loading,
      error,
      editContract,
      openEditModal,
      saveContract,
      deleteContract
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
