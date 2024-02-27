<?php

namespace App\Exports;

use App\Khademyar;
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
            'توضیحات',
            'محل خدمت',
            'شماره نامه',
            'تاریخ نامه',
            'معاونت',
        ];
    }

    public function map($data): array
    {
            return [
                $data->codemelli,
                $data->fname,
                $data->lname,
                $data->tozih,
                $data->moarefi,
                $data->sh_letter,
                $data->date_letter,
                $data->moavenat,
            ];
        
    }

    public function query()
    {
        return Khademyar::with('definations:khademyar_id')->join('definations','khademyars.id','=','definations.khademyar_id')->whereIn('khademyars.codemelli', $this->selected)->where('definations.deleted', 0);
    }
}