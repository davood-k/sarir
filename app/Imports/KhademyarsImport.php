<?php

namespace App\Imports;

use App\Khademyar;
use App\Defination;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KhademyarsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $mydata = ['codemelli' => $this->codem(trim($row['codemelli']))];

            if (Khademyar::where('codemelli', $mydata)->exists()) {
                if (Khademyar::where('codemelli', $mydata)->first()->definations()->orderBy('sh_letter', 'desc')->where('deleted' , '0')->value('moarefi') != trim($row['moarefi'])) {
                    $extkhademyar = Khademyar::where('codemelli', $this->codem(trim($row['codemelli'])))->first();
                    Khademyar::where('codemelli', $this->codem(trim($row['codemelli'])))->first()->definations()->create([
                        'user_id' => '4',
                        'khademyar_id ' => $extkhademyar,
                        'sh_letter' => $this->ReplacePersianNumber(trim($row['shletter'])),
                        'date_letter' => $this->ReplacePersianNumber(trim($row['dateletter'])),
                        'moarefi' => trim($row['moarefi']),
                        'moavenat' => $this->replacemoavenat(trim($row['moarefi'])),
                        'tozih' => $row['tozih'],
                    ]);
                }
            } 
            else {
                $Khademyar = Khademyar::create([
                    'codemelli' => $this->codem(trim($row['codemelli'])),
                    'fname' => trim($row['fname']),
                    'lname' => trim($row['lname']),
                    'gender' => $this->jens(trim($row['gender'])),
                ]);

                $Khademyar->definations()->create([
                    'user_id' => auth()->user()->id,
                    'khademyar_id ' => $Khademyar->id,
                    'sh_letter' => $this->ReplacePersianNumber(trim($row['shletter'])),
                    'date_letter' => $this->ReplacePersianNumber(trim($row['dateletter'])),
                    'moarefi' => trim($row['moarefi']),
                    'moavenat' => $this->replacemoavenat(trim($row['moarefi'])),
                    'molahezat' => $row['molahezat'],
                    'tozih' => $row['tozih'],
                ]);
            }
        }
    }
    public $codes = '0';
    public function codem($intcod)
    {
        while (strlen($intcod) < 10)
            $intcod = '0' . $intcod;
        return $intcod;
    }

    public function jens($val)
    {

        if ($val === 'زن' || $val === 'خانم' || $val === 'خانوم') {
            return '2';
        } else {
            return '1';
        }


    }

    public function replacemoavenat($var)
    {
        if (
            $var === 'کتب انوار'
            || $var === 'زلال رضوان'
            || $var === 'انتظامات صحن ها و حریم'
            || $var === 'کفشداری'
            || $var === 'فراشی'
            || $var === 'خدام'
            || $var === 'دربانی'
            || $var === 'گروه ویژه'
            || $var === 'خواهران خدمه'
            || $var === 'انتظامات رواق ها'
            || $var === 'انتظامات رواق ها (خیاطی)'
            || $var === 'انتظامات رواق ها (گروه ویژه)'
            || $var === 'انتظامات حریم'
            || $var === 'انتظامات صحن ها'
            || $var === 'شمیم رضوان'
            || $var === 'تشریفات آئین ها و مناسبت ها'
            || $var === 'تشریفات آیین ها و مناسبت ها'
        ) {
            return ('اماکن');
        } elseif (
            $var === 'روشنایی'
            || $var === 'فنی'
            || $var === 'آرایشگر'
            || $var === 'نظارت بر خدمات نظافت'
            || $var === 'گل آرائی'
            || $var === 'گل آرایی'
            || $var === 'نظارت فرش'
            || $var === 'صندلی چرخدار'
            || $var === 'پیداشدگان'
            || $var === 'مهمانسرای حر'
            || $var === 'مهمانسرای غدیر'
            || $var === 'چایخانه'
        ) {
            return ('خدمات زائرین');
        } elseif (
            $var === 'رواق کودک'
            || $var === 'پایگاه ها'
            || $var === 'پاسخگویی'
            || $var === 'مراسم و آئین ها'
            || $var === 'زائرین غیر ایرانی'
            || $var === 'زائرین غیرایرانی'
            || $var === 'دارالقرآن'
            || $var === 'برنامه ریزی'
            || $var === 'امور عمومی'
        ) {
            return ('تبلیغات');
        } elseif (
            $var === 'نعیم رضوان'
            || $var === 'نسیم رضوان'
            || $var === 'طرح و برنامه'
        ) {
            return ('ستادی');
        } elseif (
            $var === 'امور بانوان'
            || $var === 'مرکز خادمیاری'
        ) {
            return ('بنیاد کرامت');
        } elseif (
            $var === 'کتابخانه'
            || $var === 'مرکز قرآن'
        ) {
            return ('سازمان فرهنگی');
        } elseif ($var === 'دارالشفاء') {
            return ('دارالشفاء');
        } elseif (
            $var === 'جذب و سازماندهی'
            || $var === 'ارزیابی و توانمند سازی'
        ) {
            return ('امور خدام');
        } elseif (
            $var === 'یگان صیانت'
        ) {
            return ('یگان صیانت');
        } elseif (
            $var === 'مشاوره'
        ) {
            return ('مشاوره');
        } elseif (
            $var === 'افکارسنجی'
            || $var === 'پاسخگویی 138'
            || $var === 'رسانه'
            || $var === 'ارتباط با مخاطبین'
        ) {
            return ('مرکز ارتباطات و رسانه');
        } elseif (
            $var === 'زائرسرا'
            || $var === 'زائرشهر'
        ) {
            return ('موسسه موقوفه');
        } elseif ($var === 'بازرسی') {
            return ('بازرسی');
        } elseif ($var === 'حراست') {
            return ('حراست');
        } else {
            return ('نامشخص');
        }
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