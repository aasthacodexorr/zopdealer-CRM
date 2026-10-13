import Swal from 'sweetalert2';
import 'sweetalert2/src/sweetalert2.scss';

class Notification {
    constructor(title, html){
        this.toast = Swal.mixin({
          title: title,
          html: html,
          width: 700,
          showCloseButton: true,
          showCancelButton: false,
          confirmButtonText: "Dismiss",
          focusConfirm: false
          })
    }

    fire(){
        this.toast.fire();
    }
}

export default Notification;