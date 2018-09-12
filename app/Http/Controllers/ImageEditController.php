<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageEditController extends Controller
{
    // index
    public function index(Request $request)
    {
        // リクエスト確認
        if(!isset($request->angle)){
            return view('content');
        }

        // 入力値を返すパラメーター
        $param = [
            'angle' => $request->angle,
            'position' => $request->position,
            'bgcolor' => $request->bgcolor,
            'permeability' => $request->permeability,
            'fsize' => $request->fsize,
            'font' => $request->font,
            'fcolor' => $request->fcolor,
            'asked' => $request->asked,
            'name' => $request->name,
            'number' => $request->number,
            'title' => $request->title,
            'graduate' => $request->graduate,
            'namev' => $request->namev,
            'image' => $request->image,
            'message' => $request->message
        ];

        if(!is_uploaded_file($_FILES['image']['tmp_name'])){
            $param += [
                'emess' => '画像を選択してください。',
            ];
            return view('content', $param);
        }

        // ドメイン
        $domain = Config('const.domain');

        // ファイル名を生成
        if(mb_substr($_FILES['image']['name'], -3) == 'jpg' || mb_substr($_FILES['image']['name'], -3) == 'png'){
            $ext = mb_substr($_FILES['image']['name'], -3);
        }else{
            $ext = mb_substr($_FILES['image']['name'], -4);
        }
        $format = '%s_%s.%s';
        $time = time();
        $sha1 = sha1(uniqid(mt_rand(),true));
        $file_name = sprintf($format,$time,$sha1,$ext);

        // アップロードされたファイルを指定のフォルダに移動
        move_uploaded_file($_FILES['image']['tmp_name'], dirname(__FILE__) . '/../../../storage/images/' . $file_name);
        $image_path = $domain . '/mensp/storage/images/' . $file_name;

        // 加工前の画像の情報を取得
        list($w, $h, $type) = getimagesize($image_path);

        // pngとjpgで処理関数を分けて画像をインスタンス化
        $image = null;
        if($ext == 'png'){
            $image = imagecreatefrompng($image_path);
        }elseif($ext == 'jpg'|| $ext == 'jpeg'){
            $image = imagecreatefromjpeg($image_path);
        }

        // 新しい画像のキャンバスを定義
        $new_w  = 1920;
        $new_h = 1080;
        if($request->angle == 1){
            $w_per = $new_w / 2 / $w;
            $new_h = $h * $w_per;
        }else{
            $w_per = $new_w / $w;
            $new_h = $h * $w_per;
        }
        $new_image = imagecreatetruecolor($new_w, $new_h);

        // 入力値から画像の配置場所を指定
        if($request->angle == 1){
            if($request->position == 1){
                $position_x = 0;
                $position_y = 0;
                $e_position_x = $new_w / 2;
                $e_position_y = $new_h;
            }else{
                $position_x = $new_w / 2 + 1;
                $position_y = 0;
                $e_position_x = $new_w / 2;
                $e_position_y = $new_h;
            }
        }else{
            $position_x = 0;
            $position_y = 0;
            $e_position_x = $new_w ;
            $e_position_y = $new_h;
        }

        // 位置を指定して画像をキャンバスに設置
        imagecopyresampled($new_image, $image, $position_x, $position_y, 0, 0, $e_position_x, $e_position_y, $w, $h);

        // 塗りつぶし箇所を指定
        if($request->position == 1){
            $black_x = $new_w / 2 + 1;
            $black_y = 0;
            $e_black_x = $new_w;
            $e_black_y = $new_h;
        }else{
            $black_x = 0;
            $black_y = 0;
            $e_black_x = $new_w / 2;
            $e_black_y = $new_h;
        }

        // 塗りつぶしの色を指定
        $bgcolor_r = hexdec(substr($request->bgcolor, 1, 2));
        $bgcolor_g = hexdec(substr($request->bgcolor, 3, 2));
        $bgcolor_b = hexdec(substr($request->bgcolor, 5, 2));
        $bgcolor = imagecolorallocatealpha($new_image, $bgcolor_r, $bgcolor_g, $bgcolor_b, $request->permeability);

        // 塗りつぶしを実施
        imagefilledrectangle($new_image, $black_x, $black_y, $e_black_x, $e_black_y, $bgcolor);

        // パラメーター
        $c_p = 60;

        $c_s = $request->fsize;
        $c_s_p = $request->fsize / 72 * 96;
        $c_h = $request->fsize / 72 * 96;
        $r_c = ($new_w / 2 - $c_p) / $c_s_p;

        $n_c_s = $request->fsize * 0.8;
        $n_c_s_p = $n_c_s / 72 * 96;
        $n_c_h = $n_c_s / 72 * 96;
        $n_r_c = ($new_w / 2 - $c_p) / $n_c_s_p;

        $t_c_s = $request->fsize / 3;
        $t_c_s_p = $t_c_s / 72 * 96;
        $t_c_h = $t_c_s / 72 * 96;
        $t_r_c = ($new_w / 2 - $c_p) / $t_c_s_p;

        // メッセージを１行ずつ取得
        $mess = $request->message; // テキストエリアの値を取得
        //$mess = trim($mess); // 文頭文末の空白を削除
        $cr = array("\r\n", "\r"); // 改行コード置換用配列を作成しておく
        $mess = str_replace($cr, "\n", $mess); // 改行コードを統一
        $mess_array = explode("\n", $mess); // 改行コードで分割
        $output_array = array();
        // 印字のための文字列を追加
        foreach($mess_array as $m){
            if(mb_strlen($m) > $r_c){
                while(mb_strlen($m) > $r_c){
                    $outstr = mb_substr($m, 0, $r_c);
                    array_push($output_array, $outstr);
                    $m = mb_substr($m, $r_c);
                }
                array_push($output_array, $m);
            }else{
                array_push($output_array, $m);
            }
        }

        // 名前表示１の場合の値のセット
        $nnt_cnt = 0;
        if($request->namev == 1){
            if(isset($request->number) || isset($request->title) || isset($request->graduate) || isset($request->name)){
                array_push($output_array, '　');
            }
            if(isset($request->number) || isset($request->title)){
                if(isset($request->number) && isset($request->title)){
                    $num_title = $request->number . '　' . $request->title;
                    if(mb_strlen($num_title) > $r_c){
                        array_push($output_array, $request->number);
                        $nnt_cnt += 1;
                        array_push($output_array, $request->title);
                        $nnt_cnt += 1;
                    }else{
                        array_push($output_array, $num_title);
                        $nnt_cnt += 1;
                    }
                }else{
                    array_push($output_array, $request->number . $request->title);
                    $nnt_cnt += 1;
                }
            }
            if(isset($request->graduate)){
                array_push($output_array, '童貞卒業：' . $request->graduate);
                $nnt_cnt += 1;
            }
            if(isset($request->name)){
                array_push($output_array, $request->name);
                $nnt_cnt += 1;
            }
        }

        // 文字のy軸の位置調整
        $row_cnt = count($output_array);
        if(isset($request->name) && $request->namev == 2){
            $char_y = ($new_h - ($row_cnt + 1) * $c_h) / 2;
        }else{
            $char_y = ($new_h - ($row_cnt - 1) * $c_h) / 2;
        }
        // 文字のｘ軸の位置調整
        if($request->position == 1){
            $char_x = $new_w / 2 + $c_p / 2;
        }else{
            $char_x = $c_p / 2;
        }

        // 塗りつぶしの色指定
        $fcolor_r = hexdec(substr($request->fcolor, 1, 2));
        $fcolor_g = hexdec(substr($request->fcolor, 3, 2));
        $fcolor_b = hexdec(substr($request->fcolor, 5, 2));
        $fcolor = imagecolorallocate($new_image, $fcolor_r, $fcolor_g, $fcolor_b);

        // フォント指定
        $font =  dirname(__FILE__) . '/../../../resources/fonts/' . Config('const.font' . $request->font);

        // 文字を印字
        $lcnt = 0;
        foreach($output_array as $om){
            $lcnt += 1;
            $plp = 0;
            if($nnt_cnt > 0 && ($row_cnt - $lcnt + 1) <= $nnt_cnt){
                if($request->asked == 2){
                    $plp = ($new_w / 2 - $c_p - mb_strlen($om) * $n_c_s_p) / 2;
                }
                imagettftext($new_image, $n_c_s, 0, $char_x + $plp, $char_y, $fcolor, $font, $om);
                $char_y += $n_c_h;
            }else{
                if($request->asked == 2){
                    $plp = ($new_w / 2 - $c_p - mb_strlen($om) * $c_s_p) / 2;
                }
                imagettftext($new_image, $c_s, 0, $char_x + $plp, $char_y, $fcolor, $font, $om);
                $char_y += $c_h;
            }
        }

        // 名前表示２の場合の値のセット
        if($request->namev == 2){
            // 名前
            if(isset($request->name)){
                $plp = 0;

                if($request->asked == 2){
                    $plp = ($new_w / 2 - $c_p - mb_strlen($request->name) * $n_c_s_p) / 2;
                }
                
                $char_y += $c_h;
                imagettftext($new_image, $n_c_s, 0, $char_x + $plp, $char_y, $fcolor, $font, $request->name);
                $char_y += $n_c_h;
            }
            // 代/役職
            if(isset($request->number) || isset($request->title)){
                $plp = 0;
                if(isset($request->number) && isset($request->title)){
                    $num_title = $request->number . '　' . $request->title;
                }else{
                    $num_title = $request->number . $request->title;
                }

                if($request->asked == 2){
                    $plp = ($new_w / 2 - $c_p - mb_strlen($num_title) * $t_c_s_p) / 2;
                }
                
                $char_y -= $t_c_h;
                imagettftext($new_image, $t_c_s, 0, $char_x + $plp, $char_y, $fcolor, $font, $num_title);
                $char_y += $t_c_h;
            }
            // 卒業
            if(isset($request->graduate)){
                $plp = 0;
                $graduate = '童貞卒業：' . $request->graduate;
                
                if($request->asked == 2){
                    $plp = ($new_w / 2 - $c_p - mb_strlen($graduate) * $t_c_s_p) / 2;
                }

                imagettftext($new_image, $t_c_s, 0, $char_x + $plp, $char_y, $fcolor, $font, $graduate);
            }
        }

        // 画像を保存する
        imagepng($new_image, dirname(__FILE__) . '/../../../storage/new_images/' . $file_name);
        $new_image_path = $domain . '/mensp/storage/new_images/' . $file_name;

        //最後にメモリを開放する
        imagedestroy($new_image);

        // 戻り値
        $param += [
            'imageurl' => $new_image_path
        ];
        return view('content', $param);
    }
}
