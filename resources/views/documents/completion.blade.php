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
        margin-left: 2em;
        margin-right: 2em;
        padding-left: 2em;
        padding-right: 2em;
    }

    .sto-w-px-120 {
        width: 120px;
    }

    .sto-mb-1 {
        margin-bottom: 0.5em;
    }

    .sto-mb-2 {
        margin-bottom: 1em;
    }

    .sto-mb-3 {
        margin-bottom: 1.5em;
    }

    .sto-mb-4 {
        margin-bottom: 2em;
    }

    .bold {
        font-weight: bold;
    }

    ul {
        list-style: none;
        padding-top: 2em;

    }

    .sto-flex {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex
    }

    .sto-items-center {
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
    }

    table {
        width: 100%;
        font-size: small;
        border-spacing: 0;
        border: 0.3px solid #000;
        border-collapse: collapse;
    }

    .sto-table {
        width: 100%;
        font-size: small;
    }

    .sto-table {
        text-align: right
    }

    .sto-table td,
    .sto-table th {
        text-align: center;
        padding: 0.3em;
    }

    .sto-table thead td,
    .sto-table thead th,
    .sto-table tbody td,
    .sto-table tbody th {
        -webkit-box-shadow: 0 0 0 0.5px black;
        box-shadow: 0 0 0 0.5px black;
    }

    .sto-table thead tr,
    .sto-table tbody tr {
        -webkit-box-shadow: 0 0 0 0.5px black;
        box-shadow: 0 0 0 0.5px black;
    }

    .sto-table tfoot td.surround {
        -webkit-box-shadow: 0 0 0 0.5px black;
        box-shadow: 0 0 0 0.5px black;
    }

    .border-right-black-1 {
        border-right: 1px solid #000;
    }

    .border-bottom-black-1 {
        border-bottom: 1px solid #000;
    }
    .border-left-black-1 {
        border-left: 1px solid #000;
    }
    .border-black-1 {
        border: 1px solid #000
    }
    .sto-mt-1 {
        margin-top: 1em;
    }
    .w-100 {
        width: 100%;
    }
    .appeal-reason-footer {
        margin-top: -20px;
    }
</style>

<div id="main">
    <div id="wrapper" class="bg-gray-50 border rounded border-gray-200">
        <div class="sto-flex w-100" style="padding-top: 50px">
            <span>Поставщик:</span>
            <span class="bold" style="width: 100px">
                    ИП Арутюнян Асмик Михайловна, ИНН 231139389283, 350901, Краснодарский край, Краснодар г, 2-я Российская
                    ул, дом № 67, тел.: +7 (861) 213-95-59
            </span>
        </div>

        <h2 class="sto-mt-1 " style="font-size: 16px;">
            Акт об оказании услуг 00000002529 от 14.07.2022
        </h2>

        <ul class="sto-mb-2">
            <li class="sto-flex sto-mb-1">
                <span class="">Покупатель:</span>
                <span class="bold">
                    {!! data_get($data, 'order_client_surname') !!}
                    {!! data_get($data, 'order_client_name') !!}
                    {!! data_get($data, 'order_client_middle_name') !!},
                    тел.: {!! data_get($data, 'order_client_phones') !!}
                </span>
            </li>
            <li class="sto-flex sto-mb-1 sto-items-center">
                <span class="sto-w-px-120">Автомобиль:</span>
                <span class="bold">
                    {!! data_get($data, 'order_car_model_name') !!}
                    {!! data_get($data, 'order_car_color') !!}
                    № {!! data_get($data, 'order_car_number') !!}
                    VIN {!! data_get($data, 'order_car_vin') !!}
                </span>
            </li>
            <li class="sto-flex sto-mb-1 sto-items-center">
                <span class="sto-w-px-120">Док. основание:</span>
                <span class="bold">
                    Заказ-наряд №
                    {!! data_get($data, 'order_id') !!} от
                    {!! data_get($data, 'order_created_at') !!}
                </span>
            </li>
        </ul>

        <div class=" sto-mb-2">
            <h5>Причина обращения: {!! data_get($data, 'order_appeal_reason_name') !!}</h5>
            <h5>Комментарий: {!! data_get($data, 'order_comments_description') !!}</h5>
        </div>
        <!-- Table -->

        <table class="sto-table" style="border-bottom: none; border-left: none">
            <thead>
            <tr>
                <th class="border-right-black-1 border-bottom-black-1 border-left-black-1 w-100">№</th>
                <th class="border-right-black-1 border-bottom-black-1 w-100">Услуга</th>
                <th class="border-right-black-1 border-bottom-black-1 w-100">Цена</th>
                <th class="border-right-black-1 border-bottom-black-1 w-100">в т.ч. НДС</th>
            </tr>
            </thead>

            <tbody>
            @foreach(data_get($data, 'order_works') as $work)
                <tr>
                    <td class="border-right-black-1 border-bottom-black-1 border-left-black-1 w-100">
                        {!! data_get($work, 'id', 0) !!}
                    </td>
                    <td class="border-right-black-1 border-bottom-black-1 w-100">
                        {!! data_get($work, 'name', 'Не задано') !!}
                    </td>
                    <td class="border-right-black-1 border-bottom-black-1 w-100">
                        {!! number_format(data_get($work, 'sum', 0), 2, ',', ' ') !!}
                    </td>
                    <td class="border-right-black-1 border-bottom-black-1 w-100">0.00</td>
                </tr>
            @endforeach
            </tbody>

            <tfoot>
            <tr>
                <td class="bold w-100"></td>
                <td class="bold border-right-black-1 w-100" style="border-top: 1px solid black"></td>
                <td class="bold border-right-black-1 border-bottom-black-1 w-100">
                    {!! number_format(data_get($data, 'order_works_total_sum', 0) + 0, 2, ',', ' ') !!}
                </td>
                <td class="bold surround border-bottom-black-1 w-100">0.00</td>
            </tr>
            </tfoot>
        </table>
        <div class="sto-flex sto-mb-4 bold appeal-reason-footer">
            Итого по причине обращения Руб:
        </div>

        <!-- *********************************** -->

        <p class="bold sto-mb-4 ">
            {!! mb_ucfirst(number2string(data_get($data, 'order_works_total_sum', 0))) !!} 00 копеек
        </p>

        <table class="sto-table sto-mb-4" style="border-bottom: none; border-left: none">
            <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="bold border-right-black-1">Итого Руб:</td>
                <td class="bold surround border-right-black-1 border-bottom-black-1">
                    {!! number_format(data_get($data, 'order_works_total_sum', 0) + 0, 2, ',', ' ') !!}
                </td>
                <td class="bold surround border-right-black-1 border-bottom-black-1">0.00</td>
            </tr>
            </tfoot>
        </table>

        <p class="sto-mb-1 ">
            Всего услуг {!! data_get($data, 'order_works_count') !!} на сумму
            {!! number_format(data_get($data, 'order_works_total_sum', 0) + 0, 2, ',', ' ') !!}
            Руб (в т.ч. НДС 0,00 Руб)
        </p>

        <h4 class="sto-mb-4 ">{!! mb_ucfirst(number2string(data_get($data, 'order_works_total_sum', 0))) !!} 00
            копеек</h4>

        <p class="sto-mb-3 ">
            Вышеперечисленные услуги выполнены полностью и в срок. Заказчик претензий по объему, качеству и срокам
            оказания услуг не имеет.
        </p>

        <div class="sto-flex ">
            <span class="bold">Исполнитель</span>
            <span class="border-bottom-black-1" style="padding-left: 100px;">
                / {!! data_get($data, 'order_user_name') !!}
                {!! data_get($data, 'order_user_surname') !!}
                {!! data_get($data, 'order_user_middle_name') !!} /
            </span>
            <span class="bold" style="padding-left: 50px">Заказчик ____________________________</span>
        </div>

    </div>
</div>
