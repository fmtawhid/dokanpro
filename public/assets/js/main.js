if ($(window).outerWidth() > 1199) {
    $('nav.side-navbar').removeClass('shrink');
}
function signout() {
    window.location.href = '/signout';
}
$("div.alert").delay(3000).slideUp(750);
function confirmDelete() {
    if (confirm("Are you sure want to delete?")) {
        return true;
    }
    return false;
}
function confirmDelete(route) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value === true) {
            window.location.href = route;
        }
    })
}
new DataTable('#dataTable');
$('#summernote').summernote({
    placeholder: 'Type description',
    tabsize: 2,
    height: 100
});
function onlyLetter(evt) {
    var chars = String.fromCharCode(evt.which);
    if (!(/[a-z,A-Z]/.test(chars))) {
        evt.preventDefault();
    }
}
function onlyNumber(evt) {
    var chars = String.fromCharCode(evt.which);
    if (!(/[0-9.]/.test(chars))) {
        evt.preventDefault();
    }
}
