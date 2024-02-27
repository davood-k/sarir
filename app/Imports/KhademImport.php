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
            'moavenat' => trim($row['moavenat']),
            'bkhademyarsr' => trim($row['khademyarsr']),
            'codemsr' => $this->ReplacePersianNumber(trim($row['codemsr'])),
            'namesr' => trim($row['namesr']),
            'familysr' => trim($row['familysr']),
            'dateshsr' => $this->ReplacePersianNumber(trim($row['dateshsr'])),
            'tdatesr' => $this->ReplacePersianNumber(trim($row['tdatesr'])),
            'madraksr' => trim($row['madraksr']),
            'sanvatsr' => $this->ReplacePersianNumber(trim($row['sanvatsr'])),
            'enzebatsr' => $this->ReplacePersianNumber(trim($row['enzebatsr'])),
            'keifisr' => $this->ReplacePersianNumber(trim($row['keifisr'])),
            'tahsilsr' => trim($row['tahsilsr']),
            'isarsr' => $this->ReplacePersianNumber(trim($row['isarsr'])),
            'nokhbehsr' => $this->ReplacePersianNumber(trim($row['nokhbehsr'])),
            'tajmi' => $this->ReplacePersianNumber(trim($row['tajmi'])),
            'mobilesr' => $this->ReplacePersianNumber(trim($row['mobilesr'])),
            'descriptionsr' => $this->ReplacePersianNumber(trim($row['descriptionsr'])),
        ]);
    }

    public function ReplacePersianNumber(string $var)
    {
        if ($var != '' && $var != null) {
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