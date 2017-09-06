<?php

use Illuminate\Database\Seeder;

use App\Role;
use App\User;
use App\Period;
use App\Topic;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		// Membuat role admin
		$adminRole = new Role();
		$adminRole->name = "admin";
		$adminRole->display_name = "Administrator";
		$adminRole->save();

		// Membuat role member
		$profRole = new Role();
		$profRole->name = "koordinator";
		$profRole->display_name = "Koordinator TA";
		$profRole->save();

		// Membuat role lecture
		$lecturerRole = new Role();
		$lecturerRole->name = "dosen";
		$lecturerRole->display_name = "Dosen";
		$lecturerRole->save();

		// Membuat role academic assistant
		$academicAssistantRole = new Role();
		$academicAssistantRole->name = "student";
		$academicAssistantRole->display_name = "Student";
		$academicAssistantRole->save();

		
		// Membuat sample admin
		$admin = new User();
		$admin->name = 'admin';
		$admin->username = 'admin';
		$admin->password = bcrypt('rahasia');
		$admin->save();
		$admin->attachRole($adminRole);

		// Membuat sample member
		$member = new User();
		$member->name = "Eko Mursito Budi";
		$member->username = 'emb';
		$member->password = bcrypt('emb');
		$member->save();
		$member->attachRole($profRole);

		// Membuat sample member
		$member = new User();
		$member->name = "AN";
		$member->username = 'an';
		$member->password = bcrypt('an');
		$member->save();
		$member->attachRole($lecturerRole);

		// Membuat sample member
		$member = new User();
		$member->name = "AW";
		$member->username = 'aw';
		$member->password = bcrypt('aw');
		$member->save();
		$member->attachRole($lecturerRole);

		// Membuat sample member
		$member = new User();
		$member->name = "BY";
		$member->username = 'by';
		$member->password = bcrypt('by');
		$member->save();
		$member->attachRole($lecturerRole);

		// Membuat sample member
		$member = new User();
		$member->name = "DK";
		$member->username = 'dk';
		$member->password = bcrypt('dk');
		$member->save();
		$member->attachRole($lecturerRole);

		// Membuat sample member
		$member = new User();
		$member->name = "DR";
		$member->username = 'dr';
		$member->password = bcrypt('dr');
		$member->save();
		$member->attachRole($lecturerRole);

		// Membuat sample member
		$member = new User();
		$member->name = "EE";
		$member->username = 'ee';
		$member->password = bcrypt('ee');
		$member->save();
		$member->attachRole($lecturerRole);

		// Membuat sample member
		$member = new User();
		$member->name = "EJ";
		$member->username = 'ej';
		$member->password = bcrypt('ej');
		$member->save();
		$member->attachRole($lecturerRole);

		// Membuat sample member
		$member = new User();
		$member->name = "EM";
		$member->username = 'em';
		$member->password = bcrypt('em');
		$member->save();
		$member->attachRole($lecturerRole);

		// Membuat sample member
		$member = new User();
		$member->name = "EY";
		$member->username = 'ey';
		$member->password = bcrypt('ey');
		$member->save();
		$member->attachRole($lecturerRole);

		// Membuat sample member
		$member = new User();
		$member->name = "IM";
		$member->username = 'im';
		$member->password = bcrypt('im');
		$member->save();
		$member->attachRole($lecturerRole);

		// Membuat sample member
		$member = new User();
		$member->name = "IP";
		$member->username = 'ip';
		$member->password = bcrypt('ip');
		$member->save();
		$member->attachRole($lecturerRole);

		// Membuat sample member
		$member = new User();
		$member->name = "JS";
		$member->username = 'js';
		$member->password = bcrypt('js');
		$member->save();
		$member->attachRole($lecturerRole);

		// Membuat sample member
		$member = new User();
		$member->name = "MK";
		$member->username = 'mk';
		$member->password = bcrypt('mk');
		$member->save();
		$member->attachRole($lecturerRole);

		// Membuat sample member
		$member = new User();
		$member->name = "NG";
		$member->username = 'ng';
		$member->password = bcrypt('ng');
		$member->save();
		$member->attachRole($lecturerRole);

		// Membuat sample member
		$member = new User();
		$member->name = "PS";
		$member->username = 'ps';
		$member->password = bcrypt('ps');
		$member->save();
		$member->attachRole($lecturerRole);

		// Membuat sample member
		$member = new User();
		$member->name = "RA";
		$member->username = 'ra';
		$member->password = bcrypt('ra');
		$member->save();
		$member->attachRole($lecturerRole);

		// Membuat sample member
		$member = new User();
		$member->name = "RR";
		$member->username = 'rr';
		$member->password = bcrypt('rr');
		$member->save();
		$member->attachRole($lecturerRole);

		// Membuat sample member
		$member = new User();
		$member->name = "SP";
		$member->username = 'sp';
		$member->password = bcrypt('sp');
		$member->save();
		$member->attachRole($lecturerRole);

		// Membuat sample member
		$member = new User();
		$member->name = "YM";
		$member->username = 'ym';
		$member->password = bcrypt('ym');
		$member->save();
		$member->attachRole($lecturerRole);

		// Membuat sample member
		$member = new User();
		$member->name = "YY";
		$member->username = 'yy';
		$member->password = bcrypt('yy');
		$member->save();
		$member->attachRole($lecturerRole);// Membuat sample member

		$member = new User();
		$member->name = "WH";
		$member->username = 'wh';
		$member->password = bcrypt('wh');
		$member->save();
		$member->attachRole($lecturerRole);


		// Membuat sample member
		$member = new User();
		$member->name = "Student 1";
		$member->username = 'student1';
		$member->password = bcrypt('student1');
		$member->save();
		$member->attachRole($academicAssistantRole);

		// Membuat sample member
		$member = new User();
		$member->name = "Student 2";
		$member->username = 'student2';
		$member->password = bcrypt('student2');
		$member->save();
		$member->attachRole($academicAssistantRole);

		// Membuat sample member
		$member = new User();
		$member->name = "Student 3";
		$member->username = 'student3';
		$member->password = bcrypt('student3');
		$member->save();
		$member->attachRole($academicAssistantRole);
		
		// Membuat sample member
		$member = new User();
		$member->name = "Student 4";
		$member->username = 'student4';
		$member->password = bcrypt('student4');
		$member->save();
		$member->attachRole($academicAssistantRole);


		// Membuat period baru
		$period = new Period();
		$period->year = "2017";
		$period->semester = "1";
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 3;
		$period->title = "Pembuatan lapisan pelindung gelombang elektromagnetic (electromagnetic field) / lapisan anti-radar";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 3;
		$period->title = "Pembuatan lapisan superhidrofobik pada 'kaca' pelindung (visor) pada helm";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 4;
		$period->title = "robot rehabilitasi";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 1;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 4;
		$period->title = "autonomous truck trailer";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 1;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 4;
		$period->title = "control of multiple robots";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 1;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 4;
		$period->title = "optimasi operasi pelabuhan";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 1;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 5;
		$period->title = "Synthesis & fabrikasi material struktur nano MOF sebagai sensor gas";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 3;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 5;
		$period->title = "Fabrikasi material struktur nano sebagai bahan bio sensor";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 3;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 5;
		$period->title = "Pengembangan sistem monitoring kualitas udara berbasis sensor semikonduktor";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 3;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 6;
		$period->title = "Desain sistem uji tak merusak dengan ultrasonik untuk deteksi cacat pada rel kereta";
		$period->bobot = 2;
		$period->waktu = 1;
		$period->dana = 1;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 6;
		$period->title = "Desain sistem uji tak merusak dengan ultrasonik untuk pemeriksaan struktur beton";
		$period->bobot = 2;
		$period->waktu = 1;
		$period->dana = 1;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 7;
		$period->title = "Studi nanopartikel pada filter dan asap rokok menggunakan SEM dan TEM";
		$period->bobot = 3;
		$period->waktu = 1;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 7;
		$period->title = "Pembuatan dan evaluasi performa Phase Change Material berbasis PEG";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 8;
		$period->title = "Operator Training Simulator untuk DCS";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 8;
		$period->title = "Sistem kontrol swarm robot BB8";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 8;
		$period->title = "Sistem kontrol swarm robot quad rotor";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 8;
		$period->title = "Pengendalian sistem penjernih air portabel";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 9;
		$period->title = "Integrated Control for Speed Control of Electrical Vehicle";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 9;
		$period->title = "Swarm Control of unmanned ground vehicle";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 9;
		$period->title = "Railway Signalling and Automation";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 9;
		$period->title = "Obstacle avoidance control of quad rotor";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 10;
		$period->title = "Educational Control System";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 1;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 10;
		$period->title = "Alat pengering kain batik dengan udara kering dingin";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 1;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 10;
		$period->title = "Sistem perekam MIDI gambang carumba";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 10;
		$period->title = "Interactive Multimedia in Public Space";
		$period->bobot = 1;
		$period->waktu = 2;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 11;
		$period->title = "Sistem detektor bayi kuning sederhana";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 11;
		$period->title = "Rel kereta pengukuran permukaan menggunakan laser range finder";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 11;
		$period->title = "Pengukuran pengotor pada minyak kelapa sawit";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 11;
		$period->title = "Pembuatan prototipe perekam tomografi optik";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 12;
		$period->title = "Perancangan Concert Hall -dedicated- musik kolintang berbasis kepada preferensi penonton";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 3;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 12;
		$period->title = "Perancangan/model posisi -set up- ensemble musik gamelan bali di dalam Concert Hall";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 3;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 12;
		$period->title = "Analisa sinyal akustik sebagai sarana komunikasi antar pemusik gamelan bali";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 3;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 12;
		$period->title = "Pengujian psiko-akustik preferensi musik gondang batak";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 3;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 13;
		$period->title = "Pengembangan absorber konstruksi paralel dan seri berbahan dasar felt";
		$period->bobot = 3;
		$period->waktu = 1;
		$period->dana = 1;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 13;
		$period->title = "Pengembangan absorber akustik berbasis sistem resonator co-planar";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 13;
		$period->title = "Pengembangan absorber pita lebar berbasis chirped multi-layer";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 1;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 13;
		$period->title = "Pengembangan sonic crystal noise barrier";
		$period->bobot = 1;
		$period->waktu = 2;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 14;
		$period->title = "Soundscape kereta penumpang";
		$period->bobot = 2;
		$period->waktu = 1;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 14;
		$period->title = "Soundscape of iconic spaces (soundscape untuk ruang ikonik)";
		$period->bobot = 2;
		$period->waktu = 1;
		$period->dana = 1;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 14;
		$period->title = "Soundscape ruang rawat inap rumah sakit";
		$period->bobot = 2;
		$period->waktu = 1;
		$period->dana = 1;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 14;
		$period->title = "Auditory virtual reality environment (open plan offices)";
		$period->bobot = 1;
		$period->waktu = 2;
		$period->dana = 1;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 15;
		$period->title = "perancangan komputasi katalis konversi CO2 menjadi metanol untuk aplikasi baterai";
		$period->bobot = 1;
		$period->waktu = 2;
		$period->dana = 1;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 16;
		$period->title = "Pembuatan thin film menggunakan elektrospray pyrolisis";
		$period->bobot = 2;
		$period->waktu = 1;
		$period->dana = 1;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 16;
		$period->title = "Pembuatan thin film menggunakan metode solvo thermal";
		$period->bobot = 2;
		$period->waktu = 1;
		$period->dana = 1;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 17;
		$period->title = "Penerapan Standar Industri untuk Evaluasi Karakteristik dan Performansi Mini Plant di TF-ITB";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 17;
		$period->title = "Integrasi PLC – HMI – Matlab untuk Simulasi dan Pengontrolan Proses Industri.";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 17;
		$period->title = "Aplikasi Mikrokontroller dan Robotik untuk Pengembangan Mobil Listrik Tanpa Awak ";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 1;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 17;
		$period->title = "Pengembangan Sistem Monitoring Posisi dan Pergerakan Truck Trailer di Lapangan Terbuka ";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 1;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 18;
		$period->title = "Pengembangan Model Indeks Silau untuk Lampu/Luminer Berbasis LED";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 3;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 18;
		$period->title = "Pengembangan Model Pencampuran Warna LED-RGB dan LED-Konversi Fosfor";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 1;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 18;
		$period->title = "Optimisasi Sistem Penyalur Cahaya Alami Tipe Anidolik dalam Ruang Kantor Tapak Terbuka";
		$period->bobot = 2;
		$period->waktu = 1;
		$period->dana = 3;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 18;
		$period->title = "Optimisasi Desain Luminer Berbasis LED dengan Algoritma Genetik";
		$period->bobot = 2;
		$period->waktu = 1;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 18;
		$period->title = "Optimisasi Spektrum Lampu Bantu untuk Praktik Dokter di Rumah Sakit";
		$period->bobot = 2;
		$period->waktu = 1;
		$period->dana = 1;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 19;
		$period->title = "Pengembangan model termoregulasi tubuh pasien operasi";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 3;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 19;
		$period->title = "Pengaruh variasi kecepatan terhadap pola aliran udara di dalam ruang operasi (eksperimental uji asap dan simulasi)";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 19;
		$period->title = "Penggunaan thermal insulation coating untuk pengurangan kehilangan energi kalor pada tungku";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 19;
		$period->title = "Perancangan sistem penyimpanan biogas dalam tabung bertekanan";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 20;
		$period->title = "Bio signal feedback untuk robot rehabilitasi";
		$period->bobot = 2;
		$period->waktu = 1;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 20;
		$period->title = "Needle insertion guidance USD";
		$period->bobot = 1;
		$period->waktu = 1;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 20;
		$period->title = "Skin imaging";
		$period->bobot = 1;
		$period->waktu = 1;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 21;
		$period->title = "Nano Komposit Barium Ferrit untuk sensor medikal";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 21;
		$period->title = "Nano Komposit Sitontium Ferrit untuk sensor medikal";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 22;
		$period->title = "Perancangan sensor fusion untuk intelligent level crossing";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 1;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 22;
		$period->title = "Pengembangan sistem kontrol untuk complex biological network";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 22;
		$period->title = "Perancangan dan verifikasi sistem interlocking pada simulator Kereta Api";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 1;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 22;
		$period->title = "Perancangan sensor fusion berbasis machine learning";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 23;
		$period->title = "Pengembangan paket program aplikasi untuk simulator sistem pencairan gas";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 23;
		$period->title = "Pengembangan paket program aplikasi untuk simulator sistem pembangkit daya siklus binari/siklus Rankine organik ";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 23;
		$period->title = "Pengembangan paket program aplikasi untuk simulator aliran udara di dalam ruangan tertutup ";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 2;
		$period->save();

		// Membuat topik-topik
		$period = new Topic();
		$period->period_id = 1;
		$period->dosen1_id = 23;
		$period->title = "Perancangan sistem penukar kalor untuk brine-panas bertekanan tinggi dengan refrigeran organik";
		$period->bobot = 2;
		$period->waktu = 2;
		$period->dana = 2;
		$period->save();
    }
}
