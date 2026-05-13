const Toast = Swal.mixin({
    toast: true,
    position: "bottom-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
});

//sweetalert delete
$(document).on("click", "#delete", function (e) {
    e.preventDefault();
    var link = $(this).attr("href");
    Swal.fire({
        title: "Are you sure?",
        text: "Delete this data!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Confirm",
    }).then((result) => {
        if (result.value) {
            window.location.href = link;
            Swal.fire("Deleted!", "Data has been deleted.", "success");
        }
    });
});

//permission
$(document).on("click", "#permission", function (e) {
    e.preventDefault();
    var link = $(this).attr("href");
    Swal.fire({
        title: "Are you sure?",
        text: "Do you want change the permission!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Confirm",
    }).then((result) => {
        if (result.value) {
            window.location.href = link;
            Swal.fire("Ok!", "Change the permission", "success");
        }
    });
});

//sweetalert trash
$(document).on("click", "#trash", function (e) {
    e.preventDefault();
    var link = $(this).attr("href");
    Swal.fire({
        title: "Are you sure?",
        text: "Trash this data!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Confirm",
    }).then((result) => {
        if (result.value) {
            window.location.href = link;
            Swal.fire("Trashed!", "Data has been move to trashed.", "success");
        }
    });
});

//sweetalert restore
$(document).on("click", "#restore", function (e) {
    e.preventDefault();
    var link = $(this).attr("href");
    Swal.fire({
        title: "Are you sure?",
        text: "Restore this data!",
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Confirm",
    }).then((result) => {
        if (result.value) {
            window.location.href = link;
            Swal.fire("Restored!", "Data has been restored.", "success");
        }
    });
});

////////////////////////For ecommerce

//sweetalert payment status
$(document).on("click", "#course_status", function (e) {
    e.preventDefault();
    var link = $(this).attr("href");
    Swal.fire({
        title: `Are You Sure?`,
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: `Confirm`,
        denyButtonText: `No`,
    }).then((result) => {
        if (result.value) {
            window.location.href = link;
        }
    });
});
