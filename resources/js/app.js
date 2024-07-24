
import $ from 'jquery';
window.$ = $;

import Alpine from 'alpinejs'
// If you want Alpine's instance to be available everywhere.
window.Alpine = Alpine
Alpine.start()

import Swal from 'sweetalert2';
window.Swal = Swal

import {notification} from './notifications.js'
window.notification=notification

document.addEventListener("DOMContentLoaded", async (event) => {
    const toastString = localStorage.getItem("notification");
    if(toastString){
        Swal.fire(JSON.parse(toastString));
    }
    
    localStorage.removeItem("notification");
})