<template>
  <div class="employees-container">
    <!-- หัวข้อหน้า -->
    <div class="page-header">
      <h2 class="mb-3">รายชื่อพนักงาน</h2>
      <button class="add-button" type="button" @click="addEmployee">
        Add Employee
      </button>
    </div>
    <form v-if="showForm" class="employee-form" @submit.prevent="saveEmployee">
      <input v-model.trim="form.firstName" placeholder="First name" required />
      <input v-model.trim="form.lastName" placeholder="Last name" required />
      <input v-model.trim="form.phone" placeholder="Phone" required />
      <input v-model.trim="form.username" placeholder="Username" required />
      <input v-model="form.password" type="password" placeholder="Password" required />
      <button class="save-button" type="submit" :disabled="saving">
        {{ saving ? "Adding..." : "Save Employee" }}
      </button>
    </form>
    <p v-if="message" class="message">{{ message }}</p>
    
    <!-- ตารางแสดงข้อมูลพนักงาน -->
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>ลำดับที่</th>        <!-- index -->
          <th>รหัสพนักงาน</th>     <!-- emp_id -->
          <th>ชื่อ</th>            <!-- firstName -->
          <th>นามสกุล</th>        <!-- lastName -->
          <th>เบอร์โทร</th>       <!-- phone -->
          <th>ชื่อผู้ใช้</th>      <!-- username -->
        </tr>
      </thead>

      <tbody>
        <!-- วนลูปข้อมูล employees -->
        <tr v-for="(item,index) in employees" :key="item.emp_id">
          <td>{{ index + 1 }}</td>       <!-- แสดงลำดับที่ (เริ่มจาก 1) -->
          <td>{{ item.emp_id }}</td>      <!-- รหัสพนักงาน -->
          <td>{{ item.firstName }}</td>   <!-- ชื่อ -->
          <td>{{ item.lastName }}</td>    <!-- นามสกุล -->
          <td>{{ item.phone }}</td>       <!-- เบอร์โทร -->
          <td>{{ item.username }}</td>    <!-- ชื่อผู้ใช้ -->
        </tr>
      </tbody>
    </table>

    <!-- Loading: แสดงระหว่างรอข้อมูล -->
    <div v-if="loading" class="text-center">
      <p>กำลังโหลดข้อมูล...</p>
    </div>

    <!-- Error: แสดงเมื่อเกิดข้อผิดพลาด -->
    <div v-if="error" class="alert alert-danger">
      {{ error }}
    </div>
  </div>
</template>

<script>
// import ฟังก์ชันจาก Vue (Composition API)
import { reactive, ref, onMounted } from "vue";

export default {
  name: "employeesList", // ชื่อ component

  setup() {
    // -----------------------------
    // state (ตัวแปร reactive)
    // -----------------------------
    const employees = ref([]); // เก็บข้อมูลพนักงาน (array)
    const loading = ref(true); // สถานะโหลดข้อมูล
    const error = ref(null);   // เก็บ error
    const message = ref("");
    const showForm = ref(false);
    const saving = ref(false);
    const form = reactive({ firstName: "", lastName: "", phone: "", username: "", password: "" });

    const addEmployee = () => {
      showForm.value = true;
      message.value = "";
      error.value = null;
    };

    const saveEmployee = async () => {
      saving.value = true;
      message.value = "";
      error.value = null;

      try {
        const response = await fetch("http://localhost/Praject-V.2/php_api/add_employees.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(form)
        });
        const result = await response.json();

        if (!response.ok || !result.success) {
          throw new Error(result.message || "เพิ่มพนักงานไม่สำเร็จ");
        }

        message.value = "เพิ่มพนักงานแล้ว";
        showForm.value = false;
        Object.keys(form).forEach((key) => { form[key] = ""; });
        await fetchdata();
      } catch (err) {
        error.value = err.message;
      } finally {
        saving.value = false;
      }
    };

    // -----------------------------
    // ฟังก์ชันดึงข้อมูลจาก API
    // -----------------------------
    const fetchdata = async () => {
      try {
        // เรียก API (PHP)
        const response = await fetch("http://localhost/Praject-V.2/php_api/show_employees.php");

        // ตรวจสอบว่าการเรียกสำเร็จหรือไม่
        if (!response.ok) {
          throw new Error("ไม่สามารถดึงข้อมูลได้");
        }

        // แปลง response เป็น JSON
        const result = await response.json();

        if (!result.success || !Array.isArray(result.data)) {
          throw new Error(result.message || "รูปแบบข้อมูลพนักงานไม่ถูกต้อง");
        }

        employees.value = result.data;

      } catch (err) {
        // ถ้า error ให้เก็บข้อความไว้แสดง
        error.value = err.message;

      } finally {
        // ไม่ว่าจะสำเร็จหรือ error ให้หยุด loading
        loading.value = false;
      }
    };

    // -----------------------------
    // lifecycle: ทำงานเมื่อ component โหลดเสร็จ
    // -----------------------------
    onMounted(() => {
      fetchdata(); // เรียก API ทันที
    });

    // -----------------------------
    // return ค่าไปใช้ใน template
    // -----------------------------
    return {
      employees,
      loading,
      error,
      message,
      addEmployee,
      showForm,
      saving,
      form,
      saveEmployee
    };
  }
};
</script>

<style scoped>
.employees-container {
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
  border: 0;
  border-radius: 4px;
  background: #198754;
  color: #fff;
  cursor: pointer;
}

.message {
  color: #198754;
}

.employee-form {
  display: grid;
  grid-template-columns: repeat(5, 1fr) auto;
  gap: 8px;
  margin: 16px 0;
}

.employee-form input,
.save-button {
  min-width: 0;
  padding: 9px;
}

.save-button {
  border: 0;
  border-radius: 4px;
  background: #0d6efd;
  color: #fff;
  cursor: pointer;
}

@media (max-width: 700px) {
  .employee-form {
    grid-template-columns: 1fr;
  }
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
