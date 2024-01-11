<?php

namespace App\Imports;

use App\Khadem;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KhademImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        
        return new Khadem([
            'namesr'=> trim($row['namesr']),
            'familysr'=> trim($row['familysr']),
            'codemsr'=> $this->ReplacePersianNumber(trim($row['codemsr'])),
            'tdatesr'=> $this->ReplacePersianNumber(trim($row['tdatesr'])),
            'moavenat'=> trim($row['moavenat']),
            'sanvatsr'=> $this->ReplacePersianNumber(trim($row['sanvatsr'])),
            'enzebatsr'=> $this->ReplacePersianNumber(trim($row['enzebatsr'])),
            'keifisr'=> $this->ReplacePersianNumber(trim($row['keifisr'])),
            'isarsr'=> $this->ReplacePersianNumber(trim($row['isarsr'])),
            'tahsilsr'=> trim($row['tahsilsr']),
            'nokhbehsr'=> $this->ReplacePersianNumber(trim($row['nokhbehsr'])),
            'tajmi'=> $this->ReplacePersianNumber(trim($row['tajmi'])),
            'bkhademyarsr'=> trim($row['khademyarsr']),
            'mobilesr'=> $this->ReplacePersianNumber(trim($row['mobilesr'])),
            'dateshsr'=>$this->ReplacePersianNumber(trim($row['dateshsr'])),
            'madraksr'=> trim($row['madraksr']),
            'descriptionsr'=>$this->ReplacePersianNumber(trim($row['descriptionsr'])),
        ]);
    }

    public function ReplacePersianNumber(string $var)
    {
        if($var !='' && $var !=null){
        $var = str_replace('۰', '0', $var);
        $var = str_replace('۱', '1', $var);
        $var = str_replace('۲', '2', $var);
        $var = str_replace('۳', '3', $var);
        $var = str_replace('۴', '4', $var);
        $var = str_replace('۵', '5', $var);
        $var = str_replace('۶', '6', $var);
        $var = str_replace('۷', '7', $var);
        $var = str_replace('۸', '8', $var);
        $var = str_replace('۹', '9', $var);
        return $var;
        }
        return '0';
    }

}
