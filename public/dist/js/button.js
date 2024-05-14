async function deleted(id, route) {
    const isConfirmed = await sweetDeleted();
    if (isConfirmed) {
        const btnDelete = document.getElementById('btn-delete' + id);
        btnDelete.innerHTML = "<i class='fa fa-spinner fa-spin'></i>";
        btnDelete.disabled = true;

        try {
            const response = await fetch(route, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            });

            const result = await response.json();

            if (result?.status) {
                swal.fire('success', result.message, 'success').then(() => {
                    window.location.reload();
                });
            } else {
                swal.fire('error', result.message, 'error');
                btnDelete.innerHTML = "<i class='fas fa-trash-alt'></i>";
                btnDelete.disabled = false;
            }
        } catch (error) {
            swal.fire('error', 'Error not found, Reload your page', 'error');
            btnDelete.innerHTML = "<i class='fas fa-trash-alt'></i>";
            btnDelete.disabled = false;
        }
    }
}


const sweetDeleted = async (head = 'Are you sure?', title = 'Deleted this data !', buttonText = 'Yes, delete it!') => {
    try {
        const result = await Swal.fire({
            title: head,
            text: title,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: buttonText
        });

        return result.isConfirmed;
    } catch (error) {
        console.log(error);
        return false;
    }
}