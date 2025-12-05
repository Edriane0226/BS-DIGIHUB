<?php
namespace App\Controllers;


use App\Controllers\BaseController;
use App\Models\ProductCompatibilityModel;


class Compatibility extends BaseController
{
protected $compat;


public function __construct()
{
$this->compat = new ProductCompatibilityModel();
}


public function remove($id)
{
$this->compat->delete($id);
return redirect()->back()->with('success','Compatibility removed');
}
}