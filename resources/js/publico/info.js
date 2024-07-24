import Swal from 'sweetalert2';

const showButton = document.querySelector('#btn-show-acta');
showButton.addEventListener("click", async (event) => {
    window.open(showButton.dataset.acta, '_blank');
    
    /*await Swal.fire({
        html: `
            <iframe src="${showButton.dataset.acta}" class="w-full h-full"></iframe>
        `,
        focusConfirm: false,
        showConfirmButton: false,
        width: '90vw',
        heightAuto:false, 
        customClass: 'swal-height'   
    })*/
})
