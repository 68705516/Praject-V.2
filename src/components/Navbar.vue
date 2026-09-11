<template>
  <nav class="navbar">
    <div class="container-fluid">
      <router-link class="navbar-brand" to="/" @click="closeMenu">
        Romes House
      </router-link>

      <button
        class="navbar-toggler"
        type="button"
        aria-controls="offcanvasDarkNavbar"
        :aria-expanded="menuOpen"
        aria-label="เปิดเมนู"
        @click="menuOpen = true"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div v-if="menuOpen" class="offcanvas-backdrop" @click="closeMenu"></div>
      <aside
        id="offcanvasDarkNavbar"
        class="offcanvas"
        :class="{ 'offcanvas-open': menuOpen }"
        aria-labelledby="offcanvasDarkNavbarLabel"
      >
        <div class="offcanvas-header">
          <h5 id="offcanvasDarkNavbarLabel" class="offcanvas-title">เมนูหลัก</h5>
          <button class="btn-close" type="button" aria-label="ปิดเมนู" @click="closeMenu">
            &times;
          </button>
        </div>

        <div class="offcanvas-body">
          <ul class="navbar-nav">
            <li class="nav-item">
              <router-link class="nav-link" to="/" @click="closeMenu">Home</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/customers" @click="closeMenu">Customers</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/employees" @click="closeMenu">Employees</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/about" @click="closeMenu">About</router-link>
            </li>
          </ul>

          <form class="search-form" role="search" @submit.prevent>
            <input class="search-input" type="search" placeholder="Search" aria-label="Search" />
            <button class="search-button" type="submit">Search</button>
          </form>
        </div>
      </aside>
    </div>
  </nav>
</template>

<script>
import { ref } from "vue";

export default {
  name: "Navbar",
  setup() {
    const menuOpen = ref(false);

    const closeMenu = () => {
      menuOpen.value = false;
    };

    return { menuOpen, closeMenu };
  }
};
</script>

<style scoped>
.navbar {
  position: fixed;
  top: 0;
  right: 0;
  left: 0;
  z-index: 1000;
  background: #212529;
  color: #fff;
}

.container-fluid {
  min-height: 64px;
  padding: 0 24px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.navbar-brand,
.nav-link {
  color: #fff;
  text-decoration: none;
}

.navbar-brand {
  font-size: 1.15rem;
  font-weight: 700;
}

.navbar-toggler {
  width: 44px;
  height: 38px;
  padding: 8px;
  border: 1px solid #6c757d;
  border-radius: 6px;
  background: transparent;
  cursor: pointer;
}

.navbar-toggler-icon,
.navbar-toggler-icon::before,
.navbar-toggler-icon::after {
  display: block;
  width: 22px;
  height: 2px;
  background: #fff;
  content: "";
}

.navbar-toggler-icon::before {
  transform: translateY(-7px);
}

.navbar-toggler-icon::after {
  transform: translateY(5px);
}

.offcanvas-backdrop {
  position: fixed;
  inset: 0;
  z-index: 1001;
  background: rgba(0, 0, 0, 0.5);
}

.offcanvas {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  z-index: 1002;
  width: min(320px, 85vw);
  padding: 24px;
  background: #212529;
  transform: translateX(100%);
  transition: transform 0.2s ease;
}

.offcanvas-open {
  transform: translateX(0);
}

.offcanvas-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.offcanvas-title {
  margin: 0;
}

.btn-close {
  border: 0;
  background: transparent;
  color: #fff;
  font-size: 1.8rem;
  line-height: 1;
  cursor: pointer;
}

.offcanvas-body {
  padding-top: 28px;
}

.navbar-nav {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin: 0;
  padding: 0;
  list-style: none;
}

.nav-link {
  display: block;
  padding: 10px 0;
}

.nav-link:hover,
.nav-link.router-link-exact-active {
  color: #42b983;
}

.search-form {
  display: flex;
  gap: 8px;
  margin-top: 24px;
}

.search-input {
  min-width: 0;
  flex: 1;
  padding: 8px 10px;
  border: 1px solid #ced4da;
  border-radius: 4px;
}

.search-button {
  padding: 8px 12px;
  border: 0;
  border-radius: 4px;
  background: #198754;
  color: #fff;
  cursor: pointer;
}
</style>


