<template>
	<main class="add-employee">
		<h2>Add Employee</h2>
		<form @submit.prevent="addEmployee">
			<input v-model.trim="form.firstName" placeholder="First name" required />
			<input v-model.trim="form.lastName" placeholder="Last name" required />
			<input v-model.trim="form.phone" placeholder="Phone" required />
			<input v-model.trim="form.username" placeholder="Username" required />
			<input v-model="form.password" type="password" placeholder="Password" required />
			<button type="submit" :disabled="loading">
			{{ loading ? "Adding..." : "Add Employee" }}
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
	name: "AddEmployees",
	setup() {
		const router = useRouter();
		const loading = ref(false);
		const message = ref("");
		const error = ref("");
		const form = reactive({ firstName: "", lastName: "", phone: "", username: "", password: "" });

		const addEmployee = async () => {
			loading.value = true;
			message.value = "";
			error.value = "";

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
				setTimeout(() => router.push("/employees"), 500);
			} catch (err) {
				error.value = err.message;
			} finally {
				loading.value = false;
			}
		};

		return { form, loading, message, error, addEmployee };
	}
};
</script>

<style scoped>
.add-employee {
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
