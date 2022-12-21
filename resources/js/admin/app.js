import { h, createApp } from 'vue';
import { createExpandableSidebar } from '@core/utils/aside';
const aside = document.getElementById('aside');
document.addEventListener('DOMContentLoaded', () => {
    createExpandableSidebar('#aside');
});




