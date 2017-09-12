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
				<li class="active">Kelola Peserta Tugas Akhir</li>
			</ul>

			
                <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <b>TIPS:</b> Pada halaman ini, koordinator dapat mendaftarkan mhs untuk mata kuliah Tugas Akhir 1 sekaligus membuatkan user untuk masing-masing mahasiswa<br>(password sama dengan no induk dan dapat diubah oleh masing-masing mahasiswa)
                </div>


			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">Student Management</h2>
				</div>
				<div class="panel-body">
				<div align="right">
					<button id="editBtn" class="btn btn-primary btn-sm" onclick="rikad.showModalNew()">Tambah Baru</button>
					<button id="setBtn" class="btn btn-primary btn-sm" onclick="rikad.setting()">Pengaturan</button>
				</div><br>
					{!! $html->table(['class'=>'table-striped']) !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script src="/js/jquery.dataTables.min.js"></script>
<script src="/js/dataTables.bootstrap.min.js"></script>
<script src="/js/selectize.min.js"></script>

{!! $html->scripts() !!}

  <script>


	function rikad() {


		this.showModalNew = function () {
			$('#myModal').modal();
			var form = '<form method="POST" action="/tu/transcripts/register"> {{ csrf_field() }} ';
			form += '<div class="form-group"> \
				  <label for="comment">Daftarkan siswa (dari excel sheet "Students"):</label> \
				  <textarea class="form-control" rows="5" id="comment" name=data></textarea> \
				</div> ';
				/*'<div class="form-group"> \
				  <label for="comment">Nama Siswa:</label> \
				  <input type=text class="form-control" id="comment" name=nama></input> \
				</div> \
							 <div class="form-group"> \
				  <label for="comment">NIM:</label> \
				  <input type=text class="form-control" rows="5" id="comment" name=nim></input> \
				</div> \
							 <div class="form-group"> \
				  <label for="comment">Dosen Wali:</label> \
				  <input type=text class="form-control" rows="5" id="comment" name=dosenwali></input> \
				</div> \
					*/

			form += '<div align="right"><input class="btn btn-primary btn-sm" type="submit" value="Save"/></div></form>';

			var content = $('#myModal').find('p');
			content[0].innerHTML = form;

			var contentName = $('#myModal').find('span');
			contentName[0].innerHTML = "Upload Daftar Nilai";
		}


		this.showModal = function (id) {
			$('#myModal').modal();
			var form = '<form method="POST" action="/tu/transcripts"> {{ csrf_field() }} ';
			form += '<input type="hidden" value="'+id+'" name="id"> \
			 		<div class="form-group"> \
				  	<label for="comment">Grade (sementara pakai csv):</label> \
				  	<textarea class="form-control" rows="5" id="comment" name=grade></textarea> \
					</div> ';

			form += '<div align="right"><input class="btn btn-primary btn-sm" type="submit" value="Save"/></div></form>';

			var content = $('#myModal').find('p');
			content[0].innerHTML = form;

			var contentName = $('#myModal').find('span');
			contentName[0].innerHTML = "Upload Daftar Nilai ";
		}

		this.setting = function () {
			$('#myModal').modal();
			var form = '<form method="GET" action="/tu/transcripts/create">';
			form += '<div class="form-group"> \
				  	<label for="comment">NIP Kaprodi:</label> \
				  		<input class="form-control" type="text" value="@isset($kaprodi){{$kaprodi->nip}}@endisset" name="nip" placeholder="NIP Kaprodi"> \
					</div> ';
			form += '<div class="form-group"> \
				  	<label for="comment">Nama Kaprodi:</label> \
				  		<input class="form-control" value="@isset($kaprodi){{$kaprodi->name}}@endisset" type="text" name="name" placeholder="Nama Kaprodi"> \
					</div> ';

			form += '<div align="right"><input class="btn btn-primary btn-sm" type="submit" value="Save"/></div></form>';

			var content = $('#myModal').find('p');
			content[0].innerHTML = form;

			var contentName = $('#myModal').find('span');
			contentName[0].innerHTML = "Setting Template Excel";
		}

	}

	var rikad = new rikad();
  </script>

@endsection

@section('css')
<link href="/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="/css/selectize.css" rel="stylesheet">
@endsection
