<!DOCTYPE html>
<html>
	<head>
		<style>
			body {
				font-family: Helvetica, sans-serif;
			}
		</style>
	</head>
	<body width="100%">
		<center><h3>KEPUTUSAN DEKAN FAKULTAS INFORMATIKA</h3></center>
		<center><h3>UNIVERSITAS TELKOM</h3></center>
		<center><h4>Nomor : 305/AKD9/IF-DEK/2021</h4></center><BR />
		<center><h3>Tentang</h3></center>
		<center><h3>PENETAPAN JUDUL, PEMBIMBING DAN MASA BERLAKU KARYA AKHIR MAHASISWA</h3></center>
		<center><h3>PROGRAM STUDI S1 INFORMATIKA</h3></center><br />
		<center><h3>DEKAN FAKULTAS INFORMATIKA</h3></center>
		<table cellspacing="1" cellpadding="2" border="0" width="100%" style="line-height:16px;">	
				<tr>
					<td valign="top">Menimbang </td>
					<td valign="top">:</td>
					<td>
						<ol style="list-style-type:lower-latin;margin-top:0px;">
							<li>Bahwa untuk menyelesaikan pendidikan pada Program Sarjana di Fakultas Informatika Universitas Telkom, mahasiswa diwajibkan membuat dan menyelesaikan Karya Akhir</li>
							<li>Sehubungan dengan butir (a) di atas, perlu ditetapkan judul, pembimbing dan masa berlaku Karya Akhir mahasiswa. </li>
						</ul>
					</td>
				</tr>
				<tr>
					<td valign="top">Mengingat </td>
					<td valign="top">:</td>
					<td valign="top">
						<ol style="list-style-type:lower-latin;margin-top:0px;">
							<li>Buku Pedoman Institusi Universitas Telkom.</li>
							<li>Petunjuk Pelaksanaan Karya Akhir di Fakultas Informatika Universitas Telkom tahun 2009.</li>
							<li>Surat Keputusan Dekan Fakultas Informatika nomor ITT-1/AKAD09/FIF.4.0/2013 tentang Perubahan Pola Pelaksanaan Karya Akhir Mahasiswa Program Studi S1 Informatika.</li>
						</ul>
					</td>
				</tr>
				<tr>
					<td colspan="3"><center><h3>MEMUTUSKAN</h3></center></td>
				</tr>
				<tr>
					<td valign="top">Menetapkan </td>
					<td valign="top">:</td>
					<td style="padding-left:20px;">Keputusan Dekan Fakultas Informatika, tentang Penetapan Judul, Pembimbing dan Masa Berlaku Karya Akhir Mahasiswa Program Studi S1 Informatika.
					</td>
				</tr>
				<tr>
					<td valign="top">Pertama </td>
					<td valign="top">:</td>
					<td style="padding-left:20px;">Menetapkan Judul Karya Akhir :  <b>{{ $mahasiswa->judul }}</b><i>( {{ $mahasiswa->judul_inggris }} )</i>
					<br/>untuk mahasiswa :<br />
					<table>
					<tr>
						<td>Nama</td>
						<td>:</td>
						<td>{{ $mahasiswa->mhs_nama }}</td>
					</tr>
					<tr>
						<td>NIM</td>
						<td>:</td>
						<td>{{ $mahasiswa->username }}</td>
					</tr>
					</table>
					</td>
				</tr>
				<tr>
					<td valign="top">Kedua </td>
					<td valign="top">:</td>
					<td valign="top">
						<ol style="list-style-type:lower-latin;margin-top:0px;">
							<li>Mengangkat <b>{{ $pembimbing->nama_pembimbing_1 }}</b> sebagai Pembimbing I untuk judul Karya Akhir dari mahasiswa yang disebutkan pada butir pertama.
							</li>
							<li>Mengangkat <b>{{ $pembimbing->nama_pembimbing_2 }}</b> sebagai Pembimbing II untuk judul Karya Akhir dari mahasiswa yang disebutkan pada butir pertama.</li>
						</ul>
					</td>
				</tr>
				<tr>
					<td valign="top">Ketiga </td>
					<td valign="top">:</td>
					<td style="padding-left:20px;">Keputusan ini berlaku sejak tanggal 
					{{ $persetujuan }} dan berakhir pada tanggal 
					{{ $expired }} 
					dan tidak dapat diperpanjang kecuali memenuhi persyaratan perpanjangan Surat Keputusan.
					
					</td>
				</tr>
				<tr>
					<td colspan="3">
					<br /><br /><br />
					<table border="0" style="margin-left:auto;margin-right:auto;">
					<tr>
						<td style='width:100px'>Ditetapkan di</td><td style='width:5px'> : </td> <td>Bandung</td>
					</tr>
					<tr>
						<td>Pada Tanggal</td><td> : </td> <td> {{ $persetujuan }}</td>
					</tr>
					<tr>
						<td colspan="3"><hr /></td>
					</tr>
					<tr>
						<td colspan="3">Dekan Fakultas Informatika</td>
					</tr>
					<tr>
						<td colspan="3">Universitas Telkom</td>
					</tr>
					<tr>
						<td colspan="3"><br /><br /><br /><br /><u>Dr. Z. K. ABDURAHMAN BAIZAL, S.Si., M.Kom.</u></td>
					</tr>
					</table>
					</td>
				</tr>
				<tr>
					<td valign="top">Tembusan </td>
					<td valign="top">:</td>
					<td style="padding-left:20px;">			
					</td>
				</tr>
				<tr>
					<td colspan="3" valign="top">
					<ol>
						<li>Pembimbing Karya Akhir</li>
						<li>Arsip</li>
					</ol>
					</td>
					
				</tr>	
		</table>

	</body>

</html>