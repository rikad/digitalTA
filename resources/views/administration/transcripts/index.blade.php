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
          <p><div id="spreadsheet"></div><p class="saveBtn"></p></p>
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
<script src="https://bossanova.uk/jspreadsheet/v3/jexcel.js"></script>
<script src="https://jsuites.net/v3/jsuites.js"></script>

{!! $html->scripts() !!}

  <script>


	function rikad() {


		this.showModal = function (id) {
			$('#myModal').modal();
			// var form = '<form method="POST" action="/tu/transcripts"> {{ csrf_field() }} ';
			// form += `<input type="hidden" value="'+id+'" name="id">
			//  		<div class="form-group">
			// 	  	<label for="comment">Grade (sementara pakai csv):</label>
			// 	  	<textarea class="form-control" rows="5" id="comment" name=grade></textarea> 
			// 		</div> 
			// 		`;

			var form = `<div align="right"><button class="btn btn-primary btn-sm" onclick="saveTranscript(${id})">Save</button></div></form>`;

			var content = $('#myModal').find('.saveBtn');
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

<script>

	function saveTranscript(id) {
		$.ajax({
	            url: '/tu/transcripts',
	            type: 'POST',
	            data: { 'id': id, '_token': window.Laravel.csrfToken, 'data': excel.options.data },
	            dataType: 'json',
	            error: function(e) {
					alert('Error: ', e)
	            	// location.reload();
	            },
	            success: function(r) {

					if(r.status == 'ok') {
						alert('berhasil');
		            	location.reload(); 
					} else {
						alert(r.message)
						console.log(r.message)
					}

	            }
	    	});
	}

	var data = [
		[ '1','2016',	'1','1','2','FI1101','Fisika Dasar IA','4','AB','X','2013' ],
		[]
	];
	
	var excel = jexcel(document.getElementById('spreadsheet'), {
		data:data,
		columns: [
			{
				type: 'numeric',
				title:'No',
				width:80
			},
			{
				type: 'numeric',
				title:'Tahun',
				width:80,
			},
			{
				type: 'numeric',
				title:'Semester',
				width:80,
			},
			{
				type: 'numeric',
				title:'Semkur',
				width:80,
			},
			{
				type: 'numeric',
				title:'No Urut Kur',
				width:80,
			},
			{
				type: 'numeric',
				title:'Kode',
				width:80,
			},
			{
				type: 'text',
				title:'Mata Kuliah',
				width:200
			},
			{
				type: 'numeric',
				title:'SKS',
				width:80,
			},
			{
				type: 'text',
				title:'Nilai',
				width:80
			},
			{
				type: 'text',
				title:'Masuk Transkrip',
				width:80
			},
			{
				type: 'numeric',
				title:'Kurikulum',
				width:80
			}
		 ]
	});
	</script>  

@endsection

@section('css')
<link href="/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="/css/selectize.css" rel="stylesheet">
<link rel="stylesheet" href="https://bossanova.uk/jspreadsheet/v3/jexcel.css" type="text/css" />
<link rel="stylesheet" href="https://jsuites.net/v3/jsuites.css" type="text/css" />
@endsection
