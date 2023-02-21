<style>
    * {
        padding: 0;
        margin: 0;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        font-family: "DejaVu Sans", Arial, sans-serif;
        font-size: 12px !important;
    }

    #main {
        position: relative;
    }

    #main #print {
        position: -webkit-sticky;
        position: sticky;
        top: 1em;
        left: 1em;
        background-color: greenyellow;
        border: none;
        outline: none;
        padding: 0.5em 1em;
        border-radius: 5px;
        -webkit-box-shadow: 0 0 1px 0 rgba(128, 128, 128, 0.3);
        box-shadow: 0 0 1px 0 rgba(128, 128, 128, 0.3);
        cursor: pointer;
    }

    #wrapper {
        max-width: 1024px;
        margin: 1em auto;
        padding: 1em;
        -webkit-box-shadow: 0 0 1px 0 black;
        box-shadow: 0 0 1px 0 black;
        background-color: rgba(250, 250, 250, 0.3);
    }

    .max-w-90 {
        max-width: 90%;
    }
    .mb-2 {
        margin-bottom: 1em !important;
    }

    .mb-3 {
        margin-bottom: 1.5em !important;
    }

    .mb-4 {
        margin-bottom: 2em !important;
    }

    .mt-4 {
        margin-top: 2em !important;
    }

    .mx-auto {
        margin: auto;
    }

    .title-1 {
        font-size: medium;
        max-width: 50%;
    }

    .title-2 {
        font-size: x-large;
    }

    .bold {
        font-weight: bold;
    }

    ul {
        list-style: none;
    }

    ul .list-row {
        padding: 0.5em;
        border: 1px solid gray;
    }

    .flex {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
    }

    .space-between {
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
    }

    table {
        width: 100%;
        font-size: small;
        border-spacing: 0;
        border: 0.3px solid black;
        border-collapse: collapse;
    }

    .page-break {
        page-break-inside: avoid !important;
        page-break-after: always;
    }

    .table . {
        text-align: right !important;
    }

    .table td,
    .table th {
        text-align: center;
        padding: 0.3em;
    }

    .table td.wrapped,
    .table th.wrapped {
        max-width: 200px;
    }

    .table thead td,
    .table thead th,
    .table tbody td,
    .table tbody th {
        -webkit-box-shadow: 0 0 0 0.5px black;
        box-shadow: 0 0 0 0.5px black;
    }

    .table thead tr,
    .table tbody tr {
        -webkit-box-shadow: 0 0 0 0.5px black;
        box-shadow: 0 0 0 0.5px black;
    }

    .table.foot {
        padding: 0;
        -webkit-box-shadow: 0.5px -0.5px 0 0.5px black;
        box-shadow: 0.5px -0.5px 0 0.5px black;
    }

    .table tfoot td.surround {
        -webkit-box-shadow: 0 0 0 0.5px black;
        box-shadow: 0 0 0 0.5px black;
    }

    .text-center {
        text-align: center !important;
    }
    .works-title {
        margin-left: 20px;
    }
    .fs-16 {
        font-size: 16px !important;
    }
    .border-right-black-1 {
        border-right: 1px solid black;
    }
    .border-bottom-black-1 {
        border-bottom: 1px solid black;
    }
    .total-order-card {
        display:-webkit-box;
        display:-ms-flexbox;
        display:flex; -webkit-box-pack: center; -ms-flex-pack: center; justify-content: center
    }
</style>

<div id="main">
    <div id="wrapper" class="bg-gray-50 w-full border rounded border-gray-200 mx-auto mt-3 p-3 wrapper-temp">
        <div class="mb-4">
            <h1 class="title-1">ПОСТАВЩИК: ИП Арутюнян Асмик Михайловна, ИНН 231139389283, 350901, Краснодарский край,
                Краснодар г, 2-я Российская ул, дом № 67, тел.: +7 (861) 213-95-59</h1>
        </div>

        <p class="mb-2">Адрес: 350901, Краснодарский край, Краснодар г, 2-я Российская ул, дом № 67</p>

        <div>
            <h1 class="title-2 mb-2 fs-16">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Заказ-наряд №
                {!! data_get($data, 'order_id') !!} от
                {!! data_get($data, 'order_created_at') !!}
            </h1>

            <ul class="mb-4">
                <li class="list-row bold">Заказчик:
                    {!! data_get($data, 'order_client_surname') !!}
                    {!! data_get($data, 'order_client_name') !!}
                    {!! data_get($data, 'order_client_middle_name') !!}
                </li>
                <li class="list-row bold">
                    Адрес заказчика: {!! data_get($data, 'order_client_address') !!}
                    Телефоны: {!! data_get($data, 'order_client_phones') !!}
                </li>
                <li class="list-row bold">
                    Автомобиль: {!! data_get($data, 'order_car_model_name') !!}
                    гос. номер: {!! data_get($data, 'order_car_number') !!}
                    VIN: {!! data_get($data, 'order_car_vin') !!}
                    Год вып: {!! data_get($data, 'order_car_year') !!}
                    Пробег: {!! data_get($data, 'order_car_miles') !!}
                </li>
            </ul>

            <p class="mb-4">Цена автомототранспортного средства, определяемая по соглашению сторон
                __________________</p>

            <ul class="mb-2">
                <li class="list-row bold">Плательщик:
                    {!! data_get($data, 'order_client_surname') !!}
                    {!! data_get($data, 'order_client_name') !!}
                    {!! data_get($data, 'order_client_middle_name') !!}
                </li>
                <li class="list-row bold">ИНН адрес:
                    {!! data_get($data, 'order_client_address') !!}, телефоны:
                    {!! data_get($data, 'order_client_phones') !!}
                </li>
            </ul>

            <div>
                <p class="bold">Причина обращения: {!! data_get($data, 'order_appeal_reason_name') !!}</p>
                <p class="bold">Комментарий: {!! data_get($data, 'order_comments_description') !!}</p>
            </div>

            <div class="page-break">
                <h4 class="bold works-title mt-4 mb-2">
                    Выполненные работы по заказ-наряду №
                    {!! data_get($data, 'order_id') !!} от {!! data_get($data, 'order_created_at') !!}
                    к причине обращения
                    "{!! data_get($data, 'order_appeal_reason_name') !!}"
                </h4>
                <!-- Table -->
                <table class="table mt-2">
                    <thead>
                    <tr>
                        <th class="border-right-black-1 border-bottom-black-1">№</th>
                        <th class="border-right-black-1 border-bottom-black-1">Наименование</th>
                        <th class="border-right-black-1 border-bottom-black-1">Цена</th>
                        <th class="border-right-black-1 border-bottom-black-1">Всего</th>
                        <th class="border-right-black-1 border-bottom-black-1">В т.ч. НДС</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach(data_get($data, 'order_works') as $work)
                        <tr>
                            <td class="border-right-black-1 border-bottom-black-1">
                                {!! data_get($work, 'id', 0) !!}
                            </td>
                            <td class="border-right-black-1 border-bottom-black-1">
                                {!! data_get($work, 'name', 'Не задано') !!}
                            </td>
                            <td class="border-right-black-1 border-bottom-black-1 ">
                                {!! number_format(data_get($work, 'sum', 0), 2, ',', ' ') !!}
                            </td>
                            В будущем вместо + 0 ставиться сумма ндс
                            <td class="border-right-black-1 border-bottom-black-1 ">
                                {!! number_format(data_get($work, 'sum', 0) + 0, 2, ',', ' ') !!}
                            </td>
                            <td class="border-right-black-1 border-bottom-black-1 ">0.00</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <td class="border-right-black-1 bold "></td>
                        <td class="border-right-black-1 bold "></td>
                        <td class="border-right-black-1 bold ">Итого работ на сумму:</td>
                        <td class="border-right-black-1 bold  surround">
                            {!! number_format(data_get($data, 'order_works_total_sum', 0) + 0, 2, ',', ' ') !!}
                        </td>
                        <td class="border-right-black-1 bold  surround">0</td>
                    </tr>
                    </tfoot>
                </table>

                <p class="">{!! mb_ucfirst(number2string(data_get($data, 'order_works_total_sum', 0))) !!} 00 копеек</p>

                <!-- *********************************** -->

                <h4 class="bold works-title mt-4 mb-2">
                    Расходная накладная к заказ-наряду
                    № {!! data_get($data, 'order_id') !!} от {!! data_get($data, 'order_created_at') !!}
                    Причине обращения
                    "{!! data_get($data, 'order_appeal_reason_name') !!}"
                </h4>

                <!-- Table -->

                <table class="table mt-2">
                    <thead>
                    <tr>
                        <th class="border-right-black-1 border-bottom-black-1">№</th>
                        <th class="border-right-black-1 border-bottom-black-1">Артикул</th>
                        <th class="border-right-black-1 border-bottom-black-1">Наименование</th>
                        <th class="border-right-black-1 border-bottom-black-1" style="width: 100%">Кол-во</th>
                        <th class="border-right-black-1 border-bottom-black-1">Ед.изм.</th>
                        <th class="border-right-black-1 border-bottom-black-1" style="width: 100%">Цена</th>
                        <th class="border-right-black-1 border-bottom-black-1" style="width: 100%">Всего</th>
                        <th class="border-right-black-1 border-bottom-black-1" style="width: 100%">В т.ч. НДС</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach(data_get($data, 'order_products') as $product)
                        <tr>
                            <td class="border-right-black-1 border-bottom-black-1">
                                {!! data_get($product, 'id') !!}
                            </td>
                            <td class="border-right-black-1 border-bottom-black-1">
                                {!! data_get($product, 'sku') !!}
                            </td>
                            <td class="border-right-black-1 border-bottom-black-1">
                                {!! data_get($product, 'name') !!}
                            </td>
                            <td class="border-right-black-1 border-bottom-black-1 ">
                                {!! number_format(data_get($product, 'request_count', 0), 2, ',', ' ') !!}
                            </td>
                            <td class="border-right-black-1 border-bottom-black-1 ">
                                шт
                            </td>
                            <td class="border-right-black-1 border-bottom-black-1 ">
                                {!! number_format(data_get($product, 'request_sum', 0), 2, ',', ' ') !!}
                            </td>
                            {{--В будущем вместо + 0 ставиться сумма ндс--}}
                            <td class="border-right-black-1 border-bottom-black-1 ">
                                {!! number_format(data_get($product, 'request_sum', 0) + 0, 2, ',', ' ') !!}
                            </td>
                            <td class="border-right-black-1 border-bottom-black-1 ">0.00</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <td class="border-right-black-1 bold"></td>
                        <td class="border-right-black-1 bold"></td>
                        <td class="border-right-black-1 bold">Итого материалов:</td>
                        <td class="border-right-black-1 bold  surround">
                            {!! number_format(data_get($data, 'order_products_total_count', 0), 2, ',', ' ') !!}
                        </td>
                        <td class="border-right-black-1 bold "></td>
                        <td class="border-right-black-1 bold">на сумму:</td>
                        <td class="border-right-black-1 bold  surround">
                            {!! number_format(data_get($data, 'order_products_total_sum', 0), 2, ',', ' ') !!}
                        </td>
                        <td class="border-right-black-1 bold  surround">0.00</td>
                    </tr>
                    </tfoot>
                </table>

                <!-- *********************************** -->

                <p class="mb-2">{!! mb_ucfirst(number2string(data_get($data, 'order_products_total_sum', 0))) !!} 00 копеек</p>
            </div>

            <table class="table foot" style="margin: 20px 0 20px 0">
                <tfoot class="alone">
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="border-right-black-1 bold ">Итого по заказ-наряду:</td>
                    <td class="border-right-black-1 wrapped bold  surround">
                        {!! number_format(data_get($data, 'order_total_sum', 0), 2, ',', ' ') !!}
                    </td>
                    <td class="border-right-black-1 wrapped bold  surround">0.00</td>
                </tr>
                </tfoot>
            </table>

            <div class="mb-2 flex total-order-card">
                <p class="bold">Всего по заказ-наряду:</p>
                {!! mb_ucfirst(number2string(data_get($data, 'order_total_sum', 0))) !!} 00 копеек
            </div>

            <div class="max-w-90 mb-2">
                <p class="text-center mb-2">Мастер ___________________________ /
                    {!! data_get($data, 'order_user_name') !!}
                    {!! data_get($data, 'order_user_surname') !!}
                    {!! data_get($data, 'order_user_middle_name') !!}
                    /</p>
                <p class="mb-3" style="padding-left: 40px">
                    Гарантии: Гарантия на работы: слесарные - 30 дней, электрические - 30 дней, оригинальные з/ч - 30
                    дней, з/ч, связанные с электрооборудованием, подачей топлива и системой впрыска - гарантии нет,
                    неоригинальные з/ч - гарантии нет. Гарантийные обязательства на работы выполняются фирмой только
                    при предъявлении акта приемки автомобиля и тех паспорта (доверенности).
                </p>
                {{--<p>Рекомендации: Замена масла, масляного и воздушного фильтров.</p>--}}
            </div>

            <p class="bold mt-4 mb-2">3. Приложения к заказ-наряду</p>

            <p class="mb-2">Ознакомился и получил следующие документы:</p>

            <ul class="mb-2">
                <li class="flex space-between mb-2">
                    <p>
                        1) Гарантийная карта
                        <span style="padding-left: 203px">___________________________
                        /{!! data_get($data, 'order_client_surname') !!} {!! data_get($data, 'order_client_name') !!} {!! data_get($data, 'order_client_middle_name') !!}
                        /</span>
                    </p>
                </li>
                <li class="flex space-between mb-2">
                    <p>
                        2) Диагностическая карта
                        <span style="padding-left: 175px">___________________________
                        /{!! data_get($data, 'order_client_surname') !!} {!! data_get($data, 'order_client_name') !!} {!! data_get($data, 'order_client_middle_name') !!}
                        /</span>
                    </p>
                </li>
                <li class="flex space-between mb-2">
                    <p>
                        3) Акт выполненных работ - квитанция об оплате
                        <span style="padding-left: 25px">___________________________
                        /{!! data_get($data, 'order_client_surname') !!} {!! data_get($data, 'order_client_name') !!} {!! data_get($data, 'order_client_middle_name') !!}
                        /</span>
                    </p>
                </li>
                <li class="flex space-between mb-2">
                    <p>
                        4) Акт приема-передачи турбины или автомобиля
                        <span style="padding-left: 23px">___________________________
                        /{!! data_get($data, 'order_client_surname') !!} {!! data_get($data, 'order_client_name') !!} {!! data_get($data, 'order_client_middle_name') !!}
                        /</span>
                    </p>
                </li>
            </ul>

            <div class="flex space-between">
                <ul>
                    <li>
                        Дата: {!! now()->format('d.m.Y') !!}
                        <span style="padding-left: 205px">
                            Заказчик _____________________
                        /{!! data_get($data, 'order_client_surname') !!} {!! data_get($data, 'order_client_name') !!} {!! data_get($data, 'order_client_middle_name') !!}
                        /
                        </span>
                    </li>
                    <li style="padding-left: 300px; padding-top: 10px;">
                        Плательщик _____________________ /{!! data_get($data, 'order_client_surname') !!}
                        {!! data_get($data, 'order_client_name') !!}
                        {!! data_get($data, 'order_client_middle_name') !!} /
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
