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
            <li><a href="/koordinator/topics/{{ $period_id }}">Status Topik</a></li>
            <li class="active"><a href="#">Statistik Topik</a></li>
          </ul>
          <br>

          @isset($period)
          <h5>Pilih Periode :</h5>
            <select onchange="changeStatus(this.value)" class="form-control">
            @foreach($period as $v)
              <option value="{{ $v->id }}" @if($period_id == $v->id) selected="selected" @endif>{{ $v->year }} Semester {{ $v->semester }}</option>
            @endforeach
            </select>
          @endisset
          <hr>

        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>Calon Pembombing 1</th>
              <th>Usulan Topik</th>
              <th>Pendaftar</th>
              <th>Di Setujui</th>
              <th>Catatan</th>
            </tr>
          </thead>
          <tbody>
            @foreach($data as $o)
            <tr>
            <td>{{ $o->dosen }}</td>
            <td>{{ $o->totalTopics }}</td>
            <td>{{ $o->totalGroups }}</td>
            <td>{{ $o->totalGroupsAgree }}</td>
            <td>{{ ($o->totalGroups > 4) ? $o->totalGroups-4 . '  Perlu Di Geser' : '' }}</td>
            <tr>
            @endforeach
          </tbody>
        </table>

				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')

  <script>
    function changeStatus(id) {
      window.location = "{{ url('koordinator/topics/create?id=') }}"+id;
    }
  </script>
@endsection
