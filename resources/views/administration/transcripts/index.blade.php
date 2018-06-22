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
				<li class="active">Kelola Transkrip</li>
			</ul>

			
                <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <b>TIPS:</b> Pada halaman ini, koordinator dapat mendaftarkan mhs untuk mata kuliah Tugas Akhir 1 sekaligus membuatkan user untuk masing-masing mahasiswa<br>(password sama dengan no induk dan dapat diubah oleh masing-masing mahasiswa)
                </div>


			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">Kelola Transkrip</h2>
				</div>
				<div class="panel-body">
				<div align="right">
					<a href="{{ route('transcripts.edit',0) }}"><button class="btn btn-primary btn-sm">Tambah Mahasiswa</button></a>
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
				  		<input class="form-control" type="text" value="@if (isset($kaprodi) && count($kaprodi) > 0) {{$kaprodi->nip}}@endisset" name="nip" placeholder="NIP Kaprodi"> \
					</div> ';
			form += '<div class="form-group"> \
				  	<label for="comment">Nama Kaprodi:</label> \
				  		<input class="form-control" value="@if (isset($kaprodi) && count($kaprodi) > 0){{$kaprodi->name}}@endisset" type="text" name="name" placeholder="Nama Kaprodi"> \
					</div> ';

			form += '<div align="right"><input class="btn btn-primary btn-sm" type="submit" value="Save"/></div></form>';

			var content = $('#myModal').find('p');
			content[0].innerHTML = form;

			var contentName = $('#myModal').find('span');
			contentName[0].innerHTML = "Setting Template Excel";
		}

		this.delete = function(id) {
	        $.ajax({
	            url: '/tu/transcripts/'+id,
	            type: 'DELETE',
	            data: { '_token': window.Laravel.csrfToken },
	            dataType: 'json',
	            error: function() {
	            	location.reload();
	            },
	            success: function() {
	            	location.reload(); 
	            }
	    	});
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
