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
				<li class="active">Topics Management</li>
			</ul>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">Topics Management</h2>
				</div>
				<div class="panel-body">
          <ul class="nav nav-tabs">
            <li><a href="/koordinator/topics">Daftar Topik</a></li>
            <li class="active"><a href="#">Status Topik</a></li>
          </ul>
          <br>
          @isset($period)
          <h5>Pilih Periode :</h5>
            <select onchange="changeStatus(this.value)" class="form-control">
            @foreach($period as $v)
              <option value="{{ $v->id }}" @if($id == $v->id) selected="selected" @endif>{{ $v->year }} Semester {{ $v->semester }}</option>
            @endforeach
            </select>
          @endisset
          <hr>

        <table class="table table-striped" id="konten">
          <thead>
            <tr>
            <th>Nama Topik</th>              
            <th>Dosen 1</th>              
            <th>Dosen 2</th>              
            <th>Mahasiswa 1</th>              
            <th>Mahasiswa 2</th>              
            <th>Status</th>              
            </tr>
          </thead>
        </table>

				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('css')
  <link href="/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="/css/dataTables.bootstrap.min.css" rel="stylesheet">
  <link href="/css/selectize.css" rel="stylesheet">
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
      { data: 'title', name: 'title' },
      { data: 'dosen1Name', name: 'dosen1Name' , searchable: true},
      { data: 'dosen2Name', name: 'dosen2Name' , searchable: true},
      { data: 'student1Name', name: 'student1Name' , searchable: true},
      { data: 'student2Name', name: 'student2Name' , searchable: true},
      { data: 'is_taken', name: 'is_taken', sortable: true, searchable: false,render: function(data,type,full) {
        if(data == '1') return '<span class="label label-success">Di Setujui</span>';
        else if(full.student1Name != '' && full.student1Name != null) return '<span class="label label-warning">Belum Di Setujui</span>';
        else return '<span class="label label-danger">Tidak Di Ambil</span>';
      }},
    ]});


    function changeStatus(id) {
      window.location = "{{ url('koordinator/topics') }}" +'/'+id;
    }
  </script>
@endsection
