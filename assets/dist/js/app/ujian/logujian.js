var table;


$document.ready(function() {
  ajaxcsrf()

  table = $("#ujian").DataTable({
    initComplete: function () {
      var api = this.api();
      $("#ujian_filter input")
        .off(".DT")
        .on("keyup.DT", function (e) {
          api.search(this.value).draw();
        });
    },
    oLanguage: {
      sProcessing: "loading...",
    },
    processing: true,
    serverSide: true,
    ajax: {
      url: base_url + "ujian/logjawaban",
      type: "POST",
    },
    columns: [
      {
        data: "id_ujian",
        orderable: false,
        searchable: false,
      },
      { data: "mahasiswa_id" },
      { data: "id_soal" },
      { data: "kunci_jawaban" },
      { data: "list_jawaban" },
      { data: "tanggal" },
      {
        searchable: false,
        orderable: false,
      },
    ],
    // columnDefs: [
    //   {
    //     targets: 6,
    //     data: {
    //       id_ujian: "id_ujian",
    //       ada: "ada",
    //     },
    //     render: function (data, type, row, meta) {
    //       var btn;
    //       if (data.ada > 2) {
    //         btn = `
		// 			<a class="btn btn-xs btn-success" href="${base_url}hasilujian/cetak/${data.id_ujian}" target="_blank">
		// 				<i class="fa fa-print"></i> Cetak Hasil
		// 			</a>`;
    //       } else {
    //         btn = `<a class="btn btn-xs btn-primary" href="${base_url}ujian/token/${data.id_ujian}">
		// 				<i class="fa fa-pencil"></i> Ikut Ujian
		// 			</a>`;
    //       }
    //       return `<div class="text-center">
		// 							${btn}
		// 						</div>`;
    //     },
    //   },
    // ],
    order: [[1, "asc"]],
    rowId: function (a) {
      return a;
    },
    rowCallback: function (row, data, iDisplayIndex) {
      var info = this.fnPagingInfo();
      var page = info.iPage;
      var length = info.iLength;
      var index = page * length + (iDisplayIndex + 1);
      $("td:eq(0)", row).html(index);
    },
  });

});

function simpanlog(){
  var id_ujian = $("#id_ujian").val();
  var kunci_jawaban = $("#kunci_jawaban").val();
  var list_jawaban = $("#list_jawaban").val();
  ajaxcsrf();
    $.ajax({
        type: "POST",
        url: base_url + "ujian/simpan_akhir",
        data: { id: id_tes },
        success: function (r) {
            console.log(r);
            if (r.status) {
                // isiKuesioner(id_tes);
                // alert(id_tes);
                // window.location.href = base_url + 'kuesioner/isi';
                window.location.replace(base_url + "kuesioner/isi=?" + id_tes);
                //  type: "POST",
                //     url: base_url + "kuesioner/isi",
                //     data: { id: id_tes },
                // success:function(r) {
                //     alert("success");
                
            }
        }
    });


}

// Menggunakan metode POST untuk update jawaban soal