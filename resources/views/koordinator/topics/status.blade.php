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

@isset($data)
  <table class="table table-striped">
    <thead>
      <tr>
        <th>No</th>
        <th>Nama Topik</th>
        <th>Mahasiswa</th>
        <th>Pembimbing</th>
        <th>status</th>
      </tr>
    </thead>
    <tbody>
@foreach ($data as $v)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $v->title }}</td>
        <td>

          @if(isset($v->student1Name) && isset($v->student2Name))
            1. {{ $v->student1Name }}
            <br>
            2. {{ $v->student2Name }}
          @elseif(isset($v->student1Name))
            1. {{ $v->student1Name }}
          @elseif(isset($v->student2Name))
            1. {{ $v->student2Name }}
          @else
           -
          @endif
        </td>
        <td>

          @if(isset($v->dosen1Name) && isset($v->dosen2Name))
            1. {{ $v->dosen1Name }}
            <br>
            2. {{ $v->dosen2Name }}
          @elseif(isset($v->dosen1Name))
            1. {{ $v->dosen1Name }}
          @elseif(isset($v->dosen2Name))
            1. {{ $v->dosen2Name }}
          @else
            -
          @endif
        </td>
        <td>
          @if($v->is_taken == 1)
            <span class="label label-success">Di Setujui</span>
          @elseif(isset($v->student1Name))
            <span class="label label-warning">Belum Di Setujui</span>
          @else
            <span class="label label-danger">Tidak Di Ambil</span>
          @endif

        </td>
      </tr>
@endforeach

    </tbody>
  </table>
@endisset

				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
  <script>
    function changeStatus(id) {
      window.location = "{{ url('koordinator/topics') }}" +'/'+id;
    }
  </script>
@endsection
