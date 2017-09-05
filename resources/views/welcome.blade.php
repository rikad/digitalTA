@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-info">
          <div class="panel-heading"></div>
          <div class="panel-body" style="text-align: center">
            <img class=".img-responsive" width="50%" height="100%" src="image/itb.jpg"/>
            <hr>
            <a href="/file/PanduanMahasiswaSistemTA-TF.pdf"><button class="btn btn-info btn-info">Panduan Mahasiswa</button></a>
            <a href="/file/PanduanDosenSistemTA-TF.pdf"><button class="btn btn-info btn-info">Panduan Dosen</button></a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
