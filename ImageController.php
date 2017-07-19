<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreImageRequest;
use Intervention\Image\Facades\Image;

class ImageController extends Controller
{
    /**
     * ImageController constructor.
     */
    public function __construct()
    {
        $this->middleware(['isAdmin']);
    }
    /**
     * @param StoreImageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function imageupdate(StoreImageRequest $request)
    {
        $x = $request->get('x');
        $y = $request->get('y');
        $w = $request->get('w');
        $h = $request->get('h');
        $image = substr($request->get('image_update'),1);
        $targ_w =900;
        $targ_h = 900/$request->width*$request->height;
        $jpeg_quality = 100;
        Image::make($image)->resize(900,null, function ($constraint) {$constraint->aspectRatio();})->save($image);
        $img_r = imagecreatefromjpeg($image);
        $dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
        imagecopyresampled($dst_r,$img_r,0,0,$x,$y,$targ_w,$targ_h,$w,$h);
        header('Content-type: image/jpeg');
        imagejpeg($dst_r,$image,$jpeg_quality);
        flash('裁切圖片成功!')->success();
        return back();
    }
}
