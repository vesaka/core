import { createApp } from 'vue';
import ModelForm from '@core/components/model/ModelForm.vue'
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

createApp(ModelForm, { type: window.type })
        .use(Toast, {position: 'bottom-left'})
        .mount('#model-form');