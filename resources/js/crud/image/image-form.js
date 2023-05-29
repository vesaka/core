import { createApp } from 'vue';
import ModelForm from '@core/components/model/ModelForm.vue'
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

createApp(ModelForm, { type: 'image', alias: 'img' })
        .use(Toast, {position: 'bottom-left'})
        .mount('#image-form');
