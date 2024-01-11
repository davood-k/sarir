<?php

namespace App\Exports;

use App\Khademyar;
use App\Defination;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class KhademyarExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    protected $selected;

    public function __construct($data)
    {
        $this->selected = $data;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return [
            'کدملی',
            'نام',
            'فامیل',
            'شماره نامه',
            'تاریخ نامه',
            'ملاحظات',
            'محل خدمت',
            'معاونت',
        ];
    }

    public function map($data): array
    {
            return [
                $data->codemelli,
                $data->fname,
                $data->lname,
                $data->sh_letter,
                $data->date_letter,
                $data->tozih,
                $data->moarefi,
                $data->moavenat,
                // $data->definations()->pluck('moavenat')->implode(', '),
            ];
        
    }

    public function query()
    {
        return Khademyar::with('definations:khademyar_id')->join('definations','khademyars.id','=','definations.khademyar_id')->whereIn('khademyars.codemelli', $this->selected)->where('definations.deleted', 0);
    }
}