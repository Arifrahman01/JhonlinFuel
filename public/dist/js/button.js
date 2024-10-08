const sweetDeleted = async ({ head = 'Are you sure?', title = 'Deleted this data !', buttonText = 'Yes, delete it!', id ,textLoadong = ''}) => {
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
        
        if (result.isConfirmed) {
            if (id) {
                const btnDelete = document.getElementById('btn-delete' + id);
                if (btnDelete) {
                    btnDelete.innerHTML = "<i class='fa fa-spinner fa-spin'></i> "+textLoadong;
                    btnDelete.disabled = true;
                } else {
                    console.warn(`Button with id 'btn-delete${id}' not found`);
                }
            } else {
                console.warn('No id provided for button state management');
            }
            return true;
        }
        return false;
    } catch (error) {
        console.log(error);
        return false;
    }
};


const sweetReset = async ({ head = 'Are you sure?', title = 'Reset this password !', buttonText = 'Yes, Reset it!', id }) => {
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
        
        if (result.isConfirmed) {
            if (id) {
                const btnDelete = document.getElementById('btn-reset' + id);
                if (btnDelete) {
                    btnDelete.innerHTML = "<i class='fa fa-spinner fa-spin'></i>";
                    btnDelete.disabled = true;
                } else {
                    console.warn(`Button with id 'btn-reset${id}' not found`);
                }
            } else {
                console.warn('No id provided for button state management');
            }
            return true;
        }
        return false;
    } catch (error) {
        console.log(error);
        return false;
    }
};

const sweetPosting = async ({ head = 'Are you sure?', title = 'Posting this transaction !', buttonText = 'Yes, do it!', id ,textLoadong = ''}) => { 
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
        
        if (result.isConfirmed) {
            if (id) {
                const btnPosting = document.getElementById('btn-posting' + id);
                if (btnPosting) {
                    btnPosting.innerHTML = "<i class='fa fa-spinner fa-spin'></i> "+ textLoadong;
                    btnPosting.disabled = true;
                    btnPosting.classList.add('disabled');
                } else {
                    console.warn(`Button with id 'btn-posting${id}' not found`);
                }
            } else {
                console.warn('No id provided for button state management');
            }
            return true;
        }
        return false;
    } catch (error) {
        console.log(error);
        return false;
    }
};