@extends('layouts.app')
@section('content')
<div class="container">

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><span></span></h4>
        </div>
        <div class="modal-body">
          <p></p>
        </div>
      </div>
      
    </div>
  </div>


	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
        <li><a href="{{ url('/home') }}">Dashboard</a></li>
				<li><a href="{{ url('/tu/transcripts') }}">Kelola Transkrip</a></li>
				<li class="active">New Import Mahasiswa</li>
			</ul>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">New Import Mahasiswa</h2>
				</div>
				<div class="panel-body">

        <div align="right"><button class="btn btn-primary">Save</button></div>
          <div role="tabpanel" class="tab-pane fade in active" id="barang">  
              <div id="data" style="width: 100%; height: 100%; overflow: hidden;"></div>
          </div>

				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('css')
  <link href="{{ asset('handsontable/handsontable.min.css') }}" rel="stylesheet" />
@endsection

@section('scripts')
  <script src="{{ asset('/handsontable/handsontable.min.js') }}"></script>
  <script>
    var data = [
        ['','','','','','','','','','','','','','','','','','','',''],
        ['','','','','','','','','','','','','','','','','','','',''],
        ['','','','','','','','','','','','','','','','','','','',''],
        ['','','','','','','','','','','','','','','','','','','',''],
        ['','','','','','','','','','','','','','','','','','','',''],
        ['','','','','','','','','','','','','','','','','','','','']
      ];

    var container = document.getElementById('data');

    var hot = new Handsontable(container, {

      data: data,
      rowHeaders: true,
      colHeaders: true,
      // colWidths: [55, 80, 80, 80, 80, 80, 80],
      // manualColumnResize: true,
      // manualRowResize: true,
      minSpareRows: 1,
      stretchH: 'all',
      persistentState: true,
      contextMenu: true,
      colHeaders: [
        'NIM',
        'Name Mahasiswa',
        'Initial',
        'NIP',
        'Name Dosen',
        'OPT',
        'Yudisium Date',
        'Graduation Date',
        'FE 1 Date',
        'FE 1 Pass',
        'FE 2 Date',
        'FE 2 Pass',
        'FE 3 Date',
        'FE 3 Pass',
        'FE 4 Date',
        'FE 4 Pass',
        'FE 5 Date',
        'FE 5 Pass',
        'FE 6 Date',
        'FE 6 Pass'
      ]
    });

  function saveData() {
    $.ajax({
      url: '',
      data: { '_token': '{{ csrf_token() }}','data' : hot.getData()},
      type: 'POST',
      error: function(res) {
        alert('ERROR : ' +res.responseJSON.message);
      },
      success: function(res) {
            swal({
              title: "Berhasil Di Simpan!",
              text: "Data Telah Berhasil Di Simpan",
              type: "success"
            }, function() {
              location.reload();
            });
      }
    }); 
  }
    
  </script>
@endsection
