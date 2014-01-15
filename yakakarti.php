<?php
include('reader.php');

putenv('GDFONTPATH=' . realpath('.'));
if ( isset( $_POST['sifre']) ) {
	if ( $_POST['sifre'] == '8pupRaga') {
		function uygunIsim($kelime){
			/*$kucuk=array('i','ý','ð','ö','ü','þ','ç');
			$buyuk=array('Ý','I','Ð','Ö','Ü','Þ','Ç');
			$kelime=str_replace($buyuk,$kucuk,$kelime);
			$kelime=ucwords(strtolower($kelime));
			$a = array( 'ý', 'ð', 'ü', 'þ', 'ç', 'ö');
			$b = array( 'i', 'g', 'u', 's', 'c', 'o');
			$kelime=str_replace($a,$b,$kelime);

			return ucwords($kelime);*/
			$trChars = array( 'Ä±','ÄŸ','Ã¶','Ã¼','ÅŸ','Ã§');
			$engChars = array( 'i','g','o','u','s','c');
			$kelime = str_replace($trChars,$engChars,$kelime);
			$trUpperChars = array( 'Ä°','Ã–','Ü','Åž','Ã‡');
			$engUpperChars = array( 'I','O','U','S','C');
			$kelime = str_replace( $trUpperChars,$engUpperChars,$kelime);
			return ucwords( $kelime);
		}
		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('UTF-8');
		$data->read('NameTagList.xls');
		$img = ImageCreateFromJPEG( 'yakakartiA3.jpg');
		$color = imagecolorallocate( $img, 0, 0, 0);
		$font = 50;
		$i = 1;
		for (; $i <= $data->sheets[0]['numRows']; $i++) {
			$text = $data->sheets[0]['cells'][$i][1].' '.$data->sheets[0]['cells'][$i][2];
			//$text = uygunIsim($data->sheets[0]['cells'][$i][1].' '.$data->sheets[0]['cells'][$i][2]);

			$xpos = (imagesx($img) / 3 - $font * strlen( $text)) / 2 + (imagesx($img) / 3) * ( ($i) % 3) + $font * 3;
			if(strlen( $text) > 10)
				$xpos = $xpos + 50;
			$ypos = (imagesy($img) / 7 - $font ) / 2 + (imagesy($img) / 7) * ( (integer) ((($i) % 21) / 3)) + $font + 30;
			//imagestring( $img, $font, $xpos, $ypos, $text, $color);
			imagettftext( $img, $font, 0, $xpos, $ypos, $color, 'verdana', $text);
			header( 'Content-Type: image/jpeg');
			
			header( 'Content-Type: text/HTML; charset=iso-8859-9');
			echo $text.'<br />';
			
			if ( ( $i ) % 21 == 0 && $i != 1) {
				imagejpeg( $img, 'yeni'.($i+1).'.jpg', 100);
				imagedestroy( $img);
				$img = ImageCreateFromJPEG( 'yakakartiA3.jpg');
			}
		}
		
		//if ( ($i) % 21 != 0 ) {
			if ( $img) {
				header('Content-Type: image/jpeg');
				imagejpeg( $img, 'cert'.$i.'.jpg', 100);
				imagedestroy( $img);
			}
		//}
		
		header( 'Content-Type: text/HTML');
		echo 'Yaka kartlari basariyla olusturuldu!';
	}
	else {
		header( 'Location: '.$_SERVER['PHP_SELF']);
	}
}
else {
	echo '<?xml version="1.0"?>';
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/dtd/xhtml1-transitional.dtd">';
	echo '<html xmlns="http://www.w3.org/1999/xhtml">';
	echo '<head>';
	echo '<meta http-equiv="Content-Type" content="text/HTML; charset=utf-8;" />';
	echo '<title>Yaka Kartlarý Bastýrma</title>';
	echo '</head>';
	echo '<body>';
	echo '<form action="'.$_SERVER['PHP_SELF'].'" method="POST">';
	echo '<input type="password" name="sifre" maxlength="20" />';
	echo '</form>';
	echo '</body>';
	echo '</html>';
}
?>