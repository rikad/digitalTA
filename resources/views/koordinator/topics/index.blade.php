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

			<div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <b>TIPS:</b> Pada halaman ini, koordinator dapat mendaftarkan topik Tugas Akhir
                    (Dosen juga dapat mendaftarkan topik Tugas Akhir lewat akun masing-masing)
                </div>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">Topics Management</h2>
				</div>

				<div class="panel-body">

				<ul class="nav nav-tabs">
				  <li class="active"><a href="#">Daftar Topik</a></li>
				  <li><a href="/koordinator/topics/{{ $last_period }}">Status Topik</a></li>
		          <li><a href="/koordinator/topics/create">Statistik Topik</a></li>
				</ul>

				<br>
				@isset($period)
				<h5>Pilih Periode :</h5>
				  <select onchange="changeStatus(this.value)" class="form-control" id="period_id">
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


				<div align="right"><button id="editBtn" class="btn btn-primary btn-sm" onclick="rikad.add(true)">Add</button></div><br>
					<table id="konten" class="table"></table>
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

  	function genStar(n) {
  		var out = '<p id='+n+'>';
  		for (var i = 0; i < 5; i++) {
  			if (i < n) {
	  			out += '<span class="glyphicon glyphicon-star"></span>';
  			} else {
	  			out += '<span class="glyphicon glyphicon-star-empty"></span>';	
  			}
  		}

  		out += '</div>';

  		return out;
  	}

    $('#konten').DataTable({
      processing: true,
      serverSide: true,
      ajax: '',
      columns: [
      { data: 'name', name: 'users.name', title: 'Dosen', searchable: true },
      { data: 'title', name: 'topics.title', title: 'Judul', searchable: true },
      { data: 'bobot', name: 'topics.bobot', title: 'Bobot', searchable: false, render: function(data) {
      	return genStar(data)
      }},
      { data: 'waktu', name: 'topics.waktu', title: 'Waktu', searchable: false, render: function(data) {
      	return genStar(data)
      }},
      { data: 'dana', name: 'topics.dana', title: 'Dana', searchable: false, render: function(data) {
      	return genStar(data)
      }},
      { data: 'peminat', name: 'peminat', title: 'Peminat', searchable: false },
      { data: 'id', name: 'id', sortable: false, searchable: false,render: function(data) {
		return '<button class="btn btn-primary btn-xs" onclick="rikad.edit(this,'+data+')"><span class="glyphicon glyphicon-pencil"></span></button> <button class="btn btn-danger btn-xs" onclick="rikad.delete('+data+')"> <span class="glyphicon glyphicon-remove"></span></button>';      	
      }},
    ]});


    function changeStatus(id) {
      window.location = "{{ url('koordinator/topics') }}" +'/'+id;
    }
  </script>

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
			bobot: {title:'Bobot',type:'number'},
			waktu: {title:'Waktu',type:'number'},
			dana: {title:'Dana',type:'number'}
		};

		this.removeBtn = function (id) {
			return '<button class="btn btn-danger btn-xs" onclick="rikad.deleteRow(this)"><span class="glyphicon glyphicon-remove"></span></button>';
		}

		this.getDosen = function() {
			var here = this;
	        $.ajax({
	            url: '/koordinator/topics/dosen',
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
				case 'number':
					output = '<input type="number" class="form-control" name="'+ name +'" value="'+ value +'" max="5"/>';
				break;

				default: output = '<input type="text" class="form-control" name="'+ name +'" value="'+ value +'" />';
			}
			return output;
		}

		this.showModal = function (data,id) {
			$('#myModal').modal();
			var form = '<form method="POST" action="/koordinator/topics"> {{ csrf_field() }} ';
			form += '<input type="hidden" value="'+id+'" name="id">';
			form += '<input type="hidden" value="'+document.getElementById('period_id').value+'" name="period_id">';
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
				if (i == 2 || i == 3 || i == 4) {
					data.push(row[i].firstChild.id)
				} else {
					data.push(row[i].innerHTML);
				}
			}

			this.showModal(data,id);
		}

		this.delete = function(id) {
	        $.ajax({
	            url: '/koordinator/topics/'+id,
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
	rikad.getDosen();


    function changeStatus(id) {
      window.location = "{{ url('koordinator/topics') }}" +'?id='+id;
    }

  </script>

@endsection

@section('css')
<link href="/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="/css/selectize.css" rel="stylesheet">
@endsection
