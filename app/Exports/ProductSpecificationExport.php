<?php

namespace App\Exports;

use App\Models\Specification;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductSpecificationExport implements FromCollection, WithHeadings
{

    public function collection()
    {

        $data = Specification::select('specifications.id', 'specifications.name as item',  'specifications.price', 'specifications.image', 'specifications.prefix');
        $data= $data->where('specifications.created_by', \Auth::user()->creatorId())->get();
        foreach($data as $k => $item)
        {
           
            $data[$k]["price"]     = \Auth::user()->priceFormat($item->price);
//            $data[$k]["stock_status"]   = ProductService::$stockStatus[$item->stock_status];
             $data[$k]["image"]          = asset(\Storage::url('uploads/pro_image')) . '/' . $item->image;
        }


        return $data;
    }

    public function headings(): array
    {
        return [
            "ID",
            "Name",
            "Price",
            "Prefix",
            "Image",
           
        ];
    }
}
