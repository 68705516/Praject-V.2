<template>
  <main class="assets-page">
    <h1 class="assets-title">รายการอุปกรณ์</h1>
    <button class="btn btn-primary btn-sm add-button" type="button" @click="openAddModal">Add+</button>

    <div v-if="error" class="alert alert-danger" role="alert">{{ error }}</div>
    <div v-if="loading" class="loading-state">กำลังโหลดข้อมูล...</div>

    <div v-else class="table-responsive">
      <table class="table table-bordered table-striped assets-table">
        <thead class="table-primary">
          <tr>
            <th class="id-column">ID</th>
            <th class="code-column">รหัสครุภัณฑ์</th>
            <th class="name-column">ชื่อครุภัณฑ์</th>
            <th class="date-column">วันที่ซื้อ</th>
            <th class="price-column">ราคา</th>
            <th class="image-column">รูปภาพ</th>
            <th class="actions-column">การจัดการ</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="assets.length === 0">
            <td colspan="7" class="empty-state">ยังไม่มีข้อมูลอุปกรณ์</td>
          </tr>
          <tr v-for="asset in assets" :key="asset.asset_id">
            <td>{{ asset.asset_id }}</td>
            <td>{{ asset.asset_code }}</td>
            <td>{{ asset.asset_name }}</td>
            <td>{{ asset.purchase_date || "-" }}</td>
            <td>{{ formatPrice(asset.price) }}</td>
            <td>
              <img
                v-if="asset.image"
                class="asset-image"
                :src="`${uploadUrl}${asset.image}`"
                :alt="asset.asset_name"
              />
              <span v-else class="text-secondary">-</span>
            </td>
            <td class="actions-cell">
              <button class="btn btn-warning btn-sm me-1" type="button" @click="openEditModal(asset)">แก้ไข</button>
              <button class="btn btn-danger btn-sm" type="button" @click="deleteAsset(asset.asset_id)">ลบ</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div id="assetModal" class="modal fade" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h2 class="modal-title fs-5">{{ isEditMode ? "แก้ไขครุภัณฑ์" : "เพิ่มครุภัณฑ์" }}</h2>
            <button class="btn-close" type="button" aria-label="ปิด" @click="closeModal"></button>
          </div>
          <form @submit.prevent="saveAsset">
            <div class="modal-body">
              <label class="form-label" for="asset-code">รหัสครุภัณฑ์</label>
              <input id="asset-code" v-model.trim="editForm.asset_code" class="form-control mb-3" required />

              <label class="form-label" for="asset-name">ชื่อครุภัณฑ์</label>
              <input id="asset-name" v-model.trim="editForm.asset_name" class="form-control mb-3" required />

              <label class="form-label" for="purchase-date">วันที่ซื้อ</label>
              <input id="purchase-date" v-model="editForm.purchase_date" class="form-control mb-3" type="date" />

              <label class="form-label" for="asset-price">ราคา</label>
              <input id="asset-price" v-model="editForm.price" class="form-control mb-3" type="number" min="0" step="0.01" />

              <label class="form-label" for="asset-image">รูปภาพ</label>
              <input id="asset-image" class="form-control" type="file" accept=".jpg,.jpeg,.png,.gif,.webp" @change="handleFileUpload" />
              <img
                v-if="isEditMode && editForm.image"
                class="asset-preview mt-3"
                :src="`${uploadUrl}${editForm.image}`"
                :alt="editForm.asset_name"
              />
            </div>
            <div class="modal-footer">
              <button class="btn btn-secondary" type="button" @click="closeModal">ยกเลิก</button>
              <button class="btn btn-primary" type="submit" :disabled="saving">
                {{ saving ? "กำลังบันทึก..." : "บันทึก" }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
import { onMounted, ref } from "vue";

const apiUrl = "http://localhost/Praject-V.2/php_api/api_assets.php";

export default {
  name: "AssetsView",
  setup() {
    const assets = ref([]);
    const loading = ref(true);
    const saving = ref(false);
    const error = ref("");
    const isEditMode = ref(false);
    const editForm = ref(createEmptyAsset());
    const newImageFile = ref(null);
    const uploadUrl = "http://localhost/Praject-V.2/php_api/uploads/";
    let modalInstance;

    function createEmptyAsset() {
      return { asset_id: null, asset_code: "", asset_name: "", purchase_date: "", price: "", image: "" };
    }

    const fetchAssets = async () => {
      loading.value = true;
      error.value = "";
      try {
        const response = await fetch(apiUrl);
        const result = await response.json();
        if (!response.ok || !result.success) throw new Error(result.error || result.message || "โหลดข้อมูลอุปกรณ์ไม่สำเร็จ");
        assets.value = result.data;
      } catch (err) {
        error.value = err.message;
      } finally {
        loading.value = false;
      }
    };

    const showModal = () => {
      const modalElement = document.getElementById("assetModal");
      modalInstance = window.bootstrap.Modal.getOrCreateInstance(modalElement);
      modalInstance.show();
    };

    const openAddModal = () => {
      isEditMode.value = false;
      editForm.value = createEmptyAsset();
      newImageFile.value = null;
      showModal();
      document.getElementById("asset-image").value = "";
    };

    const openEditModal = (asset) => {
      isEditMode.value = true;
      editForm.value = { ...asset, purchase_date: asset.purchase_date || "", price: asset.price || "" };
      newImageFile.value = null;
      showModal();
      document.getElementById("asset-image").value = "";
    };

    const closeModal = () => modalInstance?.hide();

    const handleFileUpload = (event) => {
      newImageFile.value = event.target.files[0] || null;
    };

    const saveAsset = async () => {
      saving.value = true;
      const formData = new FormData();
      formData.append("action", isEditMode.value ? "update" : "add");
      if (isEditMode.value) formData.append("asset_id", editForm.value.asset_id);
      formData.append("asset_code", editForm.value.asset_code);
      formData.append("asset_name", editForm.value.asset_name);
      formData.append("purchase_date", editForm.value.purchase_date);
      formData.append("price", editForm.value.price);
      if (newImageFile.value) formData.append("image", newImageFile.value);

      try {
        const response = await fetch(apiUrl, { method: "POST", body: formData });
        const result = await response.json();
        if (!response.ok || result.error) throw new Error(result.error || "บันทึกข้อมูลไม่สำเร็จ");
        closeModal();
        await fetchAssets();
      } catch (err) {
        error.value = err.message;
      } finally {
        saving.value = false;
      }
    };

    const deleteAsset = async (assetId) => {
      if (!window.confirm("ต้องการลบข้อมูลครุภัณฑ์นี้หรือไม่?")) return;
      const formData = new FormData();
      formData.append("action", "delete");
      formData.append("asset_id", assetId);
      try {
        const response = await fetch(apiUrl, { method: "POST", body: formData });
        const result = await response.json();
        if (!response.ok || result.error) throw new Error(result.error || "ลบข้อมูลไม่สำเร็จ");
        await fetchAssets();
      } catch (err) {
        error.value = err.message;
      }
    };

    const formatPrice = (price) => price == null || price === "" ? "-" : Number(price).toLocaleString("th-TH", { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    onMounted(fetchAssets);

    return { assets, loading, saving, error, isEditMode, editForm, uploadUrl, openAddModal, openEditModal, closeModal, handleFileUpload, saveAsset, deleteAsset, formatPrice };
  }
};
</script>

<style scoped>
.assets-page { width: min(1100px, calc(100% - 40px)); margin: 0 auto 20px; color: #111; font-size: 14px; }
.assets-title { margin: 0 0 12px; font-size: 24px; font-weight: 500; }
.add-button { display: block; margin-bottom: 12px; padding: 6px 12px; font-size: 14px; }
.assets-table { margin-bottom: 0; table-layout: fixed; font-size: 14px; }
.assets-table th, .assets-table td { padding: 10px; border-color: #d6dbe1; vertical-align: top; overflow-wrap: anywhere; }
.assets-table th { font-weight: 600; }
.id-column { width: 5%; }
.code-column { width: 15%; }
.name-column { width: 20%; }
.date-column { width: 14%; }
.price-column { width: 10%; }
.image-column { width: 16%; }
.actions-column { width: 20%; }
.asset-image { display: block; width: 100px; height: 72px; object-fit: contain; }
.actions-cell { white-space: nowrap; }
.actions-cell .btn { padding: 5px 9px; font-size: 13px; }
.empty-state, .loading-state { padding: 14px; text-align: center; }
.asset-preview { display: block; width: 100%; max-height: 220px; object-fit: contain; }
@media (max-width: 700px) { .assets-page { width: calc(100% - 24px); } .assets-table { min-width: 900px; } }
</style>