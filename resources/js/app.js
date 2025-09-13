
import './bootstrap';

import { createApp } from 'vue';
import CommentApproval from './components/CommentApproval.vue';

// Create Vue app
const app = createApp({});

// Register components
app.component('comment-approval', CommentApproval);

// Mount the app if element exists
const appElement = document.getElementById('app');
if (appElement) {
    app.mount('#app');
}
