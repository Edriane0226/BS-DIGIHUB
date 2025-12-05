<?php
namespace App\Models;


use CodeIgniter\Model;


class CarModel extends Model
{
protected $table = 'car_models';
protected $primaryKey = 'id';
protected $allowedFields = ['brand','model','year_start','year_end'];
}