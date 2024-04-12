<?php echo $this->session->flashdata('message');?>

<div class="callout callout-info">
    <h4>Hasil Ujian</h4>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.  nemo 
        iste cum sunt debitis odio beatae placeat nemo..</p>

</div>

<div class="row">
    <!-- <div class="col-sm-3">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Nilai Ujian</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body text-center" id="tampil_jawaban">
              <h1>Test 90</h1>
          </div>
        </div>
    </div> -->

    <div class="col-sm-12">
    <!-- <?=form_open('', array('id'=>'ujian'), array('id'=> $id_tes));?> -->
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"><span class="badge bg-blue">Soal <span id="soalke"></span> </span></h3>

        </div>
        <div class="box-body">
                <div class="row">
                    <div class="col-sm-4">
                        <button type="button" onclick="reload_ajax()" class="btn btn-sm btn-flat bg-purple"><i class="fa fa-refresh"></i> Reload</button>
                    </div>
                </div>
        </div>

        <div class="box-body">
        <div class="table-responsive px-4 pb-3" style="border: 0">
                <table id="ujian" class="w-100 table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Soal</th>
                        <th>Jawaban</th>
						            <th>Status</th>
                        <!-- <th class="text-center">Aksi</th> -->
                    </tr>        
                </thead>
                <tbody>
                  <?php $no=1; foreach ($hasil as $s):?>
                    <tr>
                      <td><?= $no++;?></td>
                      <td><?= $s['soal'];?></td>
                      <td><?= $s['kunci_jawaban']?></td>
                      <!-- <td><?= $s['list_jawaban'];?></td> -->
                    </tr>
                  <?php endforeach;?>
                </tbody>

                </table>
            </div>
        </div>
        
          <div class="box-body">
                <div class="pull-right">
                    <div class="col-sm-4">
                        <button onclick="return simpanlog();" class="selesai submit btn btn-danger">Selesai</button>
                    </div>
                </div>
          </div>

      </div>
    </div>

</div>


<!-- <script type="text/javascript">
    var base_url        = "<?=base_url(); ?>";
    var id_soal          = "<?=$id_soal; ?>";
    var widget          = $(".step");
    var total_widget    = widget.length;
</script> -->

<script src="<?=base_url()?>assets/dist/js/app/ujian/logujian.js"></script>
