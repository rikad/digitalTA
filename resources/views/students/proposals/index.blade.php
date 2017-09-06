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
			<form class="form" action="" method="POST">
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
				<li><a href="{{ url('/home') }}">Dashboard</a></li>
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
						{{ json_encode($data) }}

					  <table class="table table-striped">
					    <thead>
					      <tr>
					        <th>Status</th>
					        <th>Tanggal</th>
					        <th>Catatan</th>
					        <th>Aksi</th>
					      </tr>
					    </thead>
					    <tbody>
					      <tr>
					        <td><span class="label label-warning">Harap Revisi</span></td>
					        <td>john@example.com</td>
					        <td>Doe</td>
					        <td>Doe</td>
					      </tr>
					      <tr>
					        <td><span class="label label-success">Telah Di Setujui</span></td>
					        <td>Mary</td>
					        <td>Moe</td>
					        <td>Moe</td>
					      </tr>

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
	            url: '/student/topics/'+id,
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

  </script>
@endsection