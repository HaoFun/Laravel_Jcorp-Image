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
        $x = (int)$request->get('x');
        $y = (int)$request->get('y');
        $w = (int)$request->get('w');
        $h = (int)$request->get('h');
        $image = mb_substr($request->get('image_update'),1);
        $targ_w =Image::make($image)->width();
        Image::make($image)->crop($w,$h,$x,$y)->resize($targ_w,null, function ($constraint)
        {
            $constraint->aspectRatio();
        })->save();
        flash('裁切圖片成功!')->success();
//        return '<script type="text/javascript">history.go(-1);</script>';
        return back();
    }
}
