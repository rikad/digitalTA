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
          <h4 class="modal-title">Activitiess</h4>
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
		<li class="active">Activities</li>
	</ul>
	<div class="row">
		<div class="col-md-2">
			@include('layouts._sidebar')
		</div>
		<div class="col-md-10">

			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">Update Activities Information</h2>
				</div>
				<div class="panel-body">

				<h3>Service Activities</h3>
				<hr>

@if (empty($data[0]))
	<p>No one record found, click add to add record.</p>
				<table id="maintable" style="display: none" class="table table-striped">
@else
				<table id="maintable" class="table table-striped">
@endif
				    <thead>
				      <tr>
				        <th>#</th>
				        <th>Service</th>
				        <th style="display:none">Action</th>
				      </tr>
				    </thead>
				    <tbody>
				    	@php
				    		$i=1;
						@endphp
						@foreach ($data as $value)
				      <tr>
				    	@php
				        echo '<td>'.$i.'</td>';
				    	$i++;
						@endphp
				        <td>{{ $value->title }}</td>
				        <td style="vertical-align: middle; display: none"><button class="btn btn-primary btn-xs" onclick="rikad.edit(this,{{ $value->id }})"><span class="glyphicon glyphicon-pencil"></span></button> <button class="btn btn-danger btn-xs" onclick="rikad.delete(this,{{ $value->id }})"><span class="glyphicon glyphicon-remove"></span></button></td>
				      </tr>
						@endforeach
				    </tbody>
				</table>

				<br>
				<h3>Professional Development Activities</h3>
				<hr>
@if (empty($data2[0]))
	<p>No one record found, click add to add record.</p>
				<table id="maintable2" style="display: none" class="table table-striped">
@else
				<table id="maintable2" class="table table-striped">
@endif
				    <thead>
				      <tr>
				        <th>#</th>
				        <th>Service</th>
				        <th style="display:none">Action</th>
				      </tr>
				    </thead>
				    <tbody>
				    	@php
				    		$i=1;
						@endphp
						@foreach ($data2 as $value)
				      <tr>
				    	@php
				        echo '<td>'.$i.'</td>';
				    	$i++;
						@endphp
				        <td>{{ $value->title }}</td>
				        <td style="vertical-align: middle; display: none"><button class="btn btn-primary btn-xs" onclick="rikad.edit(this,{{ $value->id }})"><span class="glyphicon glyphicon-pencil"></span></button> <button class="btn btn-danger btn-xs" onclick="rikad.delete({{ $value->id }})"><span class="glyphicon glyphicon-remove"></span></button></td>
				      </tr>
						@endforeach
				    </tbody>
				</table>


				<hr>
				<div id="controlBtn" align="right">
					<button id="addBtn" style="display:none" class="btn btn-primary btn-sm" onclick="rikad.addRow()">Add</button>
					<button id="editBtn" class="btn btn-primary btn-sm" onclick="rikad.editMode(true)">Edit</button>
				</div>

				<hr>
					<ul class="pager">
					  <li class="previous"><a href="#">&larr; Previous</a></li>
					  <li class="next"><a href="#">Next &rarr;</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('css')
	<link href="/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
	<link href="/css/selectize.css" rel="stylesheet">
	<style type="text/css">
		td {
			vertical-align: middle;
		}
	</style>
@endsection

@section('scripts')
  <script src="/js/moment.min.js"></script>
  <script src="/js/bootstrap-datetimepicker.min.js"></script>
  <script src="/js/selectize.min.js"></script>

  <script>

  	function additional() {
		$(document).ready(function () {
		    $('.date').datetimepicker({ format: 'Y-M-D' });

			// add selectize to select element
			$('.js-selectize').selectize({
				create:true,
				sortField: 'text'
			});

		});
	}

	function rikad(table) {

		this.data = document.getElementById(table);
		this.optionData = {};
		this.inputName = {
			title: {title:'Service',type:'text'},
			type: {title:'Type',type:'boolean'},
		};
		this.existsData = this.data.rows.length;

		this.removeBtn = function (id) {
			return '<button class="btn btn-danger btn-xs" onclick="rikad.deleteRow(this)"><span class="glyphicon glyphicon-remove"></span></button>';
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
				case 'list': output =   '<input list="'+name+'" class="form-control" name="'+name+'">';
				break;
				case 'boolean':
					output = '<select class="form-control" name="'+name+'"><option value="1" selected="selected">Service Activities</option><option value="0">Professional Development Activities</option></select>';
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
			var form = '<form method="POST" action="/menu/activities"> {{ csrf_field() }} ';
			form += '<input type="hidden" value="'+id+'" name="id">';
			var i=0;
			for(var input in this.inputName) {
				if(this.inputName[input].type == 'boolean') { i++; }

				form += this.inputName[input].title + '<br>'
				form += data == null ? this.inputConstruct(input,this.inputName[input].type,'') : this.inputConstruct(input,this.inputName[input].type,data[i]);
				form += '<br>';

				i++;
			}


			form += '<div align="right"><button class="btn btn-primary btn-sm" onclick="rikad.sendSave(id)">Save</button></div>';

			var content = $('#myModal').find('p')
			content[0].innerHTML = form;


			//add additional function of date
			additional();
		}

		this.addRow = function () {
			this.showModal(null,null);
		}
		this.edit = function(row,id) {
			var row = row.parentNode.parentNode.cells;

			data = [];
			for (var i = 1, lt = row.length; i < lt-1; i++) {
				data.push(row[i].innerHTML);
			}

			this.showModal(data,id);
		}

		this.delete = function(id) {
	        $.ajax({
	            url: '/menu/activities/'+id,
	            type: 'DELETE',
	            data: { '_token': window.Laravel.csrfToken },
	            dataType: 'json',
	            error: function(er) {
	            	alert(er)
	            	location.reload();
	            },
	            success: function() {
	            	location.reload(); 
	            }
	    	});
		}

		this.editMode = function(state) {
			if(state) {
				document.getElementById("addBtn").style.display = 'inline';
				var editBtn = document.getElementById("editBtn");
				editBtn.innerHTML = 'Cancel';
				editBtn.onclick = function () { rikad.editMode(false) };
				editBtn.classList.add('btn-danger');
				editBtn.classList.remove('btn-primary');

				this.actionMode(true);
				nonAcademic.actionMode(true);
			}
			else {
				document.getElementById("addBtn").style.display = 'none';
				var editBtn = document.getElementById("editBtn");
				editBtn.innerHTML = 'Edit';
				editBtn.onclick = function () { rikad.editMode(true) };
				editBtn.classList.add('btn-primary');
				editBtn.classList.remove('btn-danger');

				this.actionMode(false);
				nonAcademic.actionMode(false);
			}
		}

		this.actionMode = function(state) {
			var rows = this.data.getElementsByTagName('tr'); 
			var mode = state ? 'block' : 'none';

			for (var row=0; row < rows.length; row++) {
				if(row == 0) {
					var cells = rows[row].getElementsByTagName('th');
					cells[cells.length -1].style.display = mode;
				}
				else {
					var cells = rows[row].getElementsByTagName('td');
					cells[cells.length -1].style.display = mode;					
				}
			}
		}

	}

	var nonAcademic = new rikad("maintable2");
	var rikad = new rikad("maintable");

	//for pagination
  	var activeSidebar = 6;
  </script>


	@include('layouts._sidebarJS')

@endsection

