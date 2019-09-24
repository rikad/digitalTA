<!DOCTYPE html>    
<head>
    <title>Konversi Kurikulum 2019</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            padding: 0px;
            margin: 0px;
            font-family: monospace;
        }

        header
        {

            height: 100vh;
            background-image: url('https://www.itb.ac.id/files/images/1513078597.jpeg');
            display: flex;
            flex-direction: column;
            align-items: center;
            background-size: cover;
            justify-content: center;
            margin-bottom: 30px;
        }
        .main
        {
            padding: 50px;
            background-color: #eee;
        }

        .main p
        {
            padding: 10px;
        }

        #down:hover
        {
            cursor: pointer;
        }

        header p {
            margin : 10px;
        }

        .inputData {
            background: whitesmoke;
            padding: 20px;
            border-radius: 20px;
            opacity: 0.9;
            text-align: center;
        }
        header input {
            width: 80%;
            padding: 12px 20px;
            margin: 8px 0;
            box-sizing: border-box;
        }
        button {
            width: 30%;
            padding: 12px 20px;
            background: blue;
            margin: 8px 0;
            box-sizing: border-box;
            opacity: 1;
            color: white;
            border-radius: 10px;
        }
        table, td, th {  
            border: 1px solid black;
            text-align: left;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin: 10px;
        }

        th, td {
            padding: 8px;
            text-align: center
        }

        .yellow {
            background: #F6E87A;
        }

        .green {
            background: #53AF50;
        }

        .grey {
            background: grey;
        }

    </style>
    <link rel="stylesheet" type="text/css" href="/ekuivalensi/print.min.css">
</head>

<body>
  	<div class="main">
        <button type="button" style="width:80px; padding:10px" onclick="printJS({ printable: 'main', type: 'html', targetStyles: ['*'] })">Print</button>
  	<div id="main">        
        <p style="font-size:1.2em" id='identitas'></p>
            <table>
                    <thead style="font-size: 1.2em">
                        <tr>
                            <th colspan="3">Kurikulum 2013</th>
                            <th colspan="3">Kurikulum 2019</th>
                            <th rowspan="2">Nilai</th>
                            <th rowspan="2">Status</th>
                        </tr>
                        <tr>
                            <th>Kode</th>
                            <th>Mata Kuliah</th>
                            <th>sks</th>
                            <th>Kode</th>
                            <th>Mata Kuliah</th>
                            <th>sks</th>
                        </tr>
                    </thead>
                    <tbody id="konten">
                    </tbody>
            </table>

        </p>
        <p>X &nbsp&nbsp&nbsp&nbsp&nbsp: Lulus di Kurikulum Lama <br>
           XX &nbsp&nbsp&nbsp&nbsp: Lulus dan Ekivalen dengan Kurikulum Baru <br>
           Hijau  &nbsp: Lulus <br>
           Kuning : Belum Lulus
        <p>
	<table style="margin:0;border:0px">
  	    <tr style="border:0px">
<td style="text-align:left;border:0px"><p id='total'></p></td><td style="border:0px">Bandung, ..............<br><br><br><br><br><br><br><span id="ttddosen"></span></td>
<td style="text-align:left;border:0px"><p id='total'></p></td><td style="border:0px">Mahasiswa<br><br><br><br><br><br><span id="ttdname"></span><br><span id="ttdnim"></span></td>
</tr>
	</table>
    </div>
    </div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="/ekuivalensi/print.min.js"></script>
<script type="text/javascript">
    var nim = '';
    var dosen = '';

    var xhttp = new XMLHttpRequest();
    
    function getInfo() {
        nim = '{{ Auth::user()->no_induk }}';
        dosen = '...............';

        genTabel();
    }

    function genTabel() {

        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Typical action to be performed when the document is ready:
                data = JSON.parse(xhttp.responseText);
                genKonten(data);
            }
        };

        xhttp.open("GET", "/ekuivalensi/api.php?nim="+ nim, true);
        xhttp.send();
    }

    function cek(data) {
        if(data == undefined || data == null || data == 'null') {
            return '-';
        }

        return data;
    }

    function genKonten(data) {

        if(data.tpb.length <= 0) {
            alert('Data '+nim+' belum tersedia');
            return false
        }

        var tr = '';
        var wajib = 0;
        var pilihan = 0;
        var tpb = 0;
        var tpbSisa = 0;
        var wajibSisa = 0;
        var data;
        var nama = data.tpb[0].nama;

        document.getElementById('identitas').innerHTML = 'Nim : '+ nim + '<br> Nama : '+ nama + '<br> Dosen Wali : '+dosen;
        document.getElementById('ttdnim').innerHTML = nim; 
        document.getElementById('ttdname').innerHTML = nama;
        document.getElementById('ttddosen').innerHTML = dosen;

        tr += '<tr><th colspan="8" style="text-align:center">Mata Kuliah TPB</th></tr>';

        data.tpb.forEach(x => {

            if(x.status == 'X' && x.nilai <= "D" ) {

                tr += '<tr class="green">';
                tr += '<td>' + cek(x.kode) + '</td>';
                tr += '<td>' + cek(x.matakuliah) + '</td>';
                tr += '<td>' + cek(x.sks) + '</td>';

                tr += '<td></td>';
                tr += '<td></td>';
                tr += '<td></td>';
                tr += '<td>' + cek(x.nilai) + '</td>';
                tr += '<td>' + cek(x.status) + '</td>';

                tpb += parseInt(x.sks);

            } else {
                tr += '<tr class="yellow">';
                tr += '<td>' + cek(x.kode) + '</td>';
                tr += '<td>' + cek(x.matakuliah) + '</td>';
                tr += '<td>' + cek(x.sks) + '</td>';

                tr += '<td>' + cek(x.kode) + '</td>';
                tr += '<td>' + cek(x.matakuliah) + '</td>';
                tr += '<td>' + cek(x.sks) + '</td>';
                tr += '<td>' + cek(x.nilai) + '</td>';
                tr += '<td>' + cek(x.status) + '</td>';

                tpbSisa =  parseInt(x.sks);
            }
            tr += '</tr>'; 
 
        });

        tr += '<tr><th colspan="8" style="text-align:center">Mata Kuliah Wajib</th></tr>';

        data.wajib.forEach(x => {

            if(x.kode_baru == 'KU206x') {
                data.pilihan.forEach(z => {
                    if(cek(z.kode).substr(0,5) == 'KU206') {
                        console.log(z.kode);
                        x.kode = z.kode;
                        x.matakuliah = z.matakuliah;
                        x.sks = z.sks;
                        x.nilai = z.nilai;
                        return true;
                    }
                })
            }

           if(cek(x.kode_baru) != '-' || cek(x.kode) != '-') {

                if(x.nilai < "D") {
                    tr += '<tr class="green">';
                } else if (cek(x.kode) != '-') {
                    tr += '<tr class="yellow">';
                } else {
                    tr += '<tr>';
                }

                tr += '<td>' + cek(x.kode) + '</td>';
                tr += '<td>' + cek(x.matakuliah) + '</td>';
                tr += '<td>' + cek(x.sks) + '</td>';
            
                tr += '<td>' + cek(x.kode_baru) + '</td>';
                tr += '<td>' + cek(x.nama_baru) + '</td>';
                tr += '<td>' + cek(x.sks_baru) + '</td>';
                tr += '<td>' + cek(x.nilai) + '</td>';
                
                if(x.nilai < "D") {
                    if(cek(x.kode_baru) == '-') {
                        tr += '<td>X</td>';
                    } else {
                        tr += '<td>XX</td>';
                    }

                    wajib += parseInt(x.sks);
                } else {
                    tr += '<td>-</td>';
                    wajibSisa += parseInt(x.sks_baru);
                }
            
                tr += '</tr>';
            }

        })

        tr += '<tr><th colspan="8" style="text-align:center">Mata Kuliah Pilihan</th></tr>';

        data.pilihan.forEach(x => {

            if(cek(x.kode_baru) != '-' || cek(x.kode) != '-' && cek(x.kode).substr(0,5) != 'KU206') {

                if(x.nilai < "D") {
                    tr += '<tr class="green">';
                } else {
                    tr += '<tr class="yellow">';
                }

                tr += '<td>' + cek(x.kode) + '</td>';
                tr += '<td>' + cek(x.matakuliah) + '</td>';
                tr += '<td>' + cek(x.sks) + '</td>';

                tr += '<td>' + cek(x.kode_baru) + '</td>';
                tr += '<td>' + cek(x.nama_baru) + '</td>';
                tr += '<td>' + cek(x.sks_baru) + '</td>';
                tr += '<td>' + cek(x.nilai) + '</td>';

                if(x.nilai < "D") {
                    if(cek(x.kode_baru) == '-') {
                        tr += '<td>X</td>';
                    } else {
                        tr += '<td>XX</td>';
                    }
                    pilihan += parseInt(x.sks);
                } else {
                    tr += '<td>-</td>';
                }

                tr += '</tr>';
            }
        })

        var total = tpb+wajib+pilihan;
        var totalSisa = 144 - total;
        totalSisa = totalSisa < 0 ? 0 : totalSisa;

        document.getElementById('total').innerHTML = 
            'TPB (sks MK lulus)   = '+tpb+' sks <br>'+
            'TPB (sks MK belum lulus)   = '+tpbSisa+' sks <br>'+
            'SAR (sks MK Wajib Lulus) = '+wajib+' sks <br>'+
            'SAR (sks MK Wajib Belum Lulus)  = '+wajibSisa+' sks <br>'+
            'SAR (sks MK Pilihan Lulus)    = '+pilihan+' sks <br>'+
            'Total sks lulus TPB dan SAR      = '+total+' sks <br>'+
            'Sisa sks MK Wajib dan Pilihan SAR yang belum lulus    = '+totalSisa+' sks';

        document.getElementById('konten').innerHTML = tr
    }

    document.addEventListener("keyup", function(event) {
        // Number 13 is the "Enter" key on the keyboard
        if (event.keyCode === 13) {
            // Cancel the default action, if needed
            event.preventDefault();
            // Trigger the button element with a click
            document.getElementById("down").click();
        }
    });

    getInfo();

</script>

</html>
