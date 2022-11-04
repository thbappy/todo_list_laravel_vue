require('./bootstrap');

import { createApp } from 'vue'
import Index from './components/Index'

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';


const app = createApp({})

app.use(VueSweetalert2);

app.component('Index', Index)

import swal from 'sweetalert2';
window.Swal = swal;
window.Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})

app.mount('#app')
