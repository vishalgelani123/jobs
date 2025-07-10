function deleteRow(url, token, tableId) {
    Swal.fire({
        //title: "Are you sure?",
        text: "Are you sure want to delete?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#398a28",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'post', url: url, data: {
                    '_token': token,
                }, success: function (response) {
                    if (response.status === true) {
                        Swal.fire({
                            title: "Deleted!",
                            text: "Record has been deleted.",
                            icon: "success",
                            timer: 2000,
                            showConfirmButton: false
                        });
                        $('#' + tableId).DataTable().draw();
                    } else {
                        Swal.fire({
                            text: response.message, icon: "warning",
                        });
                    }
                    setTimeout(function () {
                        location.reload();
                    }, 500)
                },
            });
        }
    });
}

// Disabled enter for submitting modal form
$(document).ready(function () {
    $(window).keydown(function (event) {
        let isModalOpen = $('.modal').hasClass('show');
        if (event.keyCode == 13 && isModalOpen) {
            event.preventDefault();
            return false;
        }
    });
});


//****** Scroll To Top Button ******//
const scrollToTopButton = document.getElementById('scrollToTop');
        window.onscroll = function () {
            if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
                scrollToTopButton.style.display = "block";
            } else {
                scrollToTopButton.style.display = "none";
            }
        };

        scrollToTopButton.addEventListener('click', function () {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });


//****** Remove autofilled input ******//
/*
$(document).on('show.bs.modal', function (event) {
    let modal = $(event.target);
    modal.find('input').each(function () {
        if ($(this).attr('name') !== '_token' && $(this).attr('type') !== 'radio' && $(this).attr('type') !== 'checkbox') {
            $(this).val('');
        }
    });
});
*/
