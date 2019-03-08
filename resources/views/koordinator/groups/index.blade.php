@extends('layouts.app')
@section('content')
<div class="container">

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Topics</h4>
        </div>
        <div class="modal-body">
          <p></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="{{ url('/home') }}">Dashboard</a></li>
				<li class="active">Groups Management</li>
			</ul>

			<div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <b>TIPS:</b> Pada halaman ini, koordinator dapat melihat grup tugas akhir
                </div>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">Topics Management</h2>
				</div>

				<div class="panel-body">

				@isset($period)
				<h5>Pilih Periode :</h5>
				  <select onchange="changeStatus(this.value)" class="form-control">
				  @foreach($period as $v)
				  	@if(isset($_GET['id']))
				    <option value="{{ $v->id }}" @if($_GET['id'] == $v->id) selected="selected" @endif>{{ $v->year }} Semester {{ $v->semester }}</option>
				    @else
				    <option value="{{ $v->id }}" @if($last_period == $v->id) selected="selected" @endif>{{ $v->year }} Semester {{ $v->semester }}</option>
				    @endif
				  @endforeach
				  </select>

				@endisset
				<hr>


				<div align="right"><button id="editBtn" class="btn btn-primary btn-sm" onclick="rikad.add(true)">Add</button></div><br>
					<table id="konten" class="table"></table>
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

  <script>

    $('#konten').DataTable({
      processing: true,
      serverSide: true,
      ajax: '',
      columns: [
	    { data: 's1_name', name: 's1_name', title: 'Student 1', searchable: true , render: function(data,type,full) {
	    	return full.s1_username+' - '+data;
	    }},
	    { data: 's2_name', name: 's2_name', title: 'Student 2', searchable: true , render: function(data,type,full) {
	    	if (data == null) {
	    		return '-------';
	    	}
	    	return full.s2_username+' - '+data;
	    }},
	    { data: 'title', name: 'topics.title', title: 'Topik TA', searchable: true, render: function(data,type,full){
	    	if (data != null) {
	    		if (full.is_taken == 0) {
	    			return data + ' (<span class="label label-warning">Menunggu Persetujuan</span>)'
	    		}
	    		else {
	    			return data;
	    		}
	    	}

	    	return '<span class="label label-danger">Belum Memilih Topik</span>';
	    }},
	    { data: 'dosen1_name', name: 'dosen1.name', title: 'Pembimbing1', searchable: true },
	    { data: 'dosen2_name', name: 'dosen2.name', title: 'Pembimbing2', searchable: true },
	    { data: 'id', name: 'id', title: 'Action',sortable: false, searchable: false,render: function(data) {
			return '<button class="btn btn-danger btn-xs" onclick="deleteGroup('+data+')"> <span class="glyphicon glyphicon-remove"></span> Hapus</button>';
	    }},
    ]});


    function changeStatus(id) {
      window.location = "{{ url('koordinator/groups') }}" +'?id='+id;
    }

	function deleteGroup(id) {
        $.ajax({
            url: '/koordinator/groups/'+id,
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


  </script>

@endsection

@section('css')
<link href="/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="/css/selectize.css" rel="stylesheet">
@endsection
