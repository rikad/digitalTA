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
					  <hr>
					 <div align="right">
					<form class="form-inline" action="/action_page.php">
					    <div class="form-group">
					      <input type="file" class="form-control input-sm" id="file" name="file">
					    </div>
					    <input type="submit" class="btn btn-primary btn-sm" value="Upload"/>
					</form>
					</div>

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

  	function additional() {
		$(document).ready(function () {

			// add selectize to select element
			$('.js-selectize').selectize({
				create:true,
				sortField: 'text'
			});
		});
	}

	function rikad() {

		this.optionData = {};

		this.inputName = {
			dosen1_id: {title:'Dosen',type:'select'},
			title: {title:'Judul',type:'text'},
			bobot: {title:'Bobot',type:'text'},
			waktu: {title:'Waktu',type:'text'},
			dana: {title:'Dana',type:'text'}
		};

		this.removeBtn = function (id) {
			return '<button class="btn btn-danger btn-xs" onclick="rikad.deleteRow(this)"><span class="glyphicon glyphicon-remove"></span></button>';
		}

		this.getDosen = function() {
			var here = this;
	        $.ajax({
	            url: '/student/topics/dosen',
	            type: 'GET',
	            dataType: 'json',
	            error: function() {
	                alert('error fetch data, please refresh this page again');
	            },
	            success: function(res) {
	            	here.optionData = res;
	            }
	    	});
        }

        this.buildOption = function(type,selected) {
        	var data = this.optionData;
        	var input = this.inputName[type];
        	var output = selected == '' ? '<option selected="selected" value="">Select '+input.title+'</option>' : '<option value="">Select '+input.title+'</option>';
        	for(var result in data) {
        		if (selected == data[result]) {
	        		output += '<option value="'+result+'" selected="selected">'+data[result]+'</option>';
        		}
        		else {
	        		output += '<option value="'+result+'">'+data[result]+'</option>';
        		}
        	}

        	return output;
        }

		this.inputConstruct = function (name,type,value) {
			var output;

			switch (type) {
				case 'email': output =   '<input type="email" class="form-control" name="'+ name +'" value="'+ value +'" />';
				break;
				case 'select':
					output = '<select class="js-selectize" name="'+name+'">'+ this.buildOption(name,value) +'</select>';
				break;
				case 'date': 
					output = '<div class="input-group date" id="date"><input type="text" class="form-control" name="'+ name +'" value="'+ value +'" /><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div>';
				break;
				default: output = '<input type="text" class="form-control" name="'+ name +'" value="'+ value +'" />';
			}
			return output;
		}

		this.showModal = function (data,id) {
			$('#myModal').modal();
			var form = '<form method="POST" action="/student/topics"> {{ csrf_field() }} ';
			form += '<input type="hidden" value="'+id+'" name="id">';
			var i=0;
			for(var input in this.inputName) {
				form += this.inputName[input].title + '<br>'
				if (this.inputName[input].type == 'password') {
					form += this.inputConstruct(input,this.inputName[input].type,'');
					form += '<br>';
					i++;
					continue;
				}

				form += data == null ? this.inputConstruct(input,this.inputName[input].type,'') : this.inputConstruct(input,this.inputName[input].type,data[i]);
				form += '<br>';

				i++;
			}

			form += '<div align="right"><input class="btn btn-primary btn-sm" type="submit" value="Save"></div></form>';


			var content = $('#myModal').find('p')
			content[0].innerHTML = form;

			//add additional function of date
			additional();
		}

		this.add = function () {
			this.showModal(null,null);
		}
		this.edit = function(row,id) {
			var row = row.parentNode.parentNode.cells;

			data = [];
			for (var i = 0, lt = row.length; i < lt-1; i++) {
				data.push(row[i].innerHTML);
			}

			this.showModal(data,id);
		}

		this.delete = function(id) {
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

		this.pilihTopik = function(id) {
	        $.ajax({
	            url: '/student/topics/'+id,
	            type: 'PUT',
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
	rikad.getDosen();


	//change progress bar
	var longValue = document.querySelector("#progress");
	var progressBar = document.querySelector("#progress-bar");

	if (longValue != undefined && progressBar != undefined) {
		longValue = longValue.attributes.status.value+'%';
		progressBar.style.width = longValue;
	}
  </script>

@endsection

@section('css')
<link href="/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="/css/selectize.css" rel="stylesheet">
@endsection
