<?php
class ResimIslem{
    private function dosya_uzantisi($dosya,$uzanti=-1){
        $b = strrpos($dosya,".");
        $b++;
        if($uzanti!=-1)
        {
            $cik = substr($dosya,$b,$uzanti);
        }
        if($uzanti==-1)
        {
            $cik = substr($dosya,$b);
        }
        $cik = strtolower($cik);
        return $cik;
    }

    private function resim_boyutlandir($resim,$max_en=1920,$max_boy=1200,$uzanti){
        // içeriği başlat..
        ob_start();
        // ilk boyutlar..
        $boyut = getimagesize($resim);
        $en    = $boyut[0];
        $boy   = $boyut[1];
        // yeni boyutlar..
        $x_oran = $max_en  / $en;
        $y_oran = $max_boy / $boy;

        // boyutları orantıla..
        if (($en <= $max_en) and ($boy <= $max_boy)){
            $son_en  = $en;
            $son_boy = $boy;
        }
        elseif (($x_oran * $boy) < $max_boy){
            $son_en  = $max_en;
            $son_boy = ceil($x_oran * $boy);
        }
        else{
            $son_en  = ceil($y_oran * $en);
            $son_boy = $max_boy;
        }

        // uzantıya göre yeni resmi yarat..
        switch($uzanti){
            // jpg ve jpeg uzantılar için..
            case 'jpg':
            case 'jpeg':
                // eski ve yeni resimler..
                $eski = imagecreatefromjpeg($resim);
                $yeni = imagecreatetruecolor($son_en,$son_boy);
                // eski resmi yeniden oluştur..
                imagecopyresampled($yeni,$eski,0,0,0,0,$son_en,$son_boy,$en,$boy);
                // yeni resmi bas ve içeriği çek..
                imagejpeg($yeni,null,60);
                break;
            // png uzantılar için..
            case 'png':
                $eski = imagecreatefrompng($resim);
                $yeni = imagecreatetruecolor($son_en,$son_boy);
                imagealphablending($yeni, false);
                imagesavealpha($yeni, true);
                $transparent = imagecolorallocatealpha($yeni, $son_en, $son_boy, $en, $boy);
                imagefilledrectangle($yeni, 0, 0, $son_en, $son_boy, $transparent);
                imagecopyresampled($yeni,$eski,0,0,0,0,$son_en,$son_boy,$en,$boy);
                imagepng($yeni,null,-1);
                break;
            // gif uzantılar için..
            case 'gif':
                $eski = imagecreatefromgif($resim);
                $yeni = imagecreatetruecolor($son_en,$son_boy);
                imagealphablending($yeni, false);
                imagesavealpha($yeni, true);
                $transparent = imagecolorallocatealpha($yeni, $son_en, $son_boy, $en, $boy);
                imagefilledrectangle($yeni, 0, 0, $son_en, $son_boy, $transparent);
                imagecopyresampled($yeni,$eski,0,0,0,0,$son_en,$son_boy,$en,$boy);
                imagegif($yeni,null,-1);
                break;
            default:
                break;
        }

        $icerik = ob_get_contents();
        ob_end_clean();
        imagedestroy($eski);
        imagedestroy($yeni);

        return $icerik;
    }


    private function adlandir($baslik){
        $bul = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '-');
        $yap = array('c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', ' ');
        $perma = strtolower(str_replace($bul, $yap, $baslik));
        $perma = preg_replace("@[^A-Za-z0-9\\-_]@i", ' ', $perma);
        $perma = trim(preg_replace('/\s+/',' ', $perma));
        $perma = str_replace(' ', '-', $perma);
        return $perma;
    }

    public function resim_upload($file_name,$file_tmp,$file_error,$file_path,$name,$en=1920,$boy=1200){
        $uzanti = $this->dosya_uzantisi($file_name);
        $yeni_ad = $this->adlandir($name);

        $yeni = $yeni_ad.'.'.$uzanti;

        $max_en = $en;
        $max_boy = $boy;

        $b_resim = $file_path.'/orijinal-'.$yeni;
        $k_resim = $file_path.'/'.$yeni;

        if($uzanti=='jpg' or $uzanti=='jpeg' or $uzanti=='png' or $uzanti=='gif'){
            ///chmod($file_path."/", 777);
            if($file_error==0){
                move_uploaded_file($file_tmp,$b_resim);

                $icerik = $this->resim_boyutlandir($b_resim,$max_en,$max_boy,$uzanti);

                $dosya  = fopen($k_resim,"w+");

                fwrite($dosya,$icerik);

                fclose($dosya);


                @unlink($b_resim);
                ///chmod($file_path, 755);
                return $yeni;
            }
            elseif($file_error==1) return '<strong>php.ini</strong> de belirtilmiş olan "upload_max_filesize" ayarını aşan boyutta bir dosya gönderilmeye çalışılıyor';
            elseif($file_error==2) return 'Formdaki MAX_FILE_SIZE alanının değerini aşan bir dosya gönderilmeye çalışılıyor.';
            elseif($file_error==3) return 'Dosya gönderimi tam olarak tamamlanamadı.';
            elseif($file_error==4) return 'Dosya yüklenemedi.';
            elseif($file_error==6) return 'Geçici dosya bulunamıyor.';
            elseif($file_error==7) return 'Dosya yazılamıyor.';
        }

        else{
            ///chmod($file_path, 777);
            @unlink($k_resim);
            ///chmod($file_path, 755);
            return false;
        }
    }
}

?>
