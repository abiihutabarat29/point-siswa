function alertToastr(message) {
    iziToast.show({
        color: "#71dd37",
        messageColor: "#ffffff",
        message: message,
        timeout: 5000,
        // resetOnHover: true,
        transitionIn: "flipInX",
        transitionOut: "flipOutX",
        position: "topRight",
        progressBarColor: "#ffffff",
    });
}

function alertToastrErr(message) {
    iziToast.show({
        color: "#f73131",
        titleColor: "#ffffff",
        title: "<i class='bx bx-sad'></i> Error",
        messageColor: "#ffffff",
        message: message,
        timeout: 3000,
        resetOnHover: true,
        transitionIn: "flipInX",
        transitionOut: "flipOutX",
        position: "topRight",
        progressBarColor: "rgb(255, 0, 0)",
        buttons: [
            [
                "<button type='button' class='btn btn-outline-danger text-white'>Close</button>",
                function (instance, toast) {
                    instance.hide(
                        {
                            transitionOut: "fadeOutUp",
                        },
                        toast
                    );
                },
            ],
        ],
    });
}

function DataTable(ajaxUrl, columns, columnDefs) {
    const table = $(".datatable").DataTable({
        serverSide: true,
        ajax: ajaxUrl,
        columns: columns,
        columnDefs: columnDefs,
        displayLength: 10,
        lengthMenu: [10, 25, 50, 75, 100],
        processing: true,
        language: {
            processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
        },
    });
    return table;
}

function createModel(createHeading) {
    $("#create").click(function () {
        $("#saveBtn").val("create");
        $('#fileSection').hide();
        $("#modelHeading").html(createHeading);
        $("#ajaxForm").trigger("reset");
        $("#hidden_id").val("");
        $("#ajaxModel").offcanvas("show");
        $("#modal").modal("show");
        $("#default").attr("src", "img/image-default.jpg");
    });
}

function editModel(editUrl, editHeading, field) {
    $("body").on("click", ".edit", function () {
        const editId = $(this).data("id");
        $.get(editUrl + "/" + editId + "/edit", function (data) {
            $("#ajaxForm").trigger("reset");
            $("#saveBtn").val("edit");
            $("#hidden_id").val(data.id);
            $("#modelHeading").html(editHeading);
            $.each(field, function (index, value) {
                $("#" + value).val(data[value]);
            });
            if (data.role_id == 1 || data.role_id == 2) {
                $('#role-dropdown').show();
            } else {
                $('#role-dropdown').hide();
            }
            if (data.file_surat) {
                var fileUrl = "/download/" + editId;
                $('#fileLink').attr('href', fileUrl).text("Download");
                $('#fileSection').show();
            } else {
                $('#fileSection').hide();
            }
        });
    });
}

function saveBtn(urlStore, table) {
    $("#saveBtn").click(function (e) {
        e.preventDefault();
        $(this).html(
            "<span class='spinner-border spinner-border-sm'></span><span class='visually-hidden'><i> menyimpan...</i></span>"
        );

        $.ajax({
            data: $("#ajaxForm").serialize(),
            url: urlStore,
            type: "POST",
            dataType: "json",
            success: function (data) {
                if (data.errors) {
                    $("#info-error").html("");
                    $.each(data.errors, function (key, value) {
                        console.log(value);
                        $("#info-error").show();
                        $("#info-error").append(
                            "<strong><li>" + value + "</li></strong>"
                        );
                        $("#info-error").fadeOut(5000);
                        $("#saveBtn").html("Simpan");
                    });
                } else {
                    $("#ajaxModel").offcanvas("hide");
                    $("#modal").modal("hide");
                    table.draw();
                    alertToastr(data.success);
                    $("#saveBtn").html("Simpan");
                }
            },
        });
    });
}

function saveImage(urlStore, table) {
    $("#saveBtn").click(function (e) {
        e.preventDefault();
        $(this).html(
            "<span class='spinner-border spinner-border-sm'></span><span class='visually-hidden'><i> menyimpan...</i></span>"
        );

        const form = $("#ajaxForm")[0];
        const data = new FormData(form);

        $.ajax({
            data: data,
            url: urlStore,
            type: "POST",
            dataType: "json",
            contentType: false,
            processData: false,
            success: function (data) {
                var errorAlert = $("#info-error");
                errorAlert.html("");
                if (data.errors) {
                    $.each(data.errors, function (key, value) {
                        errorAlert.append(
                            "<strong><li>" + value + "</li></strong>"
                        );
                    });
                    errorAlert.show().fadeOut(5000);
                } else {
                    errorAlert.hide();
                    $("#ajaxModel").offcanvas("hide");
                    $("#modal").modal("hide");
                    table.draw();
                    alertToastr(data.success);
                }
                $("#saveBtn").html("Simpan");
            },
        });
    });
}

function Delete(fitur, editUrl, deleteUrl, table) {
    $("body").on("click", ".delete", function () {
        const deleteId = $(this).data("id");
        $("#modelHeadingHps").html("Hapus");
        $("#fitur").html(fitur);
        $("#ajaxModelHps").modal("show");
        $.get(editUrl + "/" + deleteId + "/edit", function (data) {
            $("#field").html(data.name);
        });
        $("#hapusBtn").click(function (e) {
            e.preventDefault();
            const csrfToken = $('meta[name="csrf-token"]').attr("content");
            $(this).html(
                "<span class='spinner-border spinner-border-sm'></span><span class='visually-hidden'><i> menghapus...</i></span>"
            );
            $.ajax({
                type: "DELETE",
                url: deleteUrl + "/" + deleteId,
                data: {
                    _token: csrfToken,
                },
                success: function (data) {
                    if (data.errors) {
                        $(".alert-danger").html("");
                        $.each(data.errors, function (key, value) {
                            $(".alert-danger").show();
                            $(".alert-danger").append(
                                "<strong><li>" + value + "</li></strong>"
                            );
                            $(".alert-danger").fadeOut(5000);
                            $("#hapusBtn").html(
                                "<i class='fa fa-trash'></i>Hapus"
                            );
                        });
                    } else {
                        if (table) {
                            table.draw();
                        }
                        alertToastr(data.success);
                        $("#hapusBtn").html("<i class='fa fa-trash'></i>Hapus");
                        $("#ajaxModelHps").modal("hide");
                    }
                },
            });
        });
    });
}

function Image(name) {
    $(document).ready(function () {
        $("#" + name).change(function () {
            var input = this;
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#" + name + "Preview").attr("src", e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        });
    });
}

function importModel(importHeading) {
    $("#import").click(function () {
        $("#importHeading").html(importHeading);
        $("#modal-import").modal("show");
        $("#FormImport").trigger("reset");
    });
}

function saveFile(urlStore, table) {
    $("#saveFile").click(function (e) {
        e.preventDefault();
        $(this).html(
            "<span class='spinner-border spinner-border-sm'></span><span class='visually-hidden'><i> menyimpan...</i></span>"
        );
        var form = $("#FormImport")[0];
        var data = new FormData(form);
        $.ajax({
            data: data,
            url: urlStore,
            type: "POST",
            dataType: "json",
            contentType: false,
            processData: false,
            success: function (data) {
                if (data.errors) {
                    $(".alert-danger").html("");
                    $.each(data.errors, function (key, value) {
                        $(".alert-danger").show();
                        $(".alert-danger").append(
                            "<strong><li>" + value + "</li></strong>"
                        );
                        $(".alert-danger").fadeOut(5000);
                        $("#saveFile").html("Simpan");
                    });
                } else {
                    table.draw();
                    alertToastr(data.success);
                    $("#saveFile").html("Simpan");
                    $("#modal-import").modal("hide");
                }
            },
        });
    });
}

function Scan(urlScan, table) {

    $("body").on("click", ".scan", function () {
        const siswaId = $(this).data("id");
        $("#modalHeading").html("Pindai Kode QR");
        $("#modal-scan").modal("show");
        $("#qr-message").html("Arahkan kamera ke kode QR");

        $("#modal-scan").on("hidden.bs.modal", function () {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.clear();
            }
            $("#result-qr").empty();

            $("#modal-scan").off("hidden.bs.modal");
        });

        function onScanSuccess(decodedText, decodedResult) {
            $("#result-qr").html(decodedText);
            let qrCode = decodedText;
            const csrfToken = $('meta[name="csrf-token"]').attr("content");
            $.ajax({
                url: urlScan,
                type: "POST",
                data: {
                    _token: csrfToken,
                    siswa_id: siswaId,
                    nisn: qrCode
                },
                success: function (response) {
                    if (response.errors) {
                        $("#info-error").html("");
                        $("#info-error").show();
                        $("#info-error").append(
                            "<strong>" + response.errors + "</strong>"
                        );
                        $("#info-error").fadeOut(5000);
                    } else {
                        $("#modal-scan").modal("hide");
                        if (response.success) {
                            alertToastr(response.success);
                        }
                        table.draw();
                    }
                },
            });
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
            fps: 10,
            qrbox: {
                width: 200,
                height: 200
            }
        },
            false
        );
        html5QrcodeScanner.render(onScanSuccess);
    });
}

function Review(dokumenUrl, dokumenPath) {
    $("body").on("click", ".review", function () {
        var suratId = $(this).data("id");
        $.get(dokumenUrl + "/" + suratId, function (data) {
            $("#dokumen").empty();
            $.each(data, function (index, value) {
                $("#reviewModal").modal("show");
                $("#dokumen").append(
                    '<div class="form-group">' +
                    '<iframe src="' + dokumenPath + '/' + value.file_surat +
                    '" style="width:100%; height:600px;"></iframe>' +
                    '</div>'
                );
            });
        });
    });
}

