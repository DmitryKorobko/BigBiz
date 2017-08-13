<?php

use yii\db\Migration;

class m170403_072614_inserting_cities_in_city_table extends Migration
{
    public function safeUp()
    {
        $this->batchInsert('{{%city}}',['name'],
            [
                ['Киев'],
                ['Харьков'],
                ['Одесса'],
                ['Днепр'],
                ['Запорожье'],
                ['Львов'],
                ['Кривой Рог'],
                ['Николаев'],
                ['Мариуполь'],
                ['Винница'],
                ['Херсон'],
                ['Чернигов'],
                ['Полтава'],
                ['Черкассы'],
                ['Хмельницкий'],
                ['Сумы'],
                ['Житомир'],
                ['Черновцы'],
                ['Ровно'],
                ['Каменское'],
                ['Крапивницкий'],
                ['Ивано-Франковс'],
                ['Кременчуг'],
                ['Тернополь'],
                ['Луцк'],
                ['Белая Церковь'],
                ['Краматорск'],
                ['Мелитополь'],
                ['Никополь'],
                ['Ужгород'],
                ['Славянск'],
                ['Бердянск'],
                ['Павлоград'],
                ['Северодонецк'],
                ['Каменец-Подольский'],
                ['Лисичанск'],
                ['Бровары'],
                ['Александрия'],
                ['Константиновка'],
            ]);
    }

    public function safeDown()
    {
        $this->truncateTable('{{%city}}');
    }

}