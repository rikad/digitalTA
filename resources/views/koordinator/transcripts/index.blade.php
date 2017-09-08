@extends('layouts.app')
@section('content')
<div class="container">

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Upload Daftar Nilai <span id=nama_siswa>a</span></h4>
        </div>
        <div class="modal-body">
        	<!--<div id="hot">For handsontable</div>-->
          <p></p>
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
        	<select><option>2017 Semester 1</option></select><br><br>
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
<script src="http://eqa2.tf.itb.ac.id/handsontable.full.min.js"></script>

{!! $html->scripts() !!}




<script>
var hotElement = document.querySelector('#hot');
var hotSettings = {
    data: dataObject,
    columns: columns,
    stretchH: 'all',
    colWidths : [50,50,50,50,50,50,50,50],    

    height: 441,
    maxRows: 2200,
    rowHeaders: true,
    colHeaders: true,
    formulas: true,
    comments: true,
    outsideClickDeselects: false,    
    manualColumnResize: true
}
var hot = new Handsontable(hotElement, hotSettings);
/*container1 = document.getElementById('example1');
hot1 = new Handsontable(container1, {
    data: data,
    colWidths: [200, 200, 200, 80],
    colHeaders: ["Title", "Description", "Comments", "Cover"],
    columns: [
      {data: "title", renderer: "html"},
      {data: "description", renderer: "html"},
      {data: "comments", renderer: safeHtmlRenderer},
      {data: "cover", renderer: coverRenderer}
    ]
  });*/
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
			grade: {title:'Daftar Nilai (convert jadi csv untuk sementara)',type:'text'}
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
			var form = '<form method="POST" action="/koordinator/transcripts"> {{ csrf_field() }} ';
			form += '<input type="hidden" value="'+id+'" name="id"> \
			 <div class="form-group"> \
  <label for="comment">Grade (sementara pakai csv):</label> \
  <textarea class="form-control" rows="5" id="comment" name=grade></textarea> \
</div> ';
			var i=0;
			/*for(var input in this.inputName) {
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
			}*/

			form += '<div align="right"><input class="btn btn-primary btn-sm" type="submit" value="Save"/></div></form>';

			var content = $('#myModal').find('p')
			content[0].innerHTML = form;

			var contentName = $('#myModal').find('span')
			contentName[0].innerHTML = data[1]+" ("+data[0]+")";

			//add additional function of date
			additional();
		}

		this.add = function () {
			this.showModal(null,null);
		}
		this.upload = function(row,id) {
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
  </script>

@endsection

@section('css')
<link href="/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="/css/selectize.css" rel="stylesheet">
<link type="text/css" rel="stylesheet" href="https://docs.handsontable.com/0.15.1/bower_components/handsontable/dist/handsontable.full.min.css">
@endsection