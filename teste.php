
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

onsubmit="exibirPopup()"


<script>
    function exibirPopup() {
        Swal.fire({
            icon: 'warning',
            title: 'Carregando...',
            text: 'Aguarde enquanto finalizamos sua solicitação!',
            showCancelButton: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });
    }
</script>