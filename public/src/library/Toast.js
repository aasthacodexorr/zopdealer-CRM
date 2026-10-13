import Swal from 'sweetalert2';
import 'sweetalert2/src/sweetalert2.scss'

class Toast {
    constructor(title){
        this.toast = Swal.mixin({
            showConfirmButton: false,
            position: "bottom",
            toast: true,
            timerProgressBar: true,
            confirmButtonText: "",
            title: title,
            onBeforeOpen: () => {
                this.toast.showLoading();
            },
          })
    }

    reset(attributes){
        this.toast = Swal.mixin(attributes);
    }

    fire(){
        this.toast.fire();
    }

    success(title){
        this.toast.update({
            title: title,
            icon: "success",
          })
    }

    fail(title){
        this.toast.update({
            title: title,
            icon: "error",
          })
    }

    close(){
        const vm = this;
        setTimeout(() => {
            vm.toast.close()
        },1000)
    }

}

export default Toast;



