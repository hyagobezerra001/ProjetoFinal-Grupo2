<?php

namespace Webjump\Configuration\Setup\Functions;

class ArrayShipping
{

    public function arrayData(): array
    {
        $region_id = 485;
        $array_region_ids = [];
        while ($region_id <= 511) {
            $array_region_ids[] = $region_id;
            $region_id++;
        }

        $region_id_us = 1;
        $array_region_ids_us = [];
        while ($region_id_us <= 64) {
            $array_region_ids_us[] = $region_id_us;
            $region_id_us++;
        }

        $arrayData = [
            'website_id' => [2,3],
            'dest_country_id' => ['BR','US'],
            'dest_region_id' => [$array_region_ids,$array_region_ids_us],
            'dest_zip' => ['*'],
            'condition_name' => ['package_value_with_discount'],
            'condition_value' => ['5.0000','10.0000','15.000','25.000'],
            'price' => ['5','10', '15', '20','35', '50','200'],
            'cost' => ['0']
        ];

        return $arrayData;
    }

    public function colunsData(): array
    {
         return array_keys($this->arrayData());
    }

    public function dataBalanceBr():array
    {
        $arrayData = $this->arrayData();
        $datas= [];
        $j = 0;
        $k = 0;
        $h = 0;
        $indexEnd = 0;
        for ($i = 0; $i < 54; $i++) {
            $h = $i;
            if ($i === 27) {
                $j++;
            }
            if ($j === 1) {
                $h = $indexEnd;
                $indexEnd++;
            }
            $cond_value = rand(0, 2);
            $price      = rand(0, 5);
            $datas = [
                $arrayData['website_id'][$j],
                $arrayData['dest_country_id'][$k],
                $arrayData['dest_region_id'][0][$h],
                $arrayData['dest_zip'][$k],
                $arrayData['condition_name'][$k],
                $arrayData['condition_value'][$cond_value],
                $arrayData['price'][$price],
                $arrayData['cost'][$k]
            ];

            $arrayFinal[] = $datas;
        }
        return $arrayFinal;
    }

    public function dataBalanceUs():array
    {
        $arrayData = $this->arrayData();
        $datasUs = [];
        $j = 0;
        $h = 0;

        $indexEnd = 0;
        for ($i = 0; $i < 128; $i++)
        {
            $h = $i;
            if($i === 64){
                $j++;
            }

            //Apos Zerar o dest_region ele reseta pra 0 e começa contar
            if($j === 1){
                $h = $indexEnd;
                $indexEnd++;
            }
            $datasUs = [
                $arrayData['website_id'][$j],
                $arrayData['dest_country_id'][1],
                $arrayData['dest_region_id'][1][$h],
                $arrayData['dest_zip'][0],
                $arrayData['condition_name'][0],
                $arrayData['condition_value'][3],
                $arrayData['price'][6],
                $arrayData['cost'][0]
            ];


            $arrayFinalUs[] = $datasUs;
        }

        return $arrayFinalUs;
    }

    public function arrayConfiguration () : array {
        return [
            ['carriers/tablerate/active', true],
            ['carriers/tablerate/title', 'Frete de Entregas'],
            ['carriers/tablerate/name', 'Metodo de entregas'],
            ['carriers/tablerate/condition_name', 'package_value_with_discount'],
            ['carriers/tablerate/include_virtual_price', true],
            ['carriers/tablerate/handling_type', 'F'],
            ['carriers/tablerate/handling_fee', '5.0'],
            ['carriers/tablerate/specificerrmsg', 'Infelismete método selecionado não esta disponivel'],
            ['carriers/tablerate/sallowspecific', true],
            ['carriers/tablerate/specificcountry', 'BR,US'],
            ['carriers/tablerate/sort_order', 0],
        ];
    }

}
