function alertToastr(message) {
    iziToast.show({
        color: "#272ab9",
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
        messageColor: "#ffffff",
        message: message,
        timeout: 3000,
        resetOnHover: true,
        transitionIn: "flipInX",
        transitionOut: "flipOutX",
        position: "topRight",
        progressBarColor: "#ffffff",
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
        $("#preview").attr("src", "img/blank.jpg");
        $('#role-dropdown').show();
        // $("#default").attr("src", "img/image-default.jpg");
    });
}

function editModel(editUrl, editHeading, field) {
    $("body").on("click", ".edit", function () {
        const editId = $(this).data("id");
        console.log('ID', editId)
        $.get(editUrl + "/" + editId + "/edit", function (data) {
            $("#ajaxForm").trigger("reset");
            $("#saveBtn").val("edit");
            $("#hidden_id").val(data.id);
            $("#modelHeading").html(editHeading);
            $.each(field, function (index, value) {
                $("#" + value).val(data[value]);
                console.log(data)
            });
            if (data.role_id == 6 || data.role_id == 7) {
                $('#role-dropdown').hide();
            } else {
                $('#role-dropdown').show();
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
        $("#ajaxForm").trigger("reset");
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
                        $("#info-error").html("");
                        $.each(data.errors, function (key, value) {
                            $("#info-error").show();
                            $("#info-error").append(
                                "<strong><li>" + value + "</li></strong>"
                            );
                            $("#info-error").fadeOut(5000);
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
                    $("#info-error").html("");
                    $.each(data.errors, function (key, value) {
                        $("#info-error").show();
                        $("#info-error").append(
                            "<strong><li>" + value + "</li></strong>"
                        );
                        $("#info-error").fadeOut(5000);
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

function Detail(url, path, heading) {
    $("body").on("click", ".detail", function () {
        var pointId = $(this).data("id");
        var nama = $(this).data("nama");
        $("#heading-detail").html(heading);
        $("#ajaxModelDetail").modal("show");
        $.ajax({
            url: url + "/" + pointId,
            type: "GET",
            dataType: "json",
            success: function (response) {
                $("#detail").empty();
                var detail = response.detail;
                console.log(detail);
                if (detail) {
                    var imageSrc = detail.foto
                        ? path + "/" + detail.foto
                        : "/img/blank.jpg";

                    var statusBadge;
                    if (detail.status == 1) {
                        statusBadge = '<span class="badge bg-label-success me-1">Terkonfirmasi</span>';
                    } else if (detail.status == 0) {
                        statusBadge = '<span class="badge bg-label-warning me-1">Pending</span>';
                    } else {
                        statusBadge = '<span class="badge bg-label-danger me-1">Ditolak</span>';
                    }

                    var gender = detail.siswa.gender == 'L'
                        ? 'Laki-laki'
                        : 'Perempuan';

                    var tanggal = new Date(detail.created_at);
                    var options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
                    var formattedDate = tanggal.toLocaleDateString('id-ID', options);
                    var formattedTime = tanggal.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

                    var keteranganContent = detail.keterangan
                        ? detail.keterangan
                        : "<small class='text-danger'>Tidak ada catatan.</small>";

                    var alasanContent = detail.status == 2
                        ? "<hr><h6 class='card-title mb-3'>Alasan Penolakan :</h6>" +
                        "<dd><i class='bx bx-user-voice'></i> : " + (detail.alasan ? detail.alasan : "Tidak ada alasan.") + "</dd>"
                        : "";

                    $("#detail").append(
                        "<div class='col-md-12'>" +
                        "<div class='row'>" +
                        "<div class='col-md-4 mb-4'>" +
                        "<div class='cardhg'>" +
                        "<img class='card-img card border border-primary' src='" +
                        imageSrc +
                        "' alt='Foto Pelanggaran' />" +
                        "</div>" +
                        "</div>" +
                        "<div class='col-md-8 mb-2'>" +
                        "<dl class='row'>" +
                        "<dt class='col-sm-4'>Status Verifikasi</dt>" +
                        "<dd class='col-sm-8'>: " + statusBadge + "</dd>" +
                        "<dt class='col-sm-4'>NISN</dt>" +
                        "<dd class='col-sm-8'>: " + detail.siswa.nisn + "</dd>" +
                        "<dt class='col-sm-4'>Nama</dt>" +
                        "<dd class='col-sm-8'>: " + detail.siswa.name + "</dd>" +
                        "<dt class='col-sm-4'>Jenis Kelamin</dt>" +
                        "<dd class='col-sm-8'>: " + gender + "</dd>" +
                        "<dt class='col-sm-4'>Rombel</dt>" +
                        "<dd class='col-sm-8'>: " + detail.rombel.name + "</dd>" +
                        "<dt class='col-sm-4'>Pelanggaran</dt>" +
                        "<dd class='col-sm-8'>: " + detail.pelanggaran.name + "</dd>" +
                        "<dt class='col-sm-4'>Tanggal</dt>" +
                        "<dd class='col-sm-8'>: " + formattedDate + "</dd>" +
                        "<dt class='col-sm-4'>Waktu</dt>" +
                        "<dd class='col-sm-8'>: " + formattedTime + "</dd>" +
                        "<dt class='col-sm-4'>Pelapor</dt>" +
                        "<dd class='col-sm-8'>: " + detail.pelapor + "</dd>" +
                        "<dt class='col-sm-4'>Catatan Keterangan</dt>" +
                        "<dd class='col-sm-12'>" + keteranganContent + "</dd>" +
                        "</dl>" +
                        alasanContent +
                        "</div>" +
                        "</div>" +
                        "</div>" +
                        "<hr>"
                    );
                } else {
                    $("#detail").append("<p>Tidak ada data detail.</p>");
                }
            },
            error: function (error) {
                console.error("Error:", error);
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

function konfirmasiSkor(url, table) {
    $("body").on('click', '.konfirmasi', function () {
        var id = $(this).data('id');
        $("#modal-konfirmasi").data('id', id).modal("show");
    });

    $("#modal-konfirmasi").on('hidden.bs.modal', function () {
        $(this).removeData('id');
        $("#konfirmasiBtn").html("Konfirmasi").removeAttr("disabled");
    });

    $("#konfirmasiBtn").click(function (e) {
        e.preventDefault();

        var id = $("#modal-konfirmasi").data('id');
        if (!id) {
            return;
        }

        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        $(this)
            .html("<span class='spinner-border spinner-border-sm'></span><span class='visually-hidden'></span>")
            .attr("disabled", "disabled");

        $.ajax({
            url: url,
            type: "POST",
            data: {
                _token: csrfToken,
                id: id,
            },
            success: function (data) {
                if (data.errors) {
                    $("#konfirmasiBtn")
                        .html("Konfirmasi")
                        .removeAttr("disabled");
                    alertToastr(data.errors);
                } else {
                    if (table) {
                        table.draw();
                    }
                    alertToastr(data.success);
                    $("#konfirmasiBtn")
                        .html("Konfirmasi")
                        .removeAttr("disabled");
                    $("#modal-konfirmasi").modal("hide");
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                $("#konfirmasiBtn")
                    .html("Konfirmasi")
                    .removeAttr("disabled");
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        });
    });
}

function tolakSkor(url, table) {
    $("body").on('click', '.tolak', function () {
        var id = $(this).data('id');
        $("#ajaxForm").trigger("reset");
        $("#modal-tolak").data('id', id).modal("show");
    });

    $("#modal-tolak").on('hidden.bs.modal', function () {
        $(this).removeData('id');
        $("#tolakBtn").html("Tolak").removeAttr("disabled");
    });

    $("#tolakBtn").click(function (e) {
        e.preventDefault();

        var id = $("#modal-tolak").data('id');
        var alasan = $("#alasan").val();

        var csrfToken = $('meta[name="csrf-token"]').attr("content");

        $(this)
            .html("<span class='spinner-border spinner-border-sm'></span><span class='visually-hidden'></span>")
            .attr("disabled", "disabled");

        $.ajax({
            url: url,
            type: "POST",
            data: {
                _token: csrfToken,
                id: id,
                alasan: alasan,
            },
            success: function (data) {
                if (data.errors) {
                    $("#info-error").html("");
                    $.each(data.errors, function (key, value) {
                        $("#info-error").show();
                        $("#info-error").append(
                            "<strong><li>" + value + "</li></strong>"
                        );
                        $("#info-error").fadeOut(5000);
                        $("#tolakBtn")
                            .html("Tolak")
                            .removeAttr("disabled");
                    });
                    alertToastrErr(data.errors);
                } else {
                    if (table) {
                        table.draw();
                    }
                    alertToastr(data.success);
                    $("#tolakBtn")
                        .html("Tolak")
                        .removeAttr("disabled");
                    $("#modal-tolak").modal("hide");
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                $("#tolakBtn")
                    .html("Tolak")
                    .removeAttr("disabled");
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        });
    });
}

// Fungsi Jam
window.onload = function () {
    jam();
};

function jam() {
    var e = document.getElementById("jam"),
        d = new Date(),
        h,
        m,
        s;
    h = d.getHours();
    m = set(d.getMinutes());
    s = set(d.getSeconds());

    e.innerHTML = h + ":" + m + ":" + s;

    setTimeout("jam()", 1000);
}

function set(e) {
    e = e < 10 ? "0" + e : e;
    return e;
}

$('.select2').select2({
    theme: 'bootstrap4',
})

$('.select2Modal').select2({
    theme: 'bootstrap4',
    dropdownParent: $('#modal')
})

$('.select2Offcanvas').select2({
    theme: 'bootstrap4',
    dropdownParent: $('#ajaxModel')
})

