<?php
            $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
            $pdf->SetTitle('Kode Perkiraan');
            $pdf->SetHeaderMargin(30);
            $pdf->SetTopMargin(20);
            $pdf->setFooterMargin(20);
            $pdf->SetAutoPageBreak(true);
            $pdf->SetAuthor('Author');
            $pdf->SetDisplayMode('real', 'default');
            $pdf->AddPage();
            $i=0;
            $html='<h3>Kode Perkiraan</h3>
                    <table cellspacing="1" bgcolor="#666666" cellpadding="2">
                        <tr bgcolor="#ffffff">
                            <th width="5%" align="center">No</th>
                            <th width="35%" align="center">Rekening/th>
                            <th width="45%" align="center">Nama</th>
                            <th width="15%" align="center">Induk</th>
							<th width="5%" align="center">Gol</th>
                            <th width="35%" align="center">Tingkat/th>
                            <th width="45%" align="center">Tipe</th>
                            <th width="15%" align="center">Jenis</th>
							<th width="5%" align="center">Saldo Normal</th>
                            <th width="35%" align="center">Saldo Awal/th>
                            <
                        </tr>';
            foreach ($kode_perkiraan as $kp) 
                {
                    $i++;
                    $html.='<tr bgcolor="#ffffff">
                            <td align="center">'.$i.'</td>
                            <td>'.$kp['rekening'].'</td>
                            <td>'.$kp['nama'].'</td>
							<td>'.$kp['induk'].'</td>
                            <td>'.$kp['gol'].'</td>
							<td>'.$kp['tingkat'].'</td>
                            <td>'.$kp['tipe_nama'].'</td>
							<td>'.$kp['jenis_nama'].'</td>
                            <td>'.$kp['saldo_normal'].'</td>
							<td>'.$kp['saldo_awal'].'</td>
                            
                        </tr>';
                }
            $html.='</table>';
            $pdf->writeHTML($html, true, false, true, false, '');
            $pdf->Output('cetak.pdf', 'I');
?>