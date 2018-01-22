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
          <h4 class="modal-title">Kelola Peserta Tugas Akhir</h4>
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

  <!--Modal Bulk-->
  <div class="modal fade" id="myModalBulk" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Tambahkan Peserta</h4>
        </div>
        <div class="modal-body">
        	<form method="POST" action="/koordinator/students"> {{ csrf_field() }}
        	Periode<br>

				@isset($period)
				  <select name="period" class="form-control">
				  @foreach($period as $v)
				  	@if(isset($_GET['id']))
				    <option value="{{ $v->id }}" @if($_GET['id'] == $v->id) selected="selected" @endif>{{ $v->year }} Semester {{ $v->semester }}</option>
				    @else
				    <option value="{{ $v->id }}" @if($last_period == $v->id) selected="selected" @endif>{{ $v->year }} Semester {{ $v->semester }}</option>
				    @endif
				  @endforeach
				  </select>

				@endisset

				<hr>

        	List Student (Contoh format dibawah)<br>
        	<textarea rows=10 class="form-control" name=students>
13318001 Student Pertama
13318002 Student Kedua</textarea><br>
        	<div align="right"><input class="btn btn-primary btn-sm" type="submit" value="Save"/></div></form>
          <p></p>
        </div>
      </div>
      
    </div>
  </div>
  <!---->

	<div class="row">

		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="{{ url('/home') }}">Dashboard</a></li>
				<li class="active">Kelola Peserta Tugas Akhir</li>
			</ul>

			
                <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <b>TIPS:</b> Pada halaman ini, koordinator dapat mendaftarkan mhs untuk mata kuliah Tugas Akhir 1 sekaligus membuatkan user untuk masing-masing mahasiswa<br>(password sama dengan no induk dan dapat diubah oleh masing-masing mahasiswa)
                </div>
            

			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">Student Management</h2>
				</div>
				<div class="panel-body">
					<br>

					@isset($period)
					  <select onchange="changeStatus(this.value)" class="form-control">
					  @foreach($period as $v)
					  	@if(isset($_GET['id']))
					    <option value="{{ $v->id }}" @if($_GET['id'] == $v->id) selected="selected" @endif>{{ $v->year }} Semester {{ $v->semester }}</option>
					    @else
					    <option value="{{ $v->id }}" @if($last_period == $v->id) selected="selected" @endif>{{ $v->year }} Semester {{ $v->semester }}</option>
					    @endif
					  @endforeach
					  </select>

					@endisset
					<hr>

				<div align="right">
					<button id="editBtn" class="btn btn-primary btn-sm" 
					data-toggle="modal" data-target="#myModalBulk">Tambahkan Peserta</button>
				</div><br>
					{!! $html->table(['class'=>'table-striped']) !!}
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

{!! $html->scripts() !!}

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
			no_induk: {title:'No. Induk',type:'text'},
			name: {title:'Name',type:'text'},
			email: {title:'Email',type:'email'}
		};

		this.removeBtn = function (id) {
			return '<button class="btn btn-danger btn-xs" onclick="rikad.deleteRow(this)"><span class="glyphicon glyphicon-remove"></span></button>';
		}

		this.getPeriod = function() {
			var here = this;
	        $.ajax({
	            url: '/koordinator/students/period',
	            type: 'GET',
	            dataType: 'json',
	            error: function() {
	                alert('error fetch data, please refresh this page again');
	            },
	            success: function(res) {
	            	alert(res);
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
			var form = '<form method="POST" action="/koordinator/students"> {{ csrf_field() }} ';
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

			form += '<div align="right"><input class="btn btn-primary btn-sm" type="submit" value="Save"/></div></form>';

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
	            url: '/koordinator/students/'+id,
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

	var rikad = new rikad();
	//rikad.getPeriod();

    function changeStatus(id) {
      window.location = "{{ url('koordinator/students') }}" +'?id='+id;
    }

  </script>

@endsection

@section('css')
<link href="/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="/css/selectize.css" rel="stylesheet">
@endsection