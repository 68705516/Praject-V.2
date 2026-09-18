<template>
	<main class="add-contract">
		<h2>Add Contract</h2>
		<form @submit.prevent="addContract">
			<input v-model.trim="form.studentId" placeholder="Student ID" required />
			<input v-model.trim="form.studentName" placeholder="Student Name" required />
			<input v-model.trim="form.contractType" placeholder="Contract Type" required />
			<input v-model.trim="form.startDate" type="date" placeholder="Start Date" required />

			<input v-model.trim="form.phone" placeholder="Phone" required />
			<input v-model="form.email" type="email" placeholder="Email" required />
			<button type="submit" :disabled="loading">
				{{ loading ? "Adding..." : "Add Contract" }}
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
			studentId: "",
			contractType: "",
			startDate: "",
			phone: "",
			email: ""
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
button {
	padding: 10px;
	font: inherit;
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
