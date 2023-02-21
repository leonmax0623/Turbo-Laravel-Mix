<style>
    * {
        padding: 0;
        margin: 0;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        font-family: "DejaVu Sans", Arial, sans-serif;
        font-size: 12px;
    }

    #main {
        position: relative;
    }

    #wrapper {
        max-width: 1024px;
        -webkit-box-shadow: 0 0 1px 0 black;
        box-shadow: 0 0 1px 0 black;
        background-color: rgba(250, 250, 250, 0.3);
        margin: 1em 2em 2em 2em;
        padding: 0 2em 2em 2em;
    }

    .after-header {
        text-align: center;
    }

    .bold {
        font-weight: bold;
        font-size: 14px;
    }

    .car-options-1 {
    }

    .car-options-2 {
    }

    .body {
        margin-top: 5px;
    }

    .item {
        padding-top: 5px;
    }

    .header-left {
        width: 21%;
        border: 1px solid #000;
        border-left: none;
        padding: 5px 5px 5px 25px;
        float: right;
        font-size: 8px
    }

    .sto-max-w-75 {
        width: 75%;
    }

    .sto-border-b {
        border-bottom: 1px solid black;
    }

    .sto-flex {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
    }
</style>

<div id="main">
    <div id="wrapper" class="bg-gray-50 border rounded border-gray-200">
        <div class="sto-flex" style="margin-bottom: 100px">
            <div class="sto-max-w-75" style="float: left; border: 1px solid #000;">
                <h2 class="sto-border-b" style="padding: 15px 0 15px 130px">ИП Арутюнян Асмик Михайловна</h2>
                <div class="" style="padding: 5px; text-decoration: underline;">
                    350901, Краснодарский край, Краснодар г, 2-я Российская ул, дом № 67, тел.+7 (861) 213-95-59
                </div>
            </div>
            <div class="header-left">
                Настоящий акт составлен в соответствии с
                Правилами предоставления услуг по
                техническому обслуживанию и ремонту
                автотранспортных средств от 11 апреля
                2001 г. № 290
            </div>
        </div>
        {{--        <div class="header">--}}
        {{--            <span class="header-title">--}}
        {{--                ИП Арутюнян Асмик Михайловна--}}
        {{--            </span>--}}
        {{--            <br>--}}
        {{--            <div style="display: flex">--}}
        {{--                <span class="header-body">--}}
        {{--                    350901, Краснодарский край, Краснодар г, 2-я Российская ул, дом № 67, тел.+7 (861) 213-95-59--}}
        {{--                </span>--}}
        {{--                <span class="header-right">--}}
        {{--                    Настоящий акт составлен в соответствии с Правилами предоставления услуг по техническому обслуживанию и--}}
        {{--                    ремонту автотранспортных средств от 11 апреля 2001 г. № 290--}}
        {{--                </span>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        <div class="after-header">
            <span class="bold">
                Акт
            </span>
            <br>
            <span>
                приемки автомобиля для проведения ремонта (обслуживания)
                <br> (по наряд-заказу №{!! data_get($data, 'order_id') !!})
            </span>
        </div>
        <div class="header-date" style="float: right">
            Дата приемки: {!! data_get($data, 'order_created_at') !!}
        </div>
        <br><br>
        <div class="after-header">
            <span class="bold">
               Заказчик
            </span>
        </div>
        <div class="">
            <span>
                Ф.И.О. владельца или его представителя:
            </span>
            <span style="text-decoration: underline">
                {!! data_get($data, 'order_client_surname') !!}
                {!! data_get($data, 'order_client_name') !!}
                {!! data_get($data, 'order_client_middle_name') !!}&nbsp;______________________________________
            </span>
        </div>
        <div class="">
            <span>
                Адрес:&nbsp;&nbsp;&nbsp;___________________________________________________________________________________________________________
            </span>
        </div>
        <div class="">
            <span>
                Телефон:
            </span>
            <span style="text-decoration: underline;">
                {!! data_get($data, 'order_client_phones_count') > 0 ?
                    data_get($data, 'order_client_phones') . ' __________________________________' :
                    '_________________________________________________________________________'
                !!}&nbsp;
            </span>
        </div>
        <div class="">
            <span>
                Доверенность №:
            </span>
            <span>
                ______________________________________ Выдана:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;____________________________________________
            </span>
        </div>
        <div class="">
            <span>
                Нот. контора:
            </span>
            <span>
                __________________________________________ Нотариус:&nbsp;&nbsp;&nbsp;&nbsp;____________________________________________
            </span>
        </div>
        <br><br>
        <div class="after-header">
                <span class="bold">
                   Автомобиль
                </span>
        </div>
        <div class="body">
            <div class="item">
                <span class="car-options-1">
                    Гос. номер: {!! data_get($data, 'order_car_number') !!}
                </span>
                <span class="car-options-2">
                    Шасси №: _______________
                </span>
            </div>
            <div class="item">
                <span class="car-options-1">
                    VIN: {!! data_get($data, 'order_car_vin') !!}
                </span>
                <span class="car-options-2">
                    Кузов №: {!! data_get($data, 'order_car_body') !!}
                </span>
            </div>
            <div class="item">
                <span class="car-options-1">
                    Марка, модель: {!! data_get($data, 'order_car_model_name') !!}
                </span>
                <span class="car-options-2">
                    Цвет: {!! data_get($data, 'order_car_color') !!}
                </span>
            </div>
            <div class="item">
                <span class="car-options-1">
                    Год выпуска: {!! data_get($data, 'order_car_year') !!}
                </span>
                <span class="car-options-2">
                    Тех. паспорт №: _______________
                </span>
            </div>
            <div class="item">
                <span class="car-options-1">
                    Двигатель: {!! data_get($data, 'order_car_fuel_name') !!}
                </span>
                <span class="car-options-2">
                    Пробег: _______________
                </span>
            </div>
        </div>
        <div style="padding: 10px 40px">
            <span class="bold">
               Мастер: _______________________ /
                {!! data_get($data, 'order_user_name') !!}
                {!! data_get($data, 'order_user_surname') !!}
                {!! data_get($data, 'order_user_middle_name') !!}/
            </span>
        </div>
        <div style="padding-left: 12px">
            <span class="bold">
               Диспетчер: _______________________ //
            </span>
        </div>
        <div class="after-header" style="padding-top: 30px">
            <span class="bold">
               Условия приема
            </span>
        </div>
        <div class="">
            <span style="padding-left: 20px">Условия приема:</span>
            <br>
            Конфиденциальность сообщенной информации и сохранность автомобиля фирма гарантирует.
            Присутствие клиента в рабочей зоне не допускается. Через сутки после извещения клиента о готовности его
            автомобиля стоимость стоянки оплачивается в размере 30 руб. за каждые из первых трех суток и 100 руб.
            за каждые последующие сутки. Для принятия решения о дальнейшем ремонте автомобиля клиенту отводится 3 дня.
            При увеличении данного периода автомобиль считается переданным на ответственное хранение Исполнителю с
            удержанием с Заказчика платы за хранение в размере 30 руб. в сутки. При установке запчастей клиента фирма
            на произведенные работы гарантии не дает. При отказе клиента от работ, связанных с безопасностью
            эксплуатации автомобиля, фирма ответственности не несет.
        </div>
        <div class="after-header" style="padding-top: 30px">
            <span class="bold">
                Расписка Заказчика
            </span>
        </div>
        <div class="" style="padding: 30px 0 10px 0">
            Автомобиль не находится в залоге, под арестом не состоит.
            Подлинность всех сообщенных мною данных и всех предъявленных мною документов гарантирую.
        </div>
        <div class="after-header" style="padding: 10px 50px 10px 50px">
            <span class="bold">
                Заказчик: ___________________________________ /{!! data_get($data, 'order_client_surname') !!}
                {!! data_get($data, 'order_client_name') !!}
                {!! data_get($data, 'order_client_middle_name') !!}/
            </span>
        </div>
        <div>
            Адрес: {!! data_get($data, 'order_client_address') !!}, тел. {!! data_get($data, 'order_client_phones') !!}
        </div>
        <div class="" style="padding: 20px 0 0 70px">
            <span class="bold">
                Мастер: _______________________ /{!! data_get($data, 'order_user_name') !!}
                {!! data_get($data, 'order_user_surname') !!}
                {!! data_get($data, 'order_user_middle_name') !!}/
            </span>
        </div>
    </div>
</div>
