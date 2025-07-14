import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import { AllCommunityModule, ModuleRegistry } from 'ag-grid-community';

// Register all Community features
ModuleRegistry.registerModules([AllCommunityModule]);
const app = createApp(App);
app.use(router);
app.mount('#app');