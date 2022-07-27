<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected static $product;
    protected static $image;
    protected static $imageName;
    protected static $imageDirectory;
    protected static $imageURL;

    public static function imageUpload($request, $product = null)
    {
        self::$image = $request->file('image');
        if(self::$image)
        {
            if (isset($product))
            {
                if (file_exists($product->image))
                {
                    unlink($product->image);
                }
            }
            self::$imageName = time().rand(10,1000).'.'.self::$image->getClientOriginalExtension();
            self::$imageDirectory = 'assets/img/products/';
            self::$image->move(self::$imageDirectory, self::$imageName);
            self::$imageURL = self::$imageDirectory.self::$imageName;
        }
        else
        {
            self::$imageURL = $product->image;
        }
        return self::$imageURL;
    }


    public static function  newProduct($request)
    {
        self::$imageURL = self::imageUpload($request);

        self::$product = new Product();
        self::$product->name = $request->name;
        self::$product->category_name = $request->category_name;
        self::$product->brand_name = $request->brand_name;
        self::$product->description = $request->description;
        self::$product->image = self::$imageURL;
        self::$product->status = $request->status;
        self::$product->save();
    }

    public static function updateProduct($request, $id)
    {
        self::$product                  = Product::find($id);
        self::$imageURL                 = self::imageUpload($request, self::$product);
        self::$product->name            = $request->name;
        self::$product->category_name   = $request->category_name;
        self::$product->brand_name      = $request->brand_name;
        self::$product->description     = $request->description;
        self::$product->image           = self::$imageURL;
        self::$product->status          = $request->status;
        self::$product->save();
    }
}
