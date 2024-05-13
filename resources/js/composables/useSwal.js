import Swal from 'sweetalert2'
export function useSwal() {
    function confirm(title, text, icon = 'warning') {
        return Swal.fire({
            title: title,
            text: text,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        })
    }

    function alert(text) {
        return Swal.fire({
            title: "Warning",
            icon: "warning",
            text
        })
    }

    return {
        confirm,
        alert
    }
}
