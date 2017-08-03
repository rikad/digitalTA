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
          <h4 class="modal-title">Key Publications & Presentations</h4>
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


	<ul class="breadcrumb">
		<li><a href="{{ url('/home') }}">Dashboard</a></li>
		<li>Settings</li>
		<li class="active">My Curriculum Vitae</li>
	</ul>
	<div class="row">
		<div class="col-md-2">
			@include('layouts._sidebar')
		</div>
		<div class="col-md-10">

			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">My Curriculum Vitae</h2>
				</div>
				<div class="panel-body">

				<p align="right">Print</p>

				<h4 align="center">Nama</h4>
				<p align="center">role</p>

				<table>
					
					<tr>
						<td><b>Personal Data</b></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td><b>Place/Date of Birth</b>  </td>
						<td>:</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td><b>Phone</b></td>
						<td>:</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td><b>e-mail</b></td>
						<td>:</td>
						<td></td>
					</tr>

				</table>

				<table>

					<tr>
						<td><b>Education</b></td>
					</tr>
					<tr>
						<td>
							<ul>
								<li>tes</li>
								<li>tes</li>
								<li>tes</li>
							</ul>
						</td>
					</tr>

					<tr>
						<td><b>Academic Experience</b></td>
					</tr>
					<tr>
						<td>tes</td>
					</tr>


					<tr>
						<td><b>Non-Academic Experience</b></td>
					</tr>
					<tr>
						<td>tes</td>
					</tr>

					<tr>
						<td><b>Certifications & Professional Registrations</b></td>
					</tr>
					<tr>
						<td>tes</td>
					</tr>

					<tr>
						<td><b>Membership in Professional Organizations</b></td>
					</tr>
					<tr>
						<td>tes</td>
					</tr>

					<tr>
						<td><b>Honors & Awards</b></td>
					</tr>
					<tr>
						<td>tes</td>
					</tr>

					<tr>
						<td><b>Service Activities</b></td>
					</tr>
					<tr>
						<td>tes</td>
					</tr>

					<tr>
						<td><b>Key Publications & Presentations</b></td>
					</tr>
					<tr>
						<td>tes</td>
					</tr>

					<tr>
						<td><b>Professional Development Activities</b></td>
					</tr>
					<tr>
						<td>tes</td>
					</tr>

				</table>

				</div>
			</div>
		</div>
	</div>
</div>

@endsection
