import './bootstrap';
import { createApp } from 'vue';
import PosComponent from './components/Pos.vue';

const app = createApp({});
app.component('pos-component', PosComponent);
app.mount('#app');