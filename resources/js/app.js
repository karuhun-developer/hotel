import {
    Livewire,
    Alpine,
} from "../../vendor/livewire/livewire/dist/livewire.esm";
import Swal from "sweetalert2";

window.Swal = Swal;
window.Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
});
window.debounce = (callback, wait) => {
    let timeoutId = null;
    return (...args) => {
        window.clearTimeout(timeoutId);
        timeoutId = window.setTimeout(() => {
            callback(...args);
        }, wait);
    };
};

// Livewire alert
Livewire.on("alert", (params) => {
    window.Toast.fire({
        icon: params.type ?? "success",
        title: params.message,
    });
});

// Livewire confirmation dialog
Livewire.on("confirm", (params) => {
    let swalParams = {
        title: params.title ?? "Are you sure?",
        text: params.message ?? `You won't be able to revert this`,
        icon: params.icon ?? "warning",
        showCancelButton: true,
        confirmButtonColor: "#262626",
        cancelButtonColor: "#e91e63",
        confirmButtonText: params.confirmText ?? "Yes",
        cancelButtonText: params.cancelText ?? "Cancel",
    };

    // If params withDenyButton
    if (params?.withDenyButton) {
        swalParams.showDenyButton = true;
        swalParams.denyButtonColor = params?.denyColor ?? "#ffc107";
        swalParams.denyButtonText = params?.denyText ?? "Deny";
    }

    window.Swal.fire(swalParams).then((result) => {
        if (result.isConfirmed) {
            Livewire.dispatch(params.function, { id: params.id });
        }

        // If params withDenyButton
        if (result?.isDenied) {
            Livewire.dispatch(params?.denyFunction, { id: params?.denyId });
        }
    });
});

// Livewire setValueById
Livewire.on("setValueById", (params) => {
    setTimeout(() => {
        document.getElementById(params.id).value = params.value;
    }, 500);
});

// Livewire toast
Livewire.on("toast", (params) => {
    window.Toast.fire({
        icon: params.type ?? "success",
        title: params.message,
    });
});

Livewire.start();
