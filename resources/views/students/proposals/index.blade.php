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
          <h4 class="modal-title">Proposal</h4>
        </div>
        <div class="modal-body">
			<form class="form" action="" method="POST" enctype="multipart/form-data">
				{{ csrf_field() }}
			    <div class="form-group">
			      <textarea class="form-control" cols="15" placeholder="Catatan Untuk Pembimbing" name="note_student"></textarea><br>
			      <input type="file" class="form-control input-sm" id="file" name="file">
			    </div>
			    <input type="submit" class="btn btn-primary btn-sm" value="Upload"/>
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
		        <li><span class="glyphicon glyphicon-home"></span> &nbsp;<a href="{{ url('/home') }}">Dashboard</a></li>
		        <li class="active">Proposal Tugas Akhir</li>
			</ul>

			<div class="panel panel-primary">
				<div class="panel-heading">
					<h2 class="panel-title">Proposal Tugas Akhir</h2>
				</div>
				<div class="panel-body">
					<div class="alert alert-info">
					  <strong>Info!</strong> Upload Proposal anda, dan tunggu review dari dosen bersangkutan. Upload ulang untuk revisi proposal.
					</div>

					<div align="right">
					  <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">Upload Proposal</button>
					  <hr>
					</div>
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
					        <td><span class="label label-danger">Harap Revisi</span></td>
					        @elseif($v->status == 1)
					        <td><span class="label label-success">Telah Di Setujui</span></td>
					        @else
					        <td><span class="label label-warning">Menunggu Review Pembimbing</span></td>
					        @endif
					        <td>{{ $v->created_at }}</td>
					        <td>{{ $v->note_student }}</td>
					        <td>{{ $v->note_dosen }}</td>
					        <td>
					        <button class="btn btn-xs btn-info" onclick="downloadProposal({{ $v->id }})">Download</button>
					        <button class="btn btn-xs btn-danger" onclick="deleteProposal({{ $v->id }})">Delete</button>
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

	var deleteProposal = function(id) {
        $.ajax({
            url: '/student/proposals/'+id,
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

	var downloadProposal = function(id) {
		window.location = "/student/proposals/"+id;
	}

  </script>
@endsection