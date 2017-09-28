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
          <h4 class="modal-title">Review Proposal</h4>
        </div>
        <div class="modal-body">
			<form class="form" action="/dosen/proposals" method="POST" enctype="multipart/form-data">
				{{ csrf_field() }}

				<div id="proposal_id"></div>
				 <div class="form-group">
				  <label for="status">Review Proposal:</label>
				  <select class="form-control" name="status">
				    <option value="1">Setujui</option>
				    <option value="2">Revisi Kembali</option>
				  </select>
				</div>

				  <label for="note_dosen">Catatan:</label>
			    <div class="form-group">
			      <textarea class="form-control" cols="15" placeholder="Catatan Untuk Pembimbing" name="note_dosen"></textarea><br>
			    </div>
			    <input type="submit" class="btn btn-primary btn-sm" value="Simpan"/>
			</form>
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
				<li><a href="{{ url('/dosen/proposals') }}">Daftar Kelompok</a></li>
				<li class="active">Proposal Tugas Akhir</li>
			</ul>

			<div class="panel panel-primary">
				<div class="panel-heading">
					<h2 class="panel-title">Proposal Tugas Akhir</h2>
				</div>
				<div class="panel-body">
					<div class="alert alert-info">
					  <strong>Info!</strong> Review Proposal : klik setujui jika proposal sudah memenuhi kriteria, atau tolak untuk di revisi kembali oleh mahasiswa.
					</div>

					  <hr>
					@if(count($data) > 0)
					  <table class="table table-striped">
					    <thead>
					      <tr>
					        <th>Status</th>
					        <th>Tanggal</th>
					        <th>Catatan Mahasiswa</th>
					        <th>Catatan Pembimbing</th>
					        <th>Aksi</th>
					      </tr>
					    </thead>
					    <tbody>
					    @foreach($data as $v)
					      <tr>
					      	@if($v->status == 2)
					        <td><span class="label label-danger">Revisi</span></td>
					        @elseif($v->status == 1)
					        <td><span class="label label-success">Di Setujui</span></td>
					        @else
					        <td><span class="label label-warning">Menunggu Review</span></td>
					        @endif
					        <td>{{ $v->created_at }}</td>
					        <td>{{ $v->note_student }}</td>
					        <td>{{ $v->note_dosen }}</td>
					        <td>
					        <button class="btn btn-xs btn-info" onclick="downloadProposal({{ $v->id }})">Download</button>
					      	@if($v->status == 2 || $v->status == 1)
					        <button class="btn btn-xs btn-success" onclick="reviewProposal({{ $v->id }})">Ubah Review</button>
					      	@else
					        <button class="btn btn-xs btn-success" onclick="reviewProposal({{ $v->id }})">Review Proposal</button>
					        @endif
					        </td>
					      </tr>
					    @endforeach

					    </tbody>
					  </table>
					@else
					<h4 align="center">Belum Ada Proposal Yang Di Upload</h4>
					@endif

				</div>
			</div>


		</div>
	</div>
</div>
@endsection

@section('scripts')
  <script>

	var reviewProposal = function(id) {
            $('#myModal').modal();
            $('#proposal_id').html('<input type="hidden" value="'+id+'" name="proposal_id">');
	}

	var downloadProposal = function(id) {
		window.location = "/dosen/proposals/download/"+id;
	}

  </script>
@endsection
