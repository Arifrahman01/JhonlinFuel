
Livewire.on('success', (messages) => {
    if (messages.length > 0) {
        const firstMessage = messages[0];
        Swal.fire('Success', firstMessage, 'success');
    } else {
        Swal.fire('Success', 'Operation completed successfully', 'success');
    }
});
Livewire.on('error', (messages) => {
    if (messages.length > 0) {
        const firstMessage = messages[0];
        Swal.fire('Error', firstMessage, 'error');
    } else {
        Swal.fire('Error', 'An error occurred', 'error');
    }
});


Livewire.on('closeModal', () => {
    var modalElement = document.querySelector('#modal-large');
    console.log(modalElement);
    var modal = bootstrap.Modal.getInstance(modalElement);
    modal.hide();
});