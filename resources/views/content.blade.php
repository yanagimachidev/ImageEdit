@extends('index')

@section('content')

@isset($imageurl)
    <p class="mx-auto col-sm-12 text-center mb-0 mt-3">↓↓↓↓↓画像↓↓↓↓↓</p>
    <img class="mx-auto col-sm-12 mb-3" src="{{$imageurl}}">
@endisset
<div class="mx-auto col-sm-8 mt-3 mb-3">
    <div class="card">
        <div class="card-header text-center">素材入力</div>
        <div class="card-body ">
            @isset($emess)
                <p class="mx-auto text-center text-danger">{{$emess}}</p>
            @endisset
            <form class="form-inline" id="msform" name="msform" method="POST" action="/mensp/public/" enctype="multipart/form-data">
                @csrf
                <label class="control-label col-sm-2" for="angle" require>画像の向き</label>
                <select class="form-control col-sm-9" id="angle" name="angle">
                    <option value="1"
                        @if(isset($angle))
                            @if($angle == 1)
                                selected
                            @endif
                        @else
                            selected
                        @endif
                     >縦</option>
                    <option value="2"
                        @if(isset($angle))
                            @if($angle == 2)
                                selected
                            @endif
                        @endif
                    >横</option>
                </select>

                <div class="ds-2"></div>

                <label class="control-label col-sm-2" for="position" require>画像の位置</label>
                <select class="form-control col-sm-9" id="position" name="position">
                    <option value="1" 
                        @if(isset($position))
                            @if($position == 1)
                                selected
                            @endif
                        @else
                            selected
                        @endif
                    >左</option>
                    <option value="2"
                        @if(isset($position))
                            @if($position == 2)
                                selected
                            @endif
                        @endif
                    >右</option>
                </select>

                <div class="ds-2"></div>

                <label class="control-label col-sm-2" for="bgcolor" require>背景色</label>
                <select class="form-control col-sm-9" id="bgcolor" name="bgcolor">
                    @for($i=1; $i<=8; $i++)
                        <option value="{{Config::get('const.color_hexdec' . $i)}}" 
                            @if(isset($bgcolor))
                                @if($bgcolor == Config::get('const.color_hexdec' . $i))
                                    selected
                                @endif
                            @else
                                @if($i == 1)
                                    selected
                                @endif
                            @endif
                        >{{Config::get('const.color_label' . $i)}}</option>
                    @endfor
                </select>

                <div class="ds-2"></div>

                <label class="control-label col-sm-2" for="permeability" require>透過度(0~127)</label>
                <input class="form-control col-sm-9"  type="number" name="permeability" min="0" max="127" 
                    @if(isset($permeability))
                        value="{{$permeability}}"
                    @else
                        value="60"
                    @endif
                >

                <div class="ds-2"></div>

                <label class="control-label col-sm-2" for="fsize" require>文字サイズ</label>
                <input class="form-control col-sm-9"  type="number" name="fsize"
                    @if(isset($fsize))
                        value="{{$fsize}}"
                    @else
                        value="50"
                    @endif
                 >

                <div class="ds-2"></div>

                <label class="control-label col-sm-2" for="font" require>文字フォント</label>
                <select class="form-control col-sm-9" id="font" name="font">
                    @for($i=1; $i<=8; $i++)
                        <option value="{{$i}}"
                        @if(isset($font))
                            @if($font == $i)
                                selected
                            @endif
                        @endif
                        >{{Config::get('const.font_label' . $i)}}</option>
                    @endfor
                </select>

                <div class="ds-2"></div>

                <label class="control-label col-sm-2" for="fcolor" require>文字色</label>
                <select class="form-control col-sm-9" id="fcolor" name="fcolor">
                    @for($i=1; $i<=8; $i++)
                        <option value="{{Config::get('const.color_hexdec' . $i)}}" 
                            @if(isset($fcolor))
                                @if($fcolor == Config::get('const.color_hexdec' . $i))
                                    selected
                                @endif
                            @else
                                @if($i == 2)
                                    selected
                                @endif
                            @endif
                        >{{Config::get('const.color_label' . $i)}}</option>
                    @endfor
                </select>

                <div class="ds-2"></div>

                <label class="control-label col-sm-2" for="asked" require>文字寄せ</label>
                <select class="form-control col-sm-9" id="asked" name="asked">
                    <option value="1" 
                        @if(isset($asked))
                            @if($asked == 1)
                                selected
                            @endif
                        @else
                            selected
                        @endif
                    >左寄せ</option>
                    <option value="2"
                        @if(isset($asked))
                            @if($asked == 2)
                                selected
                            @endif
                        @endif
                    >中央寄せ</option>
                </select>

                <div class="ds-2"></div>

                <label class="control-label col-sm-2" for="name">名前</label>
                <input class="form-control col-sm-9"  type="text" name="name"
                    @if(isset($name))
                        value="{{$name}}"
                    @endif
                >

                <div class="ds-2"></div>

                <label class="control-label col-sm-2" for="number">代</label>
                <input class="form-control col-sm-9"  type="text" name="number"
                    @if(isset($number))
                        value="{{$number}}"
                    @endif
                >

                <div class="ds-2"></div>

                <label class="control-label col-sm-2" for="title">役職</label>
                <input class="form-control col-sm-9"  type="text" name="title"
                    @if(isset($title))
                        value="{{$title}}"
                    @endif
                >

                <div class="ds-2"></div>

                <label class="control-label col-sm-2" for="graduate" require>童貞卒業</label>
                <input class="form-control col-sm-9"  type="text" name="graduate"
                    @if(isset($graduate))
                        value="{{$graduate}}"
                    @endif
                >

                <div class="ds-2"></div>

                <label class="control-label col-sm-2" for="namev" require>名前表示</label>
                <select class="form-control col-sm-9" id="namev" name="namev">
                    <option value="1" 
                        @if(isset($namev))
                            @if($namev == 1)
                                selected
                            @endif
                        @else
                            selected
                        @endif
                    >バージョン１</option>
                    <option value="2"
                        @if(isset($namev))
                            @if($namev == 2)
                                selected
                            @endif
                        @endif
                    >バージョン２</option>
                </select>

                <div class="ds-2"></div>

                <label class="control-label col-sm-2" for="image">画像ファイル</label>
                <input class="form-control col-sm-9" type="file" name="image" require
                    @if(isset($image))
                        value="{{$image}}"
                    @endif
                >

                <div class="ds-2"></div>

                <label class="control-label col-sm-2" for="message">メッセージ</label>
                <textarea class="form-control col-sm-9" name="message" rows="4" cols="40">@if(isset($message)){{$message}}@endif</textarea>

                <div class="ds-2"></div>

                <input class="mx-auto btn btn-primary" type="submit" name="send" value="送信">
            </form>
        </div>
    </div>
</div>

@endsection