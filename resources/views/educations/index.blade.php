@extends('layouts.app')
@section('content')
<div class="container">
			<ul class="breadcrumb">
				<li><a href="{{ url('/home') }}">Dashboard</a></li>
				<li>Settings</li>
				<li class="active">Educations</li>
			</ul>
	<div class="row">
		<div class="col-md-2">
			@include('layouts._sidebar')
		</div>
		<div class="col-md-10">

			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">Update Educations Information</h2>
				</div>
				<div class="panel-body">

				<table id="maintable" class="table table-striped">
				    <thead>
				      <tr>
				        <th>#</th>
				        <th>Program</th>
				        <th>Institution</th>
				        <th>Country</th>
				        <th>Start</th>
				        <th>End</th>
				        <th style="display:none">Action</th>
				      </tr>
				    </thead>
				    <tbody>
				      <tr>
				        <td>1</td>
				        <td>Doe</td>
				        <td>john@example.com</td>
				        <td>john@example.com</td>
				        <td>john@example.com</td>
				        <td>john@example.com</td>
				        <td style="vertical-align: middle; display: none"><button class="btn btn-danger btn-xs" onclick="rikad.deleteRow(this)"><span class="glyphicon glyphicon-remove"></span></button></td>
				      </tr>
				    </tbody>
				</table>

				<hr>
				<div id="controlBtn" align="right">
					<button id="addBtn" style="display:none" class="btn btn-primary btn-sm" onclick="rikad.addRow()">Add</button>
					<button id="saveBtn" style="display:none" class="btn btn-primary btn-sm" onclick="addrow('maintable')">Save</button>
					<button id="editBtn" class="btn btn-primary btn-sm" onclick="rikad.editMode(true)">Edit</button>
				</div>

<datalist id="country">
    <option value="Indonesia">
    <option value="America">
    <option value="Malaysia">
    <option value="Singapura">
  </datalist>
  <datalist id="program">
    <option value="Engineering Physics">
    <option value="Teknik Fisika">
    <option value="Teknik Kimia">
    <option value="Teknik Insdustri">
  </datalist>
  <datalist id="institution">
    <option value="ITB">
    <option value="UGM">
    <option value="ITS">
    <option value="UI">
    <option value="UNPAD">
  </datalist>

				<hr>
					<ul class="pager">
					  <li class="previous disabled"><a href="#">&larr; Previous</a></li>
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
				sortField: 'text'
			});
		});
	}

	function rikad(table) {

		this.data = document.getElementById(table);
		this.inputName = {
			program: 'list',
			institution: 'list',
			country: 'list',
			start_date: 'date',
			end_date: 'date'
		};
		this.existsData = this.data.rows.length;

		this.removeBtn = function (id) {
			return '<button class="btn btn-danger btn-xs" onclick="rikad.deleteRow(this)"><span class="glyphicon glyphicon-remove"></span></button>';
		}

		this.inputConstruct = function (name,type) {
			var output;

			switch (type) {
				case 'list': output =   '<input list="'+name+'" class="form-control" name="'+name+'">';
				break;
				case 'select': output = '<select class="js-selectize" name="country_id"><option selected="selected" value="">Select Country</option></select>';
				break;
				case 'date': 
					output = '<div class="input-group date" id="date"><input type="text" class="form-control" name="'+ name +'" /><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div>';
				break;
				default: output = '<input type="text" class="form-control" name="'+ name +'" />';
			}
			return output;
		}

		this.addRow = function () {
			var row = this.data.insertRow(this.data.rows.length);

			row.insertCell().innerHTML = '';
			for ( var item in this.inputName) {
				row.insertCell().innerHTML = this.inputConstruct(item,this.inputName[item]);
			}
			var action = row.insertCell();
			action.innerHTML = this.removeBtn(this.data.rows.length);
			action.style.verticalAlign = 'middle';

			//add additional function of date
			additional();
		}

		this.deleteRow = function (row) {
			index = row.parentNode.parentNode.rowIndex;			
			this.data.deleteRow(index);
		}
		this.deleteAllRows = function () {
			var n = this.data.rows.length;
			if (this.existsData < n) {
				for (var i = n - 1; i >= this.existsData; i--) {
					this.data.deleteRow(i);
				}
			}
		}

		this.editMode = function(state) {
			if(state) {
				document.getElementById("addBtn").style.display = 'inline';
				document.getElementById("saveBtn").style.display = 'inline';
				var editBtn = document.getElementById("editBtn");
				editBtn.innerHTML = 'Cancel';
				editBtn.onclick = function () { rikad.editMode(false) };
				editBtn.classList.add('btn-danger');
				editBtn.classList.remove('btn-primary');

				this.actionMode(true);
			}
			else {
				document.getElementById("addBtn").style.display = 'none';
				document.getElementById("saveBtn").style.display = 'none';
				var editBtn = document.getElementById("editBtn");
				editBtn.innerHTML = 'Edit';
				editBtn.onclick = function () { rikad.editMode(true) };
				editBtn.classList.add('btn-primary');
				editBtn.classList.remove('btn-danger');

				this.actionMode(false);
				this.deleteAllRows();
			}
		}

		this.actionMode = function(state) {
			var rows = this.data.getElementsByTagName('tr'); 
			var mode = state ? 'block' : 'none';

			for (var row=0; row < rows.length; row++) {
				if(row == 0) {
					var cells = rows[row].getElementsByTagName('th');
					cells[6].style.display = mode;
				}
				else {
					var cells = rows[row].getElementsByTagName('td');
					cells[6].style.display = mode;					
				}
			}
		}
		this.changeToInput = function(state) {
			var rows = this.data.getElementsByTagName('tr');

			for (var row=1; row < rows.length; row++) {
				var cells = rows[row].getElementsByTagName('td');
				for ( var i=1; i < cells.length; i++) {
					cells[i].innerHTML = 'haha';
				}
			}
		}

	}

	var rikad = new rikad("maintable");

  </script>
@endsection

