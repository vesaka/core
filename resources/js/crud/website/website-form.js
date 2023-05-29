import { createApp } from 'vue';
import ModelForm from '@core/components/website/WebsiteForm.vue'
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

createApp(ModelForm, { type: 'website' })
        .use(Toast, {position: 'bottom-left'})
        .mount('#website-form');
