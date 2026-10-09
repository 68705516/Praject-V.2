<template>
	<main class="add-contract">
		<h2>เพิ่มข้อมูล Contract</h2>
		<form @submit.prevent="addContract">
			<label>
				ชื่อ
				<input v-model.trim="form.name" type="text" required />
			</label>
			<label>
				ประเภทผู้ใช้
				<input v-model.trim="form.contractType" type="text" required />
			</label>
			<label>
				เบอร์โทร
				<input v-model.trim="form.phone" type="tel" required />
			</label>
			<label>
				อีเมล
				<input v-model.trim="form.email" type="email" required />
			</label>
			<label>
				ข้อเสนอที่จะเสนอ
				<textarea v-model.trim="form.proposal" rows="4" required></textarea>
			</label>
			<button type="submit" :disabled="loading">
				{{ loading ? "กำลังบันทึก..." : "บันทึกข้อมูล" }}
			</button>
		</form>
		<p v-if="message" class="message">{{ message }}</p>
		<p v-if="error" class="error">{{ error }}</p>
	</main>
</template>

<script>
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";

export default {
	name: "AddContracts",
	setup() {
		const router = useRouter();
		const loading = ref(false);
		const message = ref("");
		const error = ref("");
		const form = reactive({
			name: "",
			contractType: "",
			phone: "",
			email: "",
			proposal: ""
		});

		const addContract = async () => {
			loading.value = true;
			message.value = "";
			error.value = "";

			try {
				const response = await fetch("http://localhost/Praject-V.2/php_api/add_contract.php", {
					method: "POST",
					headers: { "Content-Type": "application/json" },
					body: JSON.stringify(form)
				});
				const result = await response.json();

				if (!response.ok || !result.success) {
					throw new Error(result.message || "เพิ่มสัญญาไม่สำเร็จ");
				}

				message.value = "เพิ่มสัญญาแล้ว";
				setTimeout(() => router.push("/contract"), 500);
			} catch (err) {
				error.value = err.message;
			} finally {
				loading.value = false;
			}
		};

		return { form, loading, message, error, addContract };
	}
};
</script>

<style scoped>
.add-contract {
	width: min(420px, calc(100% - 32px));
	margin: 40px auto;
}

form {
	display: grid;
	gap: 12px;
}

input,
textarea,
button {
	width: 100%;
	padding: 10px;
	font: inherit;
}

label {
	display: grid;
	gap: 6px;
	text-align: left;
}

button {
	border: 0;
	border-radius: 4px;
	background: #198754;
	color: #fff;
	cursor: pointer;
}

button:disabled {
	opacity: 0.6;
	cursor: wait;
}

.message { color: #198754; }
.error { color: #dc3545; }
</style>
