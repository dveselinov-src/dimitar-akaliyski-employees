<template>
  <div class="layout">
    <aside class="sidebar">
      <h2 class="nav-title">Menu</h2>
      <nav class="nav-links">
        <RouterLink to="/analysis" class="nav-link">Employees cooperation analysis</RouterLink>
        <RouterLink to="/unit-tests" class="nav-link">Unit tests and description</RouterLink>
      </nav>
    </aside>

    <main class="content">
      <router-view />
    </main>
  </div>
</template>


<style>
.layout {
  min-height: 100vh;
  display: flex;
  background: #f5f7fb;
}

.sidebar {
  width: 280px;
  background: #1f2937;
  color: white;
  padding: 24px 16px;
}

.nav-title {
  margin: 0 0 16px;
  font-size: 1.2rem;
}

.nav-links {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.nav-link {
  color: #d1d5db;
  text-decoration: none;
  padding: 10px 12px;
  border-radius: 6px;
}

.nav-link:hover {
  background: #374151;
  color: white;
}

.nav-link.router-link-active {
  background: #111827;
  color: white;
  font-weight: 600;
}

.content {
  flex: 1;
  padding: 24px;
}

@media (max-width: 900px) {
  .layout {
    flex-direction: column;
  }

  .sidebar {
    width: auto;
  }
}
</style>