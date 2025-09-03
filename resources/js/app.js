
import { createApp } from 'vue';
import ImageUploader from './components/image-uploader.vue';


const vueUpload = document.getElementById('vue-upload');
if (vueUpload) {
	const app = createApp({});
	app.component('image-uploader', ImageUploader);
	app.mount('#vue-upload');
}

import './bootstrap';
